<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionHistoryController extends Controller
{
    /**
     * LIST SEMUA TRANSAKSI
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with('user')
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhere('transaction_code', 'like', '%' . $request->search . '%');
            })
            ->when($request->status, fn ($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->payment_status, fn ($q) =>
                $q->where('payment_status', $request->payment_status)
            )
            ->latest()
            ->get();

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * DETAIL TRANSAKSI
     */
    public function show(Transaction $transaction)
    {
        $transaction->load([
            'user',
            'items.product',
            'returnItem'
        ]);

        return view('admin.transactions.show', compact('transaction'));
    }
}
