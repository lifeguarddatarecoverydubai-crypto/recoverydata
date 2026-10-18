<?php

namespace Tests\Unit;

use App\Services\GoogleTwoFactorService;
use PHPUnit\Framework\TestCase;

class GoogleTwoFactorServiceTest extends TestCase
{
    protected GoogleTwoFactorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GoogleTwoFactorService;
    }

    public function test_rfc_6238_test_vectors_match(): void
    {
        $secret = 'GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ';

        // RFC 6238 standard test vectors
        $this->assertSame('287082', $this->service->calculateCode($secret, 59));
        $this->assertSame('081804', $this->service->calculateCode($secret, 1111111109));
        $this->assertSame('005924', $this->service->calculateCode($secret, 1234567890));
        $this->assertSame('279037', $this->service->calculateCode($secret, 2000000000));
    }

    public function test_generates_valid_base32_secret(): void
    {
        $secret = $this->service->generateSecretKey();

        $this->assertSame(32, strlen($secret));
        $this->assertMatchesRegularExpression('/^[A-Z2-7]+$/', $secret);
    }

    public function test_verifies_code_within_time_window(): void
    {
        $secret = $this->service->generateSecretKey();
        $code = $this->service->calculateCode($secret);

        $this->assertTrue($this->service->verifyCode($secret, $code));
        $this->assertFalse($this->service->verifyCode($secret, '000000'));
        $this->assertFalse($this->service->verifyCode($secret, 'invalid'));
    }

    public function test_generates_recovery_codes(): void
    {
        $codes = $this->service->generateRecoveryCodes(8);

        $this->assertCount(8, $codes);
        foreach ($codes as $code) {
            $this->assertMatchesRegularExpression('/^[a-z0-9]{4}-[a-z0-9]{4}$/', $code);
        }
    }

    public function test_generates_valid_otpauth_uri(): void
    {
        $uri = $this->service->getQrCodeUri('Recovery Data', 'admin@recoverydata.ae', 'JBSWY3DPEHPK3PXP');

        $this->assertStringStartsWith('otpauth://totp/', $uri);
        $this->assertStringContainsString('secret=JBSWY3DPEHPK3PXP', $uri);
        $this->assertStringContainsString('Recovery%20Data', $uri);
    }
}
