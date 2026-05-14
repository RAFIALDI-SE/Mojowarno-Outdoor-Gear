<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AutoCancelTransactions extends Command
{
    protected $signature = 'transactions:auto-cancel';
    protected $description = 'Auto cancel transaksi 24 jam setelah pickup & restock produk';

    public function handle()
    {
        $this->info('Waktu sekarang: ' . now());

        $transactions = Transaction::with('items.product')
            ->where('status', 'pending')
            ->where('payment_status', 'unpaid')
            ->where('pickup_datetime', '<=', now()->subMinutes(1))
            ->get();

        if ($transactions->isEmpty()) {
            $this->info('Tidak ada transaksi untuk dicancel.');
            return;
        }

        foreach ($transactions as $trx) {

            $this->info('Cancel transaksi ID: ' . $trx->id);

            DB::transaction(function () use ($trx) {

                foreach ($trx->items as $item) {
                    $item->product->increment('stock', $item->qty);
                }

                $trx->update([
                    'status' => 'cancelled'
                ]);
            });
        }

        $this->info('Auto cancel berhasil.');
    }
}