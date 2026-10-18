<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StorageFallbackController extends Controller
{
    /**
     * Handle requests for storage files that may be missing on disk.
     */
    public function handle(Request $request, string $path): Response
    {
        // 1. Guard against path traversal attacks
        if (str_contains($path, '..') || str_contains($path, "\0")) {
            abort(404);
        }

        $cleanPath = ltrim($path, '/\\');

        // 2. Check if the physical file actually exists in storage/app/public
        $storageDiskPath = storage_path('app/public/'.$cleanPath);
        if (file_exists($storageDiskPath) && is_file($storageDiskPath)) {
            return response()->file($storageDiskPath);
        }

        // Also check public/storage directly
        $publicStoragePath = public_path('storage/'.$cleanPath);
        if (file_exists($publicStoragePath) && is_file($publicStoragePath)) {
            return response()->file($publicStoragePath);
        }

        // 3. Resolve appropriate SVG placeholder based on file name or path
        $lower = strtolower($cleanPath);
        $placeholderName = 'blog-default.svg';

        if (str_contains($lower, 'ssd') || str_contains($lower, 'nvme') || str_contains($lower, 'm2')) {
            $placeholderName = 'ssd-nvme.svg';
        } elseif (str_contains($lower, 'hard-disk') || str_contains($lower, 'hard-drive') || str_contains($lower, 'hdd') || str_contains($lower, 'drive')) {
            $placeholderName = 'hard-drive.svg';
        } elseif (str_contains($lower, 'raid') || str_contains($lower, 'server')) {
            $placeholderName = 'raid-server.svg';
        } elseif (str_contains($lower, 'nas') || str_contains($lower, 'synology') || str_contains($lower, 'qnap')) {
            $placeholderName = 'nas-storage.svg';
        } elseif (str_contains($lower, 'cctv') || str_contains($lower, 'dvr') || str_contains($lower, 'footage') || str_contains($lower, 'camera')) {
            $placeholderName = 'cctv-dvr.svg';
        } elseif (str_contains($lower, 'iphone') || str_contains($lower, 'android') || str_contains($lower, 'phone') || str_contains($lower, 'mobile')) {
            $placeholderName = 'mobile-iphone.svg';
        } elseif (str_contains($lower, 'usb') || str_contains($lower, 'sd') || str_contains($lower, 'flash') || str_contains($lower, 'card')) {
            $placeholderName = 'usb-sd.svg';
        } elseif (str_contains($lower, 'ransomware') || str_contains($lower, 'decrypt')) {
            $placeholderName = 'ransomware.svg';
        } elseif (str_contains($lower, 'mac') || str_contains($lower, 'laptop') || str_contains($lower, 'apple') || str_contains($lower, 'windows')) {
            $placeholderName = 'macbook-laptop.svg';
        } elseif (str_contains($lower, 'cloud') || str_contains($lower, 'whatsapp') || str_contains($lower, 'backup')) {
            $placeholderName = 'cloud-backup.svg';
        } elseif (str_contains($lower, 'cleanroom') || str_contains($lower, 'lab')) {
            $placeholderName = 'cleanroom-lab.svg';
        }

        $fallbackPath = public_path('images/placeholders/'.$placeholderName);
        if (! file_exists($fallbackPath)) {
            $fallbackPath = public_path('images/recoverydata-logo.png');
        }

        return response()->file($fallbackPath, [
            'Content-Type' => str_ends_with($fallbackPath, '.svg') ? 'image/svg+xml; charset=utf-8' : 'image/png',
            'Cache-Control' => 'public, max-age=86400, stale-while-revalidate=604800',
        ]);
    }
}
