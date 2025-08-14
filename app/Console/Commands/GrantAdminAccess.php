<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GrantAdminAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:grant-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant admin access to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->is_admin = true;
            $user->save();
            $this->info("Admin access granted to {$email}");
        } else {
            $this->error("User with email {$email} not found.");
        }
    }
}