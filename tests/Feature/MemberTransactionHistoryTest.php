<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaction;
use App\Models\TermsCondition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberTransactionHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = User::factory()->create(['role' => 'member']);

        // Setup data terms buat layout
        TermsCondition::create(['image' => 'terms.jpg']);
    }

    /** @test */
    public function member_can_see_their_own_transaction_history()
    {
        // Buat 2 transaksi buat si member
        Transaction::factory()->count(2)->create(['user_id' => $this->member->id]);

        // Buat 1 transaksi buat orang lain
        $otherUser = User::factory()->create();
        Transaction::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->member)->get(route('transactions.index'));

        $response->assertStatus(200);
        // Pastikan cuma 2 transaksi miliknya yang tampil
        $this->assertCount(2, $response->viewData('transactions'));
    }

    /** @test */
    public function member_can_access_their_transaction_detail()
    {
        $transaction = Transaction::factory()->create(['user_id' => $this->member->id]);

        $response = $this->actingAs($this->member)->get(route('transactions.show', $transaction->id));

        $response->assertStatus(200);
        $response->assertViewHas('transaction');
    }

    /** @test */
    public function member_cannot_access_others_transaction_detail()
    {
        $otherUser = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->member)->get(route('transactions.show', $transaction->id));

        // Harus 403 (Forbidden) sesuai logic abort_if lu
        $response->assertStatus(403);
    }
}