<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRedirectRequest;
use App\Http\Requests\Admin\UpdateRedirectRequest;
use App\Models\RedirectRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RedirectController extends Controller
{
    /**
     * Display a listing of all 301/302 redirects with filtering, search & stats.
     */
    public function index(Request $request): View
    {
        $query = RedirectRule::query();

        // Search filtering
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        // Group filtering
        if ($request->filled('group') && $request->input('group') !== 'all') {
            $query->group($request->input('group'));
        }

        // Status code filtering (301, 302, 307, 308, 410)
        if ($request->filled('status_code') && $request->input('status_code') !== 'all') {
            $query->where('status_code', (int) $request->input('status_code'));
        }

        // Match type filtering (exact, wildcard, regex)
        if ($request->filled('match_type') && $request->input('match_type') !== 'all') {
            $query->where('match_type', $request->input('match_type'));
        }

        // Active state filtering
        if ($request->filled('status') && $request->input('status') !== 'all') {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'id');
        $direction = strtolower($request->input('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['id', 'source_url', 'target_url', 'status_code', 'hits', 'last_accessed_at', 'order', 'created_at'];
        if (in_array($sortBy, $allowedSorts, true)) {
            $query->orderBy($sortBy, $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        $redirects = $query->paginate(25)->withQueryString();

        // High-level statistics
        $stats = [
            'total' => RedirectRule::count(),
            'active' => RedirectRule::where('is_active', true)->count(),
            'inactive' => RedirectRule::where('is_active', false)->count(),
            'total_hits' => (int) RedirectRule::sum('hits'),
            'permanent_301' => RedirectRule::where('status_code', 301)->count(),
            'temporary_302' => RedirectRule::whereIn('status_code', [302, 307])->count(),
            'gone_410' => RedirectRule::where('status_code', 410)->count(),
        ];

        // Unique groups for filter dropdown
        $groups = RedirectRule::whereNotNull('group')
            ->where('group', '!=', '')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');

        return view('admin.redirects.index', compact('redirects', 'stats', 'groups'));
    }

    /**
     * Store a newly created redirect rule.
     */
    public function store(StoreRedirectRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $normSource = RedirectRule::normalizeUrl($validated['source_url'], true);
        $normTarget = ! empty($validated['target_url'])
            ? RedirectRule::normalizeUrl($validated['target_url'], false)
            : null;

        $rule = RedirectRule::create([
            'source_url' => $normSource,
            'target_url' => $normTarget,
            'status_code' => (int) $validated['status_code'],
            'match_type' => $validated['match_type'],
            'query_param_option' => $validated['query_param_option'],
            'group' => $validated['group'] ?? 'General',
            'description' => $validated['description'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()->route('admin.redirects.index')
            ->with('success', "Redirect rule from '{$rule->source_url}' to '{$rule->target_url}' (HTTP {$rule->status_code}) created successfully!");
    }

    /**
     * Update the specified redirect rule.
     */
    public function update(UpdateRedirectRequest $request, RedirectRule $redirect): RedirectResponse
    {
        $validated = $request->validated();

        $normSource = RedirectRule::normalizeUrl($validated['source_url'], true);
        $normTarget = ! empty($validated['target_url'])
            ? RedirectRule::normalizeUrl($validated['target_url'], false)
            : null;

        $redirect->update([
            'source_url' => $normSource,
            'target_url' => $normTarget,
            'status_code' => (int) $validated['status_code'],
            'match_type' => $validated['match_type'],
            'query_param_option' => $validated['query_param_option'],
            'group' => $validated['group'] ?? 'General',
            'description' => $validated['description'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('admin.redirects.index')
            ->with('success', "Redirect rule #{$redirect->id} updated successfully!");
    }

    /**
     * Remove the specified redirect rule.
     */
    public function destroy(RedirectRule $redirect): RedirectResponse
    {
        $source = $redirect->source_url;
        $redirect->delete();

        return redirect()->route('admin.redirects.index')
            ->with('success', "Redirect rule for '{$source}' removed successfully.");
    }

    /**
     * Toggle the active status of a redirect rule (via AJAX or form POST).
     */
    public function toggle(RedirectRule $redirect, Request $request): JsonResponse|RedirectResponse
    {
        $redirect->is_active = ! $redirect->is_active;
        $redirect->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $redirect->is_active,
                'message' => 'Redirect status updated.',
            ]);
        }

        return redirect()->back()->with('success', "Redirect rule #{$redirect->id} status updated.");
    }

    /**
     * Reset the hit counter for a redirect rule.
     */
    public function resetHits(RedirectRule $redirect): RedirectResponse
    {
        $redirect->update([
            'hits' => 0,
            'last_accessed_at' => null,
        ]);

        return redirect()->back()->with('success', "Hit counter for rule #{$redirect->id} has been reset to 0.");
    }

    /**
     * Perform bulk operations on selected redirect rules.
     */
    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:enable,disable,delete,reset_hits'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer', 'exists:redirect_rules,id'],
        ]);

        $ids = $validated['selected'];
        $count = count($ids);

        switch ($validated['action']) {
            case 'enable':
                RedirectRule::whereIn('id', $ids)->update(['is_active' => true]);
                RedirectRule::clearCache();
                $message = "Enabled {$count} redirect rules.";
                break;

            case 'disable':
                RedirectRule::whereIn('id', $ids)->update(['is_active' => false]);
                RedirectRule::clearCache();
                $message = "Disabled {$count} redirect rules.";
                break;

            case 'reset_hits':
                RedirectRule::whereIn('id', $ids)->update(['hits' => 0, 'last_accessed_at' => null]);
                $message = "Reset hits counter for {$count} redirect rules.";
                break;

            case 'delete':
                RedirectRule::whereIn('id', $ids)->delete();
                RedirectRule::clearCache();
                $message = "Deleted {$count} redirect rules.";
                break;

            default:
                $message = 'No action performed.';
        }

        return redirect()->route('admin.redirects.index')->with('success', $message);
    }

    /**
     * Live URL Simulation Diagnostic Tool.
     * Evaluates any test path against active rules and returns diagnostic output.
     */
    public function test(Request $request): JsonResponse
    {
        $testUrl = trim($request->input('url', ''));

        if (empty($testUrl)) {
            return response()->json([
                'matched' => false,
                'message' => 'Please enter a test URL or relative path.',
            ], 422);
        }

        $startTime = microtime(true);

        // Normalize test path
        $path = parse_url($testUrl, PHP_URL_PATH) ?? '/';
        $query = parse_url($testUrl, PHP_URL_QUERY);
        $fullUri = $query ? $path.'?'.$query : $path;

        $rules = RedirectRule::getActiveCachedRules();
        $matchedResult = null;

        foreach ($rules as $ruleData) {
            $target = RedirectRule::evaluateRuleArrayMatch($ruleData, $fullUri, $path, $query);

            if ($target !== null) {
                $matchedResult = [
                    'rule' => $ruleData,
                    'target_url' => $target,
                    'status_code' => (int) $ruleData['status_code'],
                ];
                break;
            }
        }

        $latency = round((microtime(true) - $startTime) * 1000, 2);

        if ($matchedResult) {
            $rule = $matchedResult['rule'];

            return response()->json([
                'matched' => true,
                'tested_url' => $testUrl,
                'status_code' => $matchedResult['status_code'],
                'target_url' => $matchedResult['target_url'],
                'rule_id' => $rule['id'],
                'match_type' => $rule['match_type'],
                'group' => $rule['group'] ?? 'General',
                'description' => $rule['description'] ?? null,
                'latency_ms' => $latency,
                'message' => "Matched Rule #{$rule['id']} ({$rule['match_type']}) -> Redirects to {$matchedResult['target_url']} with HTTP {$matchedResult['status_code']}",
            ]);
        }

        return response()->json([
            'matched' => false,
            'tested_url' => $testUrl,
            'latency_ms' => $latency,
            'message' => 'No active redirect rule matched this path. Normal Laravel routing / 404 will apply.',
        ]);
    }

    /**
     * Export all redirect rules to CSV (Screaming Frog / Excel compatible).
     */
    public function exportCsv(): StreamedResponse
    {
        $filename = 'redirects-export-'.date('Y-m-d-His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return ResponseFacade::stream(function () {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID',
                'Source URL',
                'Target URL',
                'Status Code',
                'Match Type',
                'Query Param Option',
                'Group',
                'Description',
                'Is Active',
                'Hits',
                'Last Accessed At',
                'Created At',
            ]);

            RedirectRule::orderBy('id', 'asc')->chunk(200, function ($rules) use ($handle) {
                foreach ($rules as $rule) {
                    fputcsv($handle, [
                        $rule->id,
                        $rule->source_url,
                        $rule->target_url,
                        $rule->status_code,
                        $rule->match_type,
                        $rule->query_param_option,
                        $rule->group,
                        $rule->description,
                        $rule->is_active ? '1' : '0',
                        $rule->hits,
                        $rule->last_accessed_at ? $rule->last_accessed_at->toDateTimeString() : '',
                        $rule->created_at ? $rule->created_at->toDateTimeString() : '',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Import redirect rules from a CSV file.
     */
    public function importCsv(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        if (! $handle) {
            return redirect()->back()->with('error', 'Unable to read the uploaded CSV file.');
        }

        // Read header row
        $header = fgetcsv($handle);
        if (! $header) {
            fclose($handle);

            return redirect()->back()->with('error', 'The uploaded file appears to be empty.');
        }

        // Normalize header names
        $headerMap = [];
        foreach ($header as $index => $col) {
            $cleaned = strtolower(trim($col));
            $cleaned = str_replace([' ', '_'], '', $cleaned);
            $headerMap[$cleaned] = $index;
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            // Determine source & target from header map or default index 0,1
            $sourceIndex = $headerMap['sourceurl'] ?? $headerMap['source'] ?? 1;
            $targetIndex = $headerMap['targeturl'] ?? $headerMap['target'] ?? 2;
            $codeIndex = $headerMap['statuscode'] ?? $headerMap['code'] ?? 3;
            $typeIndex = $headerMap['matchtype'] ?? $headerMap['type'] ?? 4;
            $queryIndex = $headerMap['queryparamoption'] ?? $headerMap['query'] ?? 5;
            $groupIndex = $headerMap['group'] ?? 6;
            $descIndex = $headerMap['description'] ?? 7;

            $source = isset($row[$sourceIndex]) ? trim($row[$sourceIndex]) : '';
            $target = isset($row[$targetIndex]) ? trim($row[$targetIndex]) : '';

            if (empty($source)) {
                $skipped++;

                continue;
            }

            $statusCode = isset($row[$codeIndex]) && in_array((int) $row[$codeIndex], [301, 302, 307, 308, 410], true)
                ? (int) $row[$codeIndex]
                : 301;

            $matchType = isset($row[$typeIndex]) && in_array(strtolower($row[$typeIndex]), ['exact', 'wildcard', 'regex'], true)
                ? strtolower($row[$typeIndex])
                : 'exact';

            $queryOption = isset($row[$queryIndex]) && in_array(strtolower($row[$queryIndex]), ['ignore', 'pass', 'exact'], true)
                ? strtolower($row[$queryIndex])
                : 'ignore';

            $group = isset($row[$groupIndex]) && ! empty(trim($row[$groupIndex])) ? trim($row[$groupIndex]) : 'General';
            $description = isset($row[$descIndex]) ? trim($row[$descIndex]) : null;

            $normSource = RedirectRule::normalizeUrl($source, true);
            $normTarget = ! empty($target) ? RedirectRule::normalizeUrl($target, false) : null;

            // Prevent self loop
            if ($statusCode !== 410 && ! empty($normTarget) && strtolower($normSource) === strtolower($normTarget)) {
                $skipped++;

                continue;
            }

            $rule = RedirectRule::firstOrNew(['source_url' => $normSource]);
            $isNew = ! $rule->exists;

            $rule->fill([
                'target_url' => $normTarget,
                'status_code' => $statusCode,
                'match_type' => $matchType,
                'query_param_option' => $queryOption,
                'group' => $group,
                'description' => $description,
                'is_active' => true,
            ]);
            $rule->save();

            if ($isNew) {
                $imported++;
            } else {
                $updated++;
            }
        }

        fclose($handle);
        RedirectRule::clearCache();

        return redirect()->route('admin.redirects.index')
            ->with('success', "Import completed: {$imported} new rules created, {$updated} existing rules updated, {$skipped} skipped.");
    }

    /**
     * Re-seed default legacy redirects.
     */
    public function seedDefaults(): RedirectResponse
    {
        RedirectRule::seedDefaults();
        RedirectRule::clearCache();

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Default legacy 301 redirects successfully synchronized.');
    }
}
