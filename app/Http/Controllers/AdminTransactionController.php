<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\QrCode;
use App\Models\ReturnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminTransactionController extends Controller
{
    /* =======================
    | PICKUP
    ======================= */
    public function pickupIndex(Request $request)
    {
        $transactions = Transaction::with('user')
            ->where('status', 'pending')
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$request->search}%")
                );
            })
            ->latest()
            ->get();

        return view('admin.pickups.index', compact('transactions'));
    }

    public function pickupShow(Transaction $transaction)
    {
        $transaction->load(['user','items.product','qrCode']);
        return view('admin.pickups.show', compact('transaction'));
    }

    public function pickupConfirm(Request $request, Transaction $transaction)
    {
        DB::transaction(function () use ($request, $transaction) {

            // CEK STOK DULU
            foreach ($transaction->items as $item) {
                if ($item->product->stock < $item->qty) {
                    abort(400, 'Stok produk tidak mencukupi: ' . $item->product->name);
                }
            }

            // KURANGI STOK
            // foreach ($transaction->items as $item) {
            //     $item->product->decrement('stock', $item->qty);
            // }

            // UPDATE TRANSAKSI
            $transaction->update([
                'payment_status' => $request->payment_status,
                'status' => 'active'
            ]);
        });

        return redirect()->route('admin.pickups')
            ->with('success','Pengambilan berhasil divalidasi & stok dikurangi');
    }


    /* =======================
    | RETURN
    ======================= */
    public function returnIndex(Request $request)
    {
        $transactions = Transaction::with('user')
            ->where('status', 'active')
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$request->search}%")
                );
            })
            ->latest()
            ->get();

        return view('admin.returns.index', compact('transactions'));
    }

    public function returnShow(Transaction $transaction)
    {
        $transaction->load(['user','items.product']);
        return view('admin.returns.show', compact('transaction'));
    }

    public function returnConfirm(Request $request, Transaction $transaction)
    {
        DB::transaction(function () use ($request, $transaction) {


            if ($transaction->status !== 'active') {
                abort(400, 'Transaksi sudah diproses');
            }


            foreach ($transaction->items as $item) {
                $item->product->increment('stock', $item->qty);
            }


            ReturnItem::create([
                'transaction_id' => $transaction->id,
                'condition' => $request->condition,
                'fine' => $request->fine ?? 0,
                'notes' => $request->notes,
                'returned_at' => now()
            ]);


            $transaction->update([
                'status' => 'returned'
            ]);
        });

        return redirect()->route('admin.returns')
            ->with('success','Pengembalian berhasil & stok dikembalikan');
    }


    public function markAsPaid(Transaction $transaction)
    {
        if ($transaction->payment_status !== 'half') {
            return back()->with('error', 'Transaksi tidak perlu pelunasan');
        }

        $transaction->update([
            'payment_status' => 'paid'
        ]);

        return back()->with('success', 'Pembayaran berhasil dilunasi');
    }


    /* =======================
    | QR SCAN
    ======================= */
    public function scanQr($code)
    {
        $qr = QrCode::where('code', $code)->firstOrFail();
        $transaction = $qr->transaction;

        if ($transaction->status === 'pending') {
            return redirect()->route('admin.pickups.show', $transaction);
        }

        if ($transaction->status === 'active') {
            return redirect()->route('admin.returns.show', $transaction);
        }

        abort(404);
    }
}
