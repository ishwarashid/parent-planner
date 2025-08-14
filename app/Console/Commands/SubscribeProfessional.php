<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SubscribeProfessional extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribe:professional {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually subscribe a professional user to a fake plan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->where('role', 'professional')->first();

        if (!$user) {
            $this->error("No professional user found with email: {$email}");
            return 1;
        }

        if ($user->subscribed('default')) {
            $this->info("User {$email} is already subscribed.");
            return 0;
        }

        // Create a fake subscription
        $user->subscriptions()->create([
            'type' => 'default',
            'name' => 'default',
            'stripe_id' => 'fake_sub_' . uniqid(),
            'stripe_status' => 'active',
            'stripe_price' => 'fake_price_id',
            'quantity' => 1,
            'trial_ends_at' => null,
            'ends_at' => null,
        ]);

        // Create a fake subscription item
        $subscription = $user->subscription('default');
        $subscription->items()->create([
            'stripe_id' => 'fake_si_' . uniqid(),
            'stripe_product' => 'fake_prod_id',
            'stripe_price' => 'fake_price_id',
            'quantity' => 1,
        ]);

        $this->info("Successfully subscribed {$email} to a fake plan.");
        return 0;
    }
}
