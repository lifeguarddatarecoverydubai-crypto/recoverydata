@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">My Profile</h1>
        <p class="text-xs text-slate-500 mt-1">Manage your CMS account details and password credentials</p>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Avatar Section -->
            <div class="flex items-center gap-6 pb-6 border-b border-slate-100">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" id="profileAvatarPreview" class="w-16 h-16 rounded-full object-cover border-2 border-brand-500 shadow-md">
                <div class="space-y-1">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Profile Photo</h3>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="avatar" id="avatarInput" value="{{ old('avatar', $user->avatar) }}">
                        <button type="button" onclick="chooseProfileAvatar()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                            Change from Media
                        </button>
                    </div>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Full Name</label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $user->name) }}"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" required value="{{ old('email', $user->email) }}"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>
            </div>

            <div>
                <label for="bio" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Biographical Info</label>
                <textarea id="bio" name="bio" rows="3" placeholder="Share a brief bio that displays on your articles..."
                          class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <!-- Change Password Section -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <h3 class="text-sm font-bold text-slate-900">Change Password</h3>
                <p class="text-xs text-slate-400">Leave blank if you do not wish to change your password.</p>

                <div>
                    <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Current Password</label>
                    <input type="password" id="current_password" name="current_password" placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">New Password</label>
                        <input type="password" id="new_password" name="new_password" placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Confirm New Password</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-500/20 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Two-Factor Authentication (Google Authenticator) Card -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6" x-data="{ resetModal: false }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-100 text-brand-600 flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-bold text-slate-900">Two-Factor Authentication (Google Authenticator)</h2>
                        @if($user->hasTwoFactorEnabled())
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Active &amp; Enforced
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                Pending Setup
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        Protects your account against unauthorized logins using time-based one-time passwords (TOTP).
                    </p>
                </div>
            </div>

            @if($user->hasTwoFactorEnabled())
                <div class="flex flex-wrap items-center gap-2">
                    <form action="{{ route('admin.profile.two-factor.recovery-codes') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                            <span>Regenerate Recovery Codes</span>
                        </button>
                    </form>
                    <button type="button" @click="resetModal = true" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset 2FA</span>
                    </button>
                </div>
            @else
                <a href="{{ route('login.two-factor.setup') }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-brand-500/20">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Set Up Google 2FA</span>
                </a>
            @endif
        </div>

        @if(session('show_recovery_codes'))
            <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 space-y-3">
                <div class="flex items-center gap-2 text-amber-900 font-bold text-xs uppercase tracking-wider">
                    <i data-lucide="key" class="w-4 h-4 text-amber-600"></i>
                    <span>New Emergency Recovery Codes Generated</span>
                </div>
                <p class="text-xs text-amber-800 leading-relaxed">
                    Store these single-use recovery codes in a secure location. You will not be able to view them again:
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1">
                    @foreach(session('show_recovery_codes') as $rc)
                        <div class="bg-white border border-amber-200 rounded-lg px-2.5 py-1.5 text-center font-mono font-bold text-xs text-slate-800 select-all shadow-xs">
                            {{ $rc }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-slate-600">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <i data-lucide="smartphone" class="w-4 h-4 text-brand-600"></i>
                    <span>Google Authenticator</span>
                </div>
                <p class="text-slate-500 text-[11px] leading-relaxed">
                    Compatible with iOS &amp; Android Google Authenticator, Microsoft Authenticator, and Authy.
                </p>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <i data-lucide="shield" class="w-4 h-4 text-emerald-600"></i>
                    <span>AES-256 Encryption</span>
                </div>
                <p class="text-slate-500 text-[11px] leading-relaxed">
                    Your secret key is stored encrypted at rest in the database with zero plain-text leakage.
                </p>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <i data-lucide="life-buoy" class="w-4 h-4 text-purple-600"></i>
                    <span>Emergency Fallback</span>
                </div>
                <p class="text-slate-500 text-[11px] leading-relaxed">
                    Single-use recovery codes allow logging in if your mobile device is lost or inaccessible.
                </p>
            </div>
        </div>

        <!-- Reset 2FA Confirmation Modal -->
        <div x-show="resetModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4" @click.away="resetModal = false">
                <div class="flex items-center gap-3 text-rose-600">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center shrink-0 border border-rose-100">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Reset Two-Factor Authentication</h3>
                        <p class="text-xs text-slate-500">Confirm with your current password</p>
                    </div>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed">
                    Resetting will detach your current Google Authenticator device and require re-linking with a newly generated QR code.
                </p>

                <form action="{{ route('admin.profile.two-factor.reset') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="modal_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Current Password</label>
                        <input type="password" id="modal_password" name="password" required placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="resetModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-md shadow-rose-600/20">
                            Confirm Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function chooseProfileAvatar() {
        openMediaModal(function(item) {
            document.getElementById('avatarInput').value = item.url;
            document.getElementById('profileAvatarPreview').src = item.url;
        });
    }
</script>
@endpush
@endsection
