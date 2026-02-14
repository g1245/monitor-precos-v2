<?php

namespace App\Services;

use App\Models\Product;
use App\Models\UserWishProduct;
use App\Notifications\PriceAlertNotification;
use Illuminate\Support\Facades\Log;

class PriceAlertService
{
    /**
     * Send price alert notifications for a product to all eligible users.
     *
     * @param Product $product
     * @return void
     */
    public function sendPriceAlerts(Product $product): void
    {
        $alerts = $product->activePriceAlerts;

        foreach ($alerts as $alert) {
            if ($alert->shouldTrigger($product->price)) {
                try {
                    $alert->user->notify(new PriceAlertNotification($product, $alert));

                    // Update last notified timestamp
                    $alert->update(['last_notified_at' => now()]);

                    Log::info('Price alert sent', [
                        'user_id' => $alert->user_id,
                        'product_id' => $product->id,
                        'current_price' => $product->price,
                        'target_price' => $alert->target_price,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send price alert', [
                        'user_id' => $alert->user_id,
                        'product_id' => $product->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    /**
     * Send price alert for a specific user wish.
     *
     * @param UserWishProduct $wish
     * @return void
     */
    public function sendPriceAlert(UserWishProduct $wish): void
    {
        try {
            $wish->user->notify(new PriceAlertNotification($wish->product, $wish));

            // Update last notified timestamp
            $wish->update(['last_notified_at' => now()]);

            Log::info('Price alert sent', [
                'user_id' => $wish->user_id,
                'product_id' => $wish->product_id,
                'current_price' => $wish->product->price,
                'target_price' => $wish->target_price,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send price alert', [
                'user_id' => $wish->user_id,
                'product_id' => $wish->product_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
