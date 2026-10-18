<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_present_on_web_responses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
    }

    public function test_bot_honeypot_silently_discards_spam_submissions(): void
    {
        $response = $this->post('/contact', [
            '_hp_website' => 'http://spam-bot-url.com',
            'name' => 'Spam Bot',
            'email' => 'bot@spammer.com',
            'subject' => 'Buy Cheap Pharma Drugs',
            'message' => 'Spam message',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Confirm NO inquiry was saved in database
        $this->assertDatabaseMissing('inquiries', [
            'email' => 'bot@spammer.com',
        ]);
        $this->assertEquals(0, Inquiry::count());
    }

    public function test_legitimate_contact_form_submission_is_sanitized_and_stored(): void
    {
        $response = $this->post('/contact', [
            '_hp_website' => '', // Clean honeypot
            'name' => '<b>Dr. Tariq</b><script>alert("xss")</script>',
            'email' => 'tariq@client.ae',
            'subject' => 'Urgent Server RAID 5 Recovery',
            'message' => '<p>Server dropped offline after power surge.</p>',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Confirm HTML tags were stripped from database
        $this->assertDatabaseHas('inquiries', [
            'name' => 'Dr. Tariqalert("xss")',
            'email' => 'tariq@client.ae',
            'subject' => 'Urgent Server RAID 5 Recovery',
        ]);
    }

    public function test_media_upload_rejects_dangerous_executable_files(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Attempt uploading PHP webshell
        $dangerousFile = UploadedFile::fake()->create('shell.php', 10, 'text/x-php');

        $response = $this->actingAs($admin)->post('/admin/media', [
            'file' => $dangerousFile,
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseCount('media', 0);
    }

    public function test_media_upload_accepts_valid_image_files(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $validFile = UploadedFile::fake()->image('cleanroom-lab.jpg', 600, 400);

        $response = $this->actingAs($admin)->post('/admin/media', [
            'file' => $validFile,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('media', [
            'name' => 'cleanroom-lab',
        ]);
    }
}
