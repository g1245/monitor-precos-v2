<?php

namespace Tests\Feature;

use App\Models\PriceAlert;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceAlertTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authenticated user can create price alert.
     */
    public function test_authenticated_user_can_create_price_alert(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id, 'price' => 100.00]);

        $response = $this->actingAs($user)->postJson('/api/price-alerts', [
            'product_id' => $product->id,
            'target_price' => 80.00,
        ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('price_alerts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => 80.00,
            'is_active' => true,
        ]);
    }

    /**
     * Test user can create price alert without target price.
     */
    public function test_user_can_create_alert_without_target_price(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        $response = $this->actingAs($user)->postJson('/api/price-alerts', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('price_alerts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => null,
            'is_active' => true,
        ]);
    }

    /**
     * Test guest cannot create price alert.
     */
    public function test_guest_cannot_create_price_alert(): void
    {
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        $response = $this->postJson('/api/price-alerts', [
            'product_id' => $product->id,
            'target_price' => 80.00,
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test updating existing price alert.
     */
    public function test_updating_existing_price_alert(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        PriceAlert::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => 80.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->postJson('/api/price-alerts', [
            'product_id' => $product->id,
            'target_price' => 70.00,
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('price_alerts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => 70.00,
            'is_active' => true,
        ]);

        // Verify only one alert exists
        $this->assertEquals(1, PriceAlert::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->count());
    }

    /**
     * Test user can deactivate price alert.
     */
    public function test_user_can_deactivate_price_alert(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        PriceAlert::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => 80.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/price-alerts/{$product->id}");

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('price_alerts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test alert should trigger logic.
     */
    public function test_alert_should_trigger_when_price_below_target(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id, 'price' => 100.00]);

        $alert = PriceAlert::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => 90.00,
            'is_active' => true,
        ]);

        $this->assertFalse($alert->shouldTrigger(100.00));
        $this->assertTrue($alert->shouldTrigger(90.00));
        $this->assertTrue($alert->shouldTrigger(80.00));
    }

    /**
     * Test user can view price alerts page.
     */
    public function test_user_can_view_price_alerts_page(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        PriceAlert::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => 80.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('account.price-alerts'));

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }
}
