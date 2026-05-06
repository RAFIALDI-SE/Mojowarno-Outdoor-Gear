<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    // DETAIL TRANSAKSI + QR
    public function show(Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);


        $transaction->load(['items.product.images', 'qrCode']);
        $terms = \App\Models\TermsCondition::all();

        return view('member.transactions.show', compact('transaction', 'terms'));
    }
}

