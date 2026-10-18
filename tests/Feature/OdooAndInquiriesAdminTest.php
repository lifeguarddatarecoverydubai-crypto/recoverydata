<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\OdooPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OdooAndInquiriesAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_odoo_admin_edit_page(): void
    {
        $response = $this->get('/admin/odoo');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_odoo_partner_edit_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/odoo');
        $response->assertStatus(200);
        $response->assertSee('Odoo Partner Landing Page');
        $response->assertSee('Official UAE Partner');
        $response->assertSee('Hero Banner & Main Value Proposition', false);
    }

    public function test_admin_can_update_odoo_partner_content_and_seo(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $odoo = OdooPage::getSettings();

        $response = $this->actingAs($admin)->put('/admin/odoo', [
            'hero_badge' => 'Official Gold Partner',
            'hero_country' => 'United Arab Emirates & GCC',
            'hero_title' => 'Transform Your UAE Operations with Certified Odoo ERP',
            'hero_subtitle' => 'Comprehensive enterprise resource planning fully localized for UAE FTA VAT and MOHRE WPS compliance.',
            'contact_phone' => '+971 50 999 8888',
            'contact_email' => 'odoo@upstartai.ae',
            'trust_items_input' => "FTA 5% VAT Certified\nCentral Bank WPS SIF\nArabic-English ERP",
            'focus_keyword' => 'odoo gold partner uae',
            'meta_title' => 'Odoo Gold Partner UAE | UpstartAI Dubai',
            'meta_description' => 'Certified Gold Partner delivering Odoo ERP solutions across Dubai and Abu Dhabi.',
            'meta_keywords' => 'odoo partner, odoo dubai',
            'seo_score' => 98,
            'readability_score' => 92,
            'is_indexable' => 1,
        ]);

        $response->assertRedirect(route('admin.odoo.edit'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('odoo_pages', [
            'hero_badge' => 'Official Gold Partner',
            'contact_phone' => '+971 50 999 8888',
            'focus_keyword' => 'odoo gold partner uae',
            'seo_score' => 98,
        ]);

        // Verify the frontend /odoo page reflects the dynamic updates immediately
        $frontendResponse = $this->get('/odoo');
        $frontendResponse->assertStatus(200);
        $frontendResponse->assertSee('Official Gold Partner');
        $frontendResponse->assertSee('+971 50 999 8888');
        $frontendResponse->assertSee('Odoo Gold Partner UAE | UpstartAI Dubai');
    }

    public function test_guest_contact_submission_creates_inquiry_record_in_database(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Khalid Al Zaabi',
            'email' => 'khalid@alzaabi-holdings.ae',
            'phone' => '+971 52 333 4444',
            'company' => 'Al Zaabi Holdings Abu Dhabi',
            'subject' => 'Corporate AI & ERP Consultation',
            'message' => 'We need an enterprise assessment for 200 staff in Abu Dhabi.',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inquiries', [
            'name' => 'Khalid Al Zaabi',
            'email' => 'khalid@alzaabi-holdings.ae',
            'company' => 'Al Zaabi Holdings Abu Dhabi',
            'is_read' => false,
        ]);
    }

    public function test_guest_odoo_demo_request_creates_odoo_demo_inquiry(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Fatima Al Marzooqi',
            'email' => 'fatima@dubaitrading.ae',
            'phone' => '+971 50 777 6655',
            'company' => 'Dubai Trading LLC',
            'subject' => 'Live Odoo ERP Walkthrough Request',
            'message' => 'Accounting & VAT module demo request.',
            'type' => 'odoo_demo',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inquiries', [
            'name' => 'Fatima Al Marzooqi',
            'email' => 'fatima@dubaitrading.ae',
            'type' => 'odoo_demo',
            'is_read' => false,
        ]);
    }

    public function test_admin_can_view_inquiries_and_mark_as_read_and_delete(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $inquiry = Inquiry::create([
            'name' => 'Saeed Mansour',
            'email' => 'saeed@mansour.ae',
            'phone' => '+971 55 123 9999',
            'company' => 'Mansour Group',
            'subject' => 'HRMS Implementation Demo',
            'message' => 'Requesting proposal for HRMS and WPS payroll.',
            'type' => 'demo',
            'is_read' => false,
        ]);

        // 1. Admin can view list
        $response = $this->actingAs($admin)->get('/admin/inquiries');
        $response->assertStatus(200);
        $response->assertSee('Saeed Mansour');
        $response->assertSee('Mansour Group');

        // 2. Admin viewing detail marks inquiry as read
        $detailResponse = $this->actingAs($admin)->get("/admin/inquiries/{$inquiry->id}");
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Mansour Group');

        $this->assertTrue($inquiry->fresh()->is_read);

        // 3. Admin can delete inquiry
        $deleteResponse = $this->actingAs($admin)->delete("/admin/inquiries/{$inquiry->id}");
        $deleteResponse->assertRedirect(route('admin.inquiries.index'));

        $this->assertDatabaseMissing('inquiries', [
            'id' => $inquiry->id,
        ]);
    }

    public function test_admin_dashboard_shows_odoo_card_and_inquiries(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        Inquiry::create([
            'name' => 'Rashid Al Nuaimi',
            'email' => 'rashid@nuaimi.ae',
            'subject' => 'Odoo Freezone Warehousing Demo',
            'type' => 'odoo_demo',
            'is_read' => false,
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Leads & Inquiries', false);
        $response->assertSee('Rashid Al Nuaimi');
    }
}
