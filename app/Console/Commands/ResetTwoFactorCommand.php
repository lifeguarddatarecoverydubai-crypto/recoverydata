<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetTwoFactorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'two-factor:reset {email : The email address of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Two-Factor Authentication for a specific user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("No user found with email [{$email}].");

            return Command::FAILURE;
        }

        $user->resetTwoFactor();

        $this->info("Two-Factor Authentication has been successfully reset for [{$user->name}] ({$user->email}).");
        $this->comment('The user will be prompted to set up Google Authenticator upon their next login.');

        return Command::SUCCESS;
    }
}
