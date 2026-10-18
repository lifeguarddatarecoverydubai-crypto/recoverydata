<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a static CMS page.
     */
    public function show(string $slug): View|RedirectResponse
    {
        // 301 Redirect cannibalizing and duplicate page slugs to canonical URLs
        $canonicalServiceRedirects = [
            'hard-drive-recovery' => route('service.show', 'hard-drive-recovery'),
            'hard-drive-data-recovery-in-dubai' => route('service.show', 'hard-drive-recovery'),
            'ssd-recovery-dubai' => route('service.show', 'ssd-recovery-dubai'),
            'nvme-ssd-recovery-dubai' => route('service.show', 'nvme-ssd-recovery-dubai'),
            'raid-recovery-dubai' => route('service.show', 'raid-recovery-dubai'),
            'server-recovery-dubai' => route('service.show', 'raid-recovery-dubai'),
            'nas-recovery-dubai' => route('service.show', 'nas-recovery-dubai'),
            'cctv-footage-recovery-dubai' => route('service.show', 'cctv-footage-recovery-dubai'),
            'usb-data-recovery-dubai' => route('service.show', 'usb-data-recovery-dubai'),
            'sd-card-recovery-dubai' => route('service.show', 'usb-data-recovery-dubai'),
            'iphone-data-recovery-dubai' => route('service.show', 'iphone-data-recovery-dubai'),
            'ransomware-data-recovery-dubai' => route('service.show', 'ransomware-data-recovery-dubai'),
            'home' => route('home'),
            'services' => route('service.index'),
            'about' => route('about'),
            'contact' => route('contact'),
        ];

        if (isset($canonicalServiceRedirects[$slug])) {
            return redirect($canonicalServiceRedirects[$slug], 301);
        }

        $pageQuery = Page::with('user')->where('slug', $slug);

        if (! auth()->check() || ! auth()->user()->canManageContent()) {
            $pageQuery->where('status', 'published');
        }

        $page = $pageQuery->firstOrFail();

        $viewName = match ($page->template) {
            'full-width' => 'frontend.pages.full-width',
            'contact' => 'frontend.pages.contact',
            default => 'frontend.pages.show',
        };

        return view($viewName, compact('page'));
    }
}
