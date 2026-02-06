<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Models\UserWishProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserWishProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authenticated user can add product to wishlist without price alert.
     */
    public function test_user_can_add_product_to_wishlist_without_alert(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id, 'price' => 100.00]);

        $response = $this->actingAs($user)->postJson('/wish-products', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(['wished' => true, 'has_alert' => false]);
        
        $this->assertDatabaseHas('users_wish_products', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => null,
        ]);
    }

    /**
     * Test user can add product with price alert (target price).
     */
    public function test_user_can_add_product_with_price_alert(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id, 'price' => 100.00]);

        $response = $this->actingAs($user)->postJson('/wish-products', [
            'product_id' => $product->id,
            'target_price' => 80.00,
        ]);

        $response->assertStatus(201);
        $response->assertJson(['wished' => true, 'has_alert' => true]);
        
        $this->assertDatabaseHas('users_wish_products', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => 80.00,
            'is_active' => true,
        ]);
    }

    /**
     * Test updating existing wish with price alert.
     */
    public function test_updating_existing_wish_with_price_alert(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        // First, add to wishlist without alert
        UserWishProduct::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => null,
        ]);

        // Then update with price alert
        $response = $this->actingAs($user)->postJson('/wish-products', [
            'product_id' => $product->id,
            'target_price' => 70.00,
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('users_wish_products', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => 70.00,
        ]);

        // Verify only one record exists
        $this->assertEquals(1, UserWishProduct::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->count());
    }

    /**
     * Test user can remove product from wishlist.
     */
    public function test_user_can_remove_product_from_wishlist(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        UserWishProduct::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/wish-products/{$product->id}");

        $response->assertStatus(200);
        $response->assertJson(['wished' => false]);
        
        $this->assertDatabaseMissing('users_wish_products', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    /**
     * Test checking wish status.
     */
    public function test_check_wish_status(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        UserWishProduct::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => 50.00,
        ]);

        $response = $this->actingAs($user)->getJson("/wish-products/{$product->id}/check");

        $response->assertStatus(200);
        $response->assertJson([
            'wished' => true,
            'has_alert' => true,
        ]);
    }

    /**
     * Test guest cannot add to wishlist.
     */
    public function test_guest_cannot_add_to_wishlist(): void
    {
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        $response = $this->postJson('/wish-products', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test shouldTrigger method with different scenarios.
     */
    public function test_should_trigger_alert_logic(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product1 = Product::factory()->create(['store_id' => $store->id, 'price' => 100.00]);
        $product2 = Product::factory()->create(['store_id' => $store->id, 'price' => 100.00]);

        // Wish with target price
        $wishWithAlert = UserWishProduct::create([
            'user_id' => $user->id,
            'product_id' => $product1->id,
            'target_price' => 90.00,
            'is_active' => true,
        ]);

        $this->assertFalse($wishWithAlert->shouldTrigger(100.00));
        $this->assertTrue($wishWithAlert->shouldTrigger(90.00));
        $this->assertTrue($wishWithAlert->shouldTrigger(80.00));

        // Wish without target price (alert on any change)
        $wishWithoutTarget = UserWishProduct::create([
            'user_id' => $user->id,
            'product_id' => $product2->id,
            'target_price' => null,
            'is_active' => true,
        ]);

        $this->assertTrue($wishWithoutTarget->shouldTrigger(100.00));
        $this->assertTrue($wishWithoutTarget->shouldTrigger(50.00));
    }

    /**
     * Test user relationships work correctly.
     */
    public function test_user_relationships(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product1 = Product::factory()->create(['store_id' => $store->id]);
        $product2 = Product::factory()->create(['store_id' => $store->id]);

        UserWishProduct::create([
            'user_id' => $user->id,
            'product_id' => $product1->id,
        ]);

        UserWishProduct::create([
            'user_id' => $user->id,
            'product_id' => $product2->id,
            'target_price' => 50.00,
        ]);

        $this->assertTrue($user->hasWishProduct($product1->id));
        $this->assertTrue($user->hasWishProduct($product2->id));
        $this->assertFalse($user->hasPriceAlert($product1->id));
        $this->assertTrue($user->hasPriceAlert($product2->id));
    }

    /**
     * Test updating only price alert for existing wish.
     */
    public function test_update_price_alert_for_existing_wish(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        $wish = UserWishProduct::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'target_price' => null,
        ]);

        $response = $this->actingAs($user)->patchJson("/wish-products/{$product->id}/price-alert", [
            'target_price' => 75.00,
        ]);

        $response->assertStatus(200);
        
        $wish->refresh();
        $this->assertEquals(75.00, $wish->target_price);
        $this->assertTrue($wish->is_active);
    }
}
