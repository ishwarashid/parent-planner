<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Laravel\Paddle\Cashier;

class SyncPaddleSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-paddle-subscriptions {user? : The ID of the user to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user subscriptions from Paddle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user');
        
        if ($userId) {
            $users = User::where('id', $userId)->get();
        } else {
            $users = User::all();
        }
        
        foreach ($users as $user) {
            $this->info("Syncing subscriptions for user: {$user->id} ({$user->email})");
            
            try {
                // Get customer info from Paddle
                $customer = $user->customer;
                if (!$customer) {
                    $this->warn("No customer found for user {$user->id}");
                    continue;
                }
                
                // Get subscriptions from Paddle
                $response = Cashier::api('GET', "subscriptions?customer_id={$customer->paddle_id}");
                $subscriptions = $response->json()['data'] ?? [];
                
                $this->info("Found " . count($subscriptions) . " subscriptions for user {$user->id}");
                
                foreach ($subscriptions as $paddleSubscription) {
                    // Check if subscription exists in our database
                    $existingSubscription = $user->subscriptions()
                        ->where('paddle_id', $paddleSubscription['id'])
                        ->first();
                    
                    if ($existingSubscription) {
                        // Update existing subscription
                        $existingSubscription->update([
                            'status' => $paddleSubscription['status'],
                            'trial_ends_at' => $paddleSubscription['status'] === 'trialing' 
                                ? $paddleSubscription['next_billed_at'] 
                                : null,
                            'paused_at' => $paddleSubscription['paused_at'] ?? null,
                            'ends_at' => $paddleSubscription['canceled_at'] ?? null,
                        ]);
                        
                        $this->info("Updated subscription: {$paddleSubscription['id']}");
                    } else {
                        // Create new subscription
                        $subscription = $user->subscriptions()->create([
                            'type' => $paddleSubscription['custom_data']['subscription_type'] ?? 'default',
                            'paddle_id' => $paddleSubscription['id'],
                            'status' => $paddleSubscription['status'],
                            'trial_ends_at' => $paddleSubscription['status'] === 'trialing' 
                                ? $paddleSubscription['next_billed_at'] 
                                : null,
                            'paused_at' => $paddleSubscription['paused_at'] ?? null,
                            'ends_at' => $paddleSubscription['canceled_at'] ?? null,
                        ]);
                        
                        // Create subscription items
                        foreach ($paddleSubscription['items'] as $item) {
                            $subscription->items()->create([
                                'product_id' => $item['price']['product_id'],
                                'price_id' => $item['price']['id'],
                                'status' => $item['status'],
                                'quantity' => $item['quantity'] ?? 1,
                            ]);
                        }
                        
                        $this->info("Created subscription: {$paddleSubscription['id']}");
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error syncing user {$user->id}: " . $e->getMessage());
            }
        }
        
        $this->info('Subscription sync completed!');
    }
}
