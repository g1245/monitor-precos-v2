<?php

namespace App\Services;

use App\Models\Product;
use App\Models\UserWishProduct;
use App\Models\PriceAlertLog;
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
    public static function sendPriceAlerts(Product $product): void
    {
        $alerts = $product->activePriceAlerts;

        foreach ($alerts as $alert) {

            if ($alert->shouldTrigger($product->price) && self::canSendNotification($alert)) {
                try {
                    $alert->user->notify(new PriceAlertNotification($product, $alert));

                    // Create log entry
                    PriceAlertLog::create([
                        'user_id' => $alert->user_id,
                        'product_id' => $product->id,
                        'user_wish_product_id' => $alert->id,
                        'price_at_notification' => $product->price,
                        'target_price' => $alert->target_price,
                        'notified_at' => now(),
                    ]);

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
    public static function sendPriceAlert(UserWishProduct $wish): void
    {
        if (!self::canSendNotification($wish)) {
            return;
        }

        try {
            $wish->user->notify(new PriceAlertNotification($wish->product, $wish));

            // Create log entry
            PriceAlertLog::create([
                'user_id' => $wish->user_id,
                'product_id' => $wish->product_id,
                'user_wish_product_id' => $wish->id,
                'price_at_notification' => $wish->product->price,
                'target_price' => $wish->target_price,
                'notified_at' => now(),
            ]);

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

    /**
     * Check if a notification can be sent for this wish (throttle to once per hour).
     *
     * @param UserWishProduct $wish
     * @return bool
     */
    private static function canSendNotification(UserWishProduct $wish): bool
    {
        if (!$wish->last_notified_at) {
            return true;
        }

        return now()->diffInHours($wish->last_notified_at) >= 1;
    }
}
