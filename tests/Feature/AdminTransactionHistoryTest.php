<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTransactionHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_see_all_transactions_list()
    {
        Transaction::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.transactions'));

        $response->assertStatus(200);
        $response->assertViewHas('transactions');
        $this->assertCount(5, $response->viewData('transactions'));
    }

    /** @test */
    public function admin_can_search_transaction_by_code()
    {
        Transaction::factory()->create(['transaction_code' => 'TRX-999']);
        Transaction::factory()->create(['transaction_code' => 'TRX-111']);

        // mencari yang kodenya 999
        $response = $this->actingAs($this->admin)->get(route('admin.transactions', ['search' => '999']));

        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('transactions'));
        $this->assertEquals('TRX-999', $response->viewData('transactions')->first()->transaction_code);
    }

    /** @test */
    public function admin_can_filter_transaction_by_status()
    {
        Transaction::factory()->count(3)->create(['status' => 'active']);
        Transaction::factory()->count(2)->create(['status' => 'pending']);

        // Filter status completed
        $response = $this->actingAs($this->admin)->get(route('admin.transactions', ['status' => 'active']));

        $this->assertCount(3, $response->viewData('transactions'));
    }

    /** @test */
    public function admin_can_see_transaction_detail_with_items()
    {
        $transaction = Transaction::factory()->create();
        $product = Product::factory()->create();

        // Buat item transaksi (memastikan field subtotal & price_per_day ada sesuai migrasi lu)
        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'qty' => 1,
            'price_per_day' => $product->price_per_day,
            'subtotal' => $product->price_per_day
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.transactions.show', $transaction));

        $response->assertStatus(200);
        $response->assertViewHas('transaction');
        // memastikan relasi items ikut ke-load
        $this->assertTrue($response->viewData('transaction')->relationLoaded('items'));
    }
}