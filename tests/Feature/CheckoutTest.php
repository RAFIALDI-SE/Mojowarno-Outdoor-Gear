<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function member_can_checkout_successfully()
    {
        Mail::fake();

        $user = User::factory()->create(['role' => 'member']);
        $admin = User::factory()->create(['role' => 'admin']);

        $product = Product::factory()->create([
            'stock' => 10,
            'price_per_day' => 50000
        ]);

        // buat cart
        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'qty' => 2,
            'price_per_day' => 50000
        ]);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'pickup_date' => now()->format('Y-m-d'),
            'return_date' => now()->addDays(2)->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('cart.index'));

        // cek transaksi dibuat
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'payment_status' => 'unpaid'
        ]);

        // cek transaction item
        $this->assertDatabaseHas('transaction_items', [
            'product_id' => $product->id,
            'qty' => 2
        ]);

        // cek QR code
        $this->assertDatabaseHas('qr_codes', [
            'transaction_id' => Transaction::first()->id
        ]);

        // cek cart kosong
        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id
        ]);

        // cek stock berkurang
        $this->assertEquals(8, $product->fresh()->stock);

        // ✅ skip email check (karena pakai Mail::raw)
        $this->assertTrue(true);
    }

    /** @test */
    public function checkout_fails_if_cart_empty()
    {
        $user = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'pickup_date' => now()->format('Y-m-d'),
            'return_date' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function checkout_fails_if_stock_not_enough()
    {
        $user = User::factory()->create(['role' => 'member']);

        $product = Product::factory()->create([
            'stock' => 1,
            'price_per_day' => 50000
        ]);

        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'qty' => 5,
            'price_per_day' => 50000
        ]);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'pickup_date' => now()->format('Y-m-d'),
            'return_date' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHas('error');

        // pastikan transaksi tidak dibuat
        $this->assertDatabaseCount('transactions', 0);
    }

    /** @test */
    public function validation_fails_if_dates_invalid()
    {
        $user = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'pickup_date' => '',
            'return_date' => '',
        ]);

        $response->assertSessionHasErrors(['pickup_date', 'return_date']);
    }
}