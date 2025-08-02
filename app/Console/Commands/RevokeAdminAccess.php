<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class RevokeAdminAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:revoke-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revoke admin access from a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->is_admin = false;
            $user->save();
            $this->info("Admin access revoked from {$email}");
        } else {
            $this->error("User with email {$email} not found.");
        }
    }
}
