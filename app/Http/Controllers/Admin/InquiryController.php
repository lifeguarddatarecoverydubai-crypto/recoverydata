<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    /**
     * Display a listing of client leads & inquiries.
     */
    public function index(Request $request): View
    {
        $type = $request->query('type');
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Inquiry::latest();

        if ($type && in_array($type, ['contact', 'demo', 'odoo_demo'])) {
            $query->where('type', $type);
        }

        if ($status === 'unread') {
            $query->where('is_read', false);
        } elseif ($status === 'read') {
            $query->where('is_read', true);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Inquiry::count(),
            'unread' => Inquiry::unread()->count(),
            'odoo_demo' => Inquiry::where('type', 'odoo_demo')->count(),
            'demo' => Inquiry::where('type', 'demo')->count(),
            'contact' => Inquiry::where('type', 'contact')->count(),
        ];

        return view('admin.inquiries.index', compact('inquiries', 'counts', 'type', 'status', 'search'));
    }

    /**
     * Display the specified inquiry and mark as read.
     */
    public function show(Inquiry $inquiry): View
    {
        if (! $inquiry->is_read) {
            $inquiry->update(['is_read' => true]);
        }

        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Toggle read status.
     */
    public function toggleRead(Inquiry $inquiry): RedirectResponse
    {
        $inquiry->update(['is_read' => ! $inquiry->is_read]);

        return back()->with('success', 'Inquiry status updated successfully.');
    }

    /**
     * Delete an inquiry.
     */
    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Lead inquiry deleted successfully.');
    }
}
