<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\SavedProduct;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authenticated user can save a product.
     */
    public function test_authenticated_user_can_save_product(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        $response = $this->actingAs($user)->postJson('/api/saved-products', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['saved' => true]);
        
        $this->assertDatabaseHas('saved_products', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    /**
     * Test guest cannot save a product.
     */
    public function test_guest_cannot_save_product(): void
    {
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        $response = $this->postJson('/api/saved-products', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test user cannot save same product twice.
     */
    public function test_user_cannot_save_same_product_twice(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        SavedProduct::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->postJson('/api/saved-products', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['saved' => true]);
        
        // Verify only one record exists
        $this->assertEquals(1, SavedProduct::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->count());
    }

    /**
     * Test user can remove saved product.
     */
    public function test_user_can_remove_saved_product(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        SavedProduct::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/saved-products/{$product->id}");

        $response->assertStatus(200);
        $response->assertJson(['saved' => false]);
        
        $this->assertDatabaseMissing('saved_products', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    /**
     * Test user can view saved products page.
     */
    public function test_user_can_view_saved_products_page(): void
    {
        $user = User::factory()->create();
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        SavedProduct::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->get(route('account.saved-products'));

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    /**
     * Test user only sees their own saved products.
     */
    public function test_user_only_sees_their_own_saved_products(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $store = Store::factory()->create();
        $product1 = Product::factory()->create(['store_id' => $store->id, 'name' => 'Product 1']);
        $product2 = Product::factory()->create(['store_id' => $store->id, 'name' => 'Product 2']);

        SavedProduct::create(['user_id' => $user1->id, 'product_id' => $product1->id]);
        SavedProduct::create(['user_id' => $user2->id, 'product_id' => $product2->id]);

        $response = $this->actingAs($user1)->get(route('account.saved-products'));

        $response->assertStatus(200);
        $response->assertSee('Product 1');
        $response->assertDontSee('Product 2');
    }
}
