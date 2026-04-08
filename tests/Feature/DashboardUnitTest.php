<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class DashboardUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_logic_calculates_summary_correctly()
{
    $this->withoutExceptionHandling();

    // 1. Bikin member dulu
    $members = User::factory()->count(3)->create(['role' => 'member']);

    // 2. Bikin produk
    Product::factory()->count(5)->create();

    // 3. Pake salah satu member yang udah ada buat transaksi
    Transaction::factory()->create([
        'user_id' => $members->first()->id,
        'payment_status' => 'paid',
        'total_price' => 100000,
        'status' => 'active',
        'created_at' => now()
    ]);

    // 4. Skenario transaksi unpaid
    Transaction::factory()->create([
        'user_id' => $members->last()->id,
        'payment_status' => 'unpaid',
        'total_price' => 50000,
        'status' => 'pending',
        'created_at' => now()
    ]);

    // 5. bikin Admin

    $admin = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($admin)->get('/dashboard');

    $response->assertStatus(200);
    $this->assertEquals(3, $response->viewData('totalUsers'));
    $this->assertEquals(100000, $response->viewData('totalRevenue'));
}

    public function test_dashboard_filters_revenue_by_date_range()
    {

        $admin = User::factory()->create(['role' => 'admin']);

        Transaction::factory()->create([
            'payment_status' => 'paid',
            'total_price' => 200000,
            'created_at' => Carbon::now()->subMonth()
        ]);


        $responseDefault = $this->actingAs($admin)->get('/dashboard');
        $this->assertEquals(0, $responseDefault->viewData('totalRevenue'));

        $start = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $end = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');


        $responseFiltered = $this->actingAs($admin)->get("/dashboard?start_date=$start&end_date=$end");

        $this->assertEquals(200000, $responseFiltered->viewData('totalRevenue'));
    }
}