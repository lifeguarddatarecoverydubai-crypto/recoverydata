<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OdooPage;
use Illuminate\View\View;

class OdooController extends Controller
{
    /**
     * Display the official Odoo UAE Partner landing page.
     */
    public function index(): View
    {
        $odoo = OdooPage::getSettings();
        $modules = $odoo->modules ?? [];
        $services = $odoo->services ?? [];

        return view('frontend.odoo.index', compact('odoo', 'modules', 'services'));
    }
}
