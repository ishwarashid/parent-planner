<?php

namespace App\Services;

use Laravel\Paddle\Cashier;
use Exception;

class PaddleService
{
    /**
     * Fetch prices from Paddle API
     *
     * @param array $priceIds
     * @return array
     */
    public function fetchPrices($priceIds = [])
    {   
        try {
            $query = '';
            if (!empty($priceIds)) {
                $query = '?id=' . implode(',', $priceIds);
            }
            
            $response = Cashier::api('GET', "prices{$query}");
            $prices = $response['data'] ?? [];

            $formattedPrices = [];
            foreach ($prices as $price) {
                $formattedPrices[] = [
                    'id'            => $price['id'],
                    'name'          => $price['name'] ?? $price['description'] ?? 'Subscription Plan',
                    'price'         => $this->formatPrice($price),
                    'billing_cycle' => $price['billing_cycle'] ?? null,
                    'unit_price'    => $price['unit_price'] ?? null
                ];
            }
            
            return $formattedPrices;
        } catch (Exception $e) {
            \Log::error('Error fetching prices from Paddle: ' . $e->getMessage());
            return [];
        }
    }


    /**
     * Format price for display
     *
     * @param array $price
     * @return string
     */
    private function formatPrice($price)
    {
        if (!isset($price['unit_price'])) {
            return 'Price unavailable';
        }
        
        $amount = $price['unit_price']['amount'] ?? 0;
        $currency = $price['unit_price']['currency_code'] ?? 'USD';
        
        // Convert amount from smallest currency unit (e.g., cents) to main unit (e.g., dollars)
        $convertedAmount = $amount / 100;
        
        // Return just the formatted amount (e.g., "$10.00")
        return '$' . number_format($convertedAmount, 2);
    }
}