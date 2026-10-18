<?php

namespace App\Models;

use App\Services\GoogleTwoFactorService;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'avatar',
        'is_active',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $appends = [
        'avatar_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Accessor for full avatar URL with fallback.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http')) {
                return $this->avatar;
            }

            if (str_starts_with($this->avatar, 'images/')) {
                return asset($this->avatar);
            }

            return asset('storage/'.ltrim($this->avatar, '/'));
        }

        $name = urlencode($this->name ?: 'User');

        return "https://ui-avatars.com/api/?name={$name}&color=2563EB&background=DBEAFE&size=128";
    }

    /**
     * Check if user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an editor.
     */
    public function isEditor(): bool
    {
        return in_array($this->role, ['admin', 'editor']);
    }

    /**
     * Check if user can manage content.
     */
    public function canManageContent(): bool
    {
        return $this->is_active && in_array($this->role, ['admin', 'editor', 'author']);
    }

    /**
     * Posts authored by the user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Pages authored by the user.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    /**
     * Media uploaded by the user.
     */
    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Check if user has active two-factor authentication.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return ! empty($this->two_factor_secret) && ! is_null($this->two_factor_confirmed_at);
    }

    /**
     * Verify a 6-digit Google Authenticator code for this user.
     */
    public function verifyTwoFactorCode(string $code): bool
    {
        if (empty($this->two_factor_secret)) {
            return false;
        }

        return app(GoogleTwoFactorService::class)->verifyCode($this->two_factor_secret, $code);
    }

    /**
     * Verify and consume a single-use recovery code.
     */
    public function verifyTwoFactorRecoveryCode(string $code): bool
    {
        $normalizedCode = strtolower(trim($code));
        $codes = $this->two_factor_recovery_codes ?? [];

        if (! is_array($codes) || empty($codes)) {
            return false;
        }

        foreach ($codes as $index => $recoveryCode) {
            if (hash_equals(strtolower(trim($recoveryCode)), $normalizedCode)) {
                // Remove used code to ensure single-use security
                unset($codes[$index]);
                $this->two_factor_recovery_codes = array_values($codes);
                $this->save();

                return true;
            }
        }

        return false;
    }

    /**
     * Generate and persist a fresh set of recovery codes.
     *
     * @return list<string>
     */
    public function generateTwoFactorRecoveryCodes(): array
    {
        $codes = app(GoogleTwoFactorService::class)->generateRecoveryCodes(8);
        $this->two_factor_recovery_codes = $codes;
        $this->save();

        return $codes;
    }

    /**
     * Reset and disable two-factor authentication.
     */
    public function resetTwoFactor(): void
    {
        $this->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }
}
