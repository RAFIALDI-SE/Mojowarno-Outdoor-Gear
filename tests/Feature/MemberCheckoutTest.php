<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\TermsCondition;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MemberCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = User::factory()->create(['role' => 'member']);
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com'
        ]);

        TermsCondition::create(['image' => 'terms.jpg']);


        Mail::fake();
    }

    /** @test */
    public function member_can_checkout_successfully_and_stock_is_decremented()
    {
        $product = Product::factory()->create([
            'stock' => 10,
            'price_per_day' => 50000
        ]);

        $cart = Cart::create(['user_id' => $this->member->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'qty' => 2,
            'price_per_day' => 50000
        ]);

        $response = $this->actingAs($this->member)->post(route('checkout.store'), [
            'pickup_date' => now()->addDay()->format('Y-m-d'),
            'return_date' => now()->addDays(3)->format('Y-m-d'),
        ]);

        // cek stok
        $this->assertEquals(8, $product->fresh()->stock);


        $this->assertTrue(true);

        $response->assertRedirect();
    }

    /** @test */
    public function member_can_view_their_own_qr_code_ticket()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->member->id
        ]);

        \App\Models\QrCode::create([
            'transaction_id' => $transaction->id,
            'code' => 'QR-TEST-123'
        ]);

        $response = $this->actingAs($this->member)
            ->get(route('transactions.show', $transaction->id));

        $response->assertStatus(200);
        $response->assertSee('QR-TEST-123');
    }

    /** @test */
    public function checkout_fails_if_stock_is_insufficient_at_last_moment()
    {
        $product = Product::factory()->create(['stock' => 1]);

        $cart = Cart::create(['user_id' => $this->member->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'qty' => 5,
            'price_per_day' => 50000
        ]);

        $response = $this->actingAs($this->member)->post(route('checkout.store'), [
            'pickup_date' => now()->format('Y-m-d'),
            'return_date' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, $product->fresh()->stock);
    }

    /** @test */
    public function member_cannot_view_others_qr_code_ticket()
    {
        $otherUser = User::factory()->create();

        $transaction = Transaction::factory()->create([
            'user_id' => $otherUser->id
        ]);

        $response = $this->actingAs($this->member)
            ->get(route('transactions.show', $transaction->id));

        $response->assertStatus(403);
    }
}