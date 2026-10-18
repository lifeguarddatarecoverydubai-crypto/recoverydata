<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Show the contact page.
     */
    public function show(): View
    {
        return view('frontend.contact');
    }

    /**
     * Handle the contact form submission.
     */
    public function submit(Request $request): RedirectResponse
    {
        // 1. Silent Bot Honeypot: Discard automated spam bots cleanly
        if ($request->filled('_hp_website') || $request->filled('bot_check')) {
            return back()->with('success', 'Thank you for contacting Recovery Data Dubai! We have received your request.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
            'device_model' => ['nullable', 'string', 'max:255'],
            'pickup_requested' => ['nullable', 'string', 'max:20'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        $messageParts = [];
        if (! empty($validated['device_model'])) {
            $messageParts[] = 'Storage Device: '.strip_tags($validated['device_model']);
        }
        if (! empty($validated['pickup_requested']) && in_array(strtolower($validated['pickup_requested']), ['yes', '1', 'true', 'on'])) {
            $messageParts[] = 'Courier Service: Free UAE Lab Pickup Requested';
        }
        if (! empty($validated['message'])) {
            $messageParts[] = 'Symptoms / Details: '.strip_tags($validated['message']);
        }

        $fullMessage = ! empty($messageParts) ? implode("\n\n", $messageParts) : (! empty($validated['message']) ? strip_tags($validated['message']) : null);

        $type = $validated['type'] ?? 'contact';
        if (empty($validated['type'])) {
            if (! empty($validated['device_model']) || stripos($validated['subject'], 'evaluation') !== false) {
                $type = 'evaluation';
            } elseif (stripos($validated['subject'], 'odoo') !== false) {
                $type = 'odoo_demo';
            } elseif (stripos($validated['subject'], 'demo') !== false) {
                $type = 'demo';
            }
        }

        Inquiry::create([
            'name' => strip_tags(trim($validated['name'])),
            'email' => trim($validated['email']),
            'phone' => ! empty($validated['phone']) ? strip_tags(trim($validated['phone'])) : null,
            'company' => ! empty($validated['company']) ? strip_tags(trim($validated['company'])) : null,
            'subject' => strip_tags(trim($validated['subject'])),
            'message' => $fullMessage,
            'type' => $type,
            'is_read' => false,
        ]);

        return back()->with('success', 'Thank you for contacting Recovery Data Dubai! We have received your diagnostic evaluation request and our laboratory engineers will reach out to you shortly.');
    }
}
