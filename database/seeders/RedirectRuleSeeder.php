<?php

namespace Database\Seeders;

use App\Models\RedirectRule;
use Illuminate\Database\Seeder;

class RedirectRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RedirectRule::seedDefaults();
    }
}
