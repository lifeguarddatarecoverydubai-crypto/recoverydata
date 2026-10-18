<?php

namespace App\Services;

use Illuminate\Support\Str;

class GoogleTwoFactorService
{
    /**
     * RFC 4648 Base32 alphabet.
     */
    protected const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Generate a new random Base32 secret key for Google Authenticator.
     */
    public function generateSecretKey(int $length = 20): string
    {
        $random = random_bytes($length);

        return $this->base32Encode($random);
    }

    /**
     * Format secret key into readable groups (e.g. ABCD EFGH IJKL MNOP).
     */
    public function formatSecretForDisplay(string $secret): string
    {
        return trim(chunk_split(strtoupper($secret), 4, ' '));
    }

    /**
     * Calculate current 6-digit TOTP code for a secret at a given timestamp.
     */
    public function calculateCode(string $secret, ?int $timestamp = null): string
    {
        $timestamp = $timestamp ?? time();
        $key = $this->base32Decode($secret);

        if ($key === '') {
            return '';
        }

        $timeSlice = (int) floor($timestamp / 30);
        $timePacked = pack('N*', 0).pack('N*', $timeSlice);

        $hmac = hash_hmac('sha1', $timePacked, $key, true);
        $offset = ord($hmac[19]) & 0x0F;

        $hash = (
            ((ord($hmac[$offset]) & 0x7F) << 24) |
            ((ord($hmac[$offset + 1]) & 0xFF) << 16) |
            ((ord($hmac[$offset + 2]) & 0xFF) << 8) |
            (ord($hmac[$offset + 3]) & 0xFF)
        ) % 1000000;

        return str_pad((string) $hash, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verify a 6-digit TOTP code against a secret key within a time window.
     */
    public function verifyCode(string $secret, string $code, int $discrepancy = 1): bool
    {
        $code = preg_replace('/\s+/', '', trim($code));

        if (! is_string($code) || strlen($code) !== 6 || ! ctype_digit($code)) {
            return false;
        }

        $currentTime = time();

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $checkTime = $currentTime + ($i * 30);
            $calculatedCode = $this->calculateCode($secret, $checkTime);

            if ($calculatedCode !== '' && hash_equals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate an otpauth:// URL suitable for Google Authenticator QR codes.
     */
    public function getQrCodeUri(string $companyName, string $email, string $secret): string
    {
        $cleanCompany = trim($companyName);
        $cleanEmail = trim($email);
        $cleanSecret = strtoupper(trim($secret));

        $label = rawurlencode($cleanCompany).':'.rawurlencode($cleanEmail);
        $issuer = rawurlencode($cleanCompany);

        return "otpauth://totp/{$label}?secret={$cleanSecret}&issuer={$issuer}&algorithm=SHA1&digits=6&period=30";
    }

    /**
     * Generate a set of cryptographically random single-use recovery codes.
     *
     * @return list<string>
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = sprintf(
                '%s-%s',
                strtolower(Str::random(4)),
                strtolower(Str::random(4))
            );
        }

        return $codes;
    }

    /**
     * Encode binary data into RFC 4648 Base32 string.
     */
    public function base32Encode(string $data): string
    {
        if ($data === '') {
            return '';
        }

        $binary = '';
        $length = strlen($data);

        for ($i = 0; $i < $length; $i++) {
            $binary .= str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);
        }

        $base32 = '';
        $chunks = str_split($binary, 5);

        foreach ($chunks as $chunk) {
            $chunkPadded = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
            $base32 .= self::BASE32_ALPHABET[bindec($chunkPadded)];
        }

        return $base32;
    }

    /**
     * Decode an RFC 4648 Base32 string into binary data.
     */
    public function base32Decode(string $b32): string
    {
        $b32 = strtoupper(str_replace(['=', ' ', '-'], '', $b32));

        if ($b32 === '') {
            return '';
        }

        $lut = array_flip(str_split(self::BASE32_ALPHABET));
        $buffer = 0;
        $bitsLeft = 0;
        $binary = '';
        $len = strlen($b32);

        for ($i = 0; $i < $len; $i++) {
            $char = $b32[$i];

            if (! isset($lut[$char])) {
                return '';
            }

            $buffer = ($buffer << 5) | $lut[$char];
            $bitsLeft += 5;

            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $binary .= chr(($buffer >> $bitsLeft) & 0xFF);
            }
        }

        return $binary;
    }
}
