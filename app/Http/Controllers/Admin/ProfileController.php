<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the user profile form.
     */
    public function edit(): View
    {
        $user = auth()->user();

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'bio' => ['nullable', 'string'],
            'avatar' => ['nullable', 'string'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->bio = $validated['bio'] ?? null;
        if (! empty($validated['avatar'])) {
            $user->avatar = $validated['avatar'];
        }

        if (! empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Regenerate emergency two-factor recovery codes.
     */
    public function regenerateRecoveryCodes(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->hasTwoFactorEnabled()) {
            return back()->withErrors(['two_factor' => 'Two-factor authentication is not enabled on your account.']);
        }

        $codes = $user->generateTwoFactorRecoveryCodes();

        return redirect()->route('admin.profile.edit')
            ->with('success', 'A new set of 8 recovery codes has been generated.')
            ->with('show_recovery_codes', $codes);
    }

    /**
     * Reset and re-initialize two-factor authentication.
     */
    public function resetTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();
        $user->resetTwoFactor();

        return redirect()->route('login.two-factor.setup')
            ->with('success', 'Two-Factor Authentication has been reset. Please scan the newly generated QR code to re-link Google Authenticator.');
    }
}
