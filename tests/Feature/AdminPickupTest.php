<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPickupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_see_pending_pickups()
    {
        Transaction::factory()->count(2)->create(['status' => 'pending']);
        Transaction::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->admin)->get(route('admin.pickups'));

        $response->assertStatus(200);
        $this->assertCount(2, $response->viewData('transactions'));
    }

    /** @test */
    public function admin_can_confirm_pickup_successfully_without_changing_stock()
    {
        // 1. SETUP: Pastikan subtotal dihitung
        $price = 50000;
        $qty = 3;
        $product = Product::factory()->create(['stock' => 7, 'price_per_day' => $price]);
        $transaction = Transaction::factory()->create(['status' => 'pending']);

        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'product_id'     => $product->id,
            'qty'            => $qty,
            'price_per_day'  => $price,
            'subtotal'       => $price * $qty
        ]);

        // 2. ACTION
        $response = $this->actingAs($this->admin)->post(route('admin.pickups.confirm', $transaction), [
            'payment_status' => 'paid'
        ]);

        // 3. ASSERTION
        $response->assertRedirect(route('admin.pickups'));

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'active',
            'payment_status' => 'paid'
        ]);


        $this->assertEquals(7, $product->fresh()->stock);
    }

    /** @test */
    public function pickup_fails_if_product_stock_is_insufficient()
    {
        // 1. SETUP
        $price = 50000;
        $qty = 5;
        $product = Product::factory()->create(['stock' => 1, 'price_per_day' => $price]);
        $transaction = Transaction::factory()->create(['status' => 'pending']);

        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'product_id'     => $product->id,
            'qty'            => $qty,
            'price_per_day'  => $price,
            'subtotal'       => $price * $qty
        ]);

        // 2. ACTION
        $response = $this->actingAs($this->admin)->post(route('admin.pickups.confirm', $transaction), [
            'payment_status' => 'paid'
        ]);

        // 3. ASSERTION
        $response->assertStatus(400);
        $this->assertEquals('pending', $transaction->fresh()->status);
    }


    /** @test */
public function admin_can_see_active_returns()
{
    Transaction::factory()->count(2)->create(['status' => 'active']);
    Transaction::factory()->create(['status' => 'pending']);

    $response = $this->actingAs($this->admin)
        ->get(route('admin.returns'));

    $response->assertStatus(200);

    // Harus cuma tampil yang active
    $this->assertCount(2, $response->viewData('transactions'));
}

/** @test */
public function admin_can_confirm_return_and_stock_is_incremented()
{
    // 1. SETUP
    $price = 50000;
    $qty = 2;

    $product = Product::factory()->create([
        'stock' => 3,
        'price_per_day' => $price
    ]);

    $transaction = Transaction::factory()->create([
        'status' => 'active',
        'payment_status' => 'paid'
    ]);

    TransactionItem::create([
        'transaction_id' => $transaction->id,
        'product_id' => $product->id,
        'qty' => $qty,
        'price_per_day' => $price,
        'subtotal' => $price * $qty
    ]);

    // 2. ACTION
    $response = $this->actingAs($this->admin)
        ->post(route('admin.returns.confirm', $transaction), [
            'condition' => 'aman',
            'fine' => 0,
            'notes' => 'Barang lengkap'
        ]);

    // 3. ASSERTION
    $response->assertRedirect(route('admin.returns'));

    // Status transaksi berubah
    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
        'status' => 'returned'
    ]);

    // Return item tercatat
    $this->assertDatabaseHas('return_items', [
        'transaction_id' => $transaction->id,
        'condition' => 'aman',
        'fine' => 0,
        'notes' => 'Barang lengkap'
    ]);

    // Stok bertambah kembali
    $this->assertEquals(5, $product->fresh()->stock);
}

/** @test */
public function return_fails_if_transaction_already_returned()
{
    // 1. SETUP
    $transaction = Transaction::factory()->create([
        'status' => 'returned'
    ]);

    // 2. ACTION
    $response = $this->actingAs($this->admin)
        ->post(route('admin.returns.confirm', $transaction), [
            'condition' => 'aman',
            'fine' => 0,
            'notes' => 'Tes double return'
        ]);

    // 3. ASSERTION
    $response->assertStatus(400);
}

/** @test */
public function admin_can_mark_half_payment_as_paid()
{
    // 1. SETUP
    $transaction = Transaction::factory()->create([
        'status' => 'active',
        'payment_status' => 'half'
    ]);

    // 2. ACTION
    $response = $this->actingAs($this->admin)
        ->post(route('admin.returns.pay', $transaction));

    // 3. ASSERTION
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
        'payment_status' => 'paid'
    ]);
}

/** @test */
public function mark_as_paid_fails_if_transaction_already_paid()
{
    // 1. SETUP
    $transaction = Transaction::factory()->create([
        'payment_status' => 'paid'
    ]);

    // 2. ACTION
    $response = $this->actingAs($this->admin)
        ->post(route('admin.returns.pay', $transaction));

    // 3. ASSERTION
    $response->assertSessionHas('error');

    $this->assertEquals(
        'paid',
        $transaction->fresh()->payment_status
    );
}
}