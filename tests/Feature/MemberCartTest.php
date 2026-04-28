<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\TermsCondition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberCartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = User::factory()->create(['role' => 'member']);

        // Anti-error 500: Setup variabel layout
        // TermsCondition::create(['image' => 'terms.jpg']);
    }

    /** @test */
    public function member_can_add_product_to_cart()
    {
        $product = Product::factory()->create(['stock' => 10, 'price_per_day' => 50000]);

        $response = $this->actingAs($this->member)->post(route('cart.add', $product->id));

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'qty' => 1
        ]);
    }

    /** @test */
    public function adding_same_product_increments_quantity()
    {
        $product = Product::factory()->create(['stock' => 10]);
        $cart = Cart::create(['user_id' => $this->member->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'qty' => 1,
            'price_per_day' => $product->price_per_day
        ]);

        // Tambah lagi produk yang sama
        $this->actingAs($this->member)->post(route('cart.add', $product->id));

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'qty' => 2
        ]);
    }

    /** @test */
    public function cannot_add_to_cart_if_stock_insufficient()
    {
        $product = Product::factory()->create(['stock' => 1]);
        $cart = Cart::create(['user_id' => $this->member->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'qty' => 1,
            'price_per_day' => $product->price_per_day
        ]);

        // tambah lagi padahal stok cuma 1 dan sudah ada 1 di cart
        $response = $this->actingAs($this->member)->post(route('cart.add', $product->id));

        $response->assertSessionHas('error', 'Stok tidak mencukupi');
        $this->assertEquals(1, CartItem::where('product_id', $product->id)->first()->qty);
    }

   /** @test */
   public function member_can_update_cart_quantity()
   {
       $product = Product::factory()->create(['stock' => 10]);
       $cart = Cart::create(['user_id' => $this->member->id]);
       $item = CartItem::create([
           'cart_id' => $cart->id,
           'product_id' => $product->id,
           'qty' => 1,
           'price_per_day' => $product->price_per_day
       ]);

       $item->refresh();


       $response = $this->actingAs($this->member)->put(route('cart.update', $item->id), [
           'qty' => 5
       ]);

       $response->assertStatus(302);
       $this->assertDatabaseHas('cart_items', [
           'id' => $item->id,
           'qty' => 5
       ]);
   }

   /** @test */
   public function member_can_remove_item_from_cart()
   {
       $cart = Cart::create(['user_id' => $this->member->id]);
       $item = CartItem::create([
           'cart_id' => $cart->id,
           'product_id' => Product::factory()->create()->id,
           'qty' => 1,
           'price_per_day' => 10000
       ]);

       $item->refresh();


       $response = $this->actingAs($this->member)->delete(route('cart.remove', $item->id));

       $response->assertStatus(302);
       $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
   }
}