<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pickup_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:pickup_date',
        ]);

        $user = Auth::user();

        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong');
        }

        // HITUNG TOTAL HARI
        $now = now();

        $pickup = Carbon::parse($request->pickup_date)
            ->setTime($now->hour, $now->minute, $now->second);

        $return = Carbon::parse($request->return_date)
            ->setTime($now->hour, $now->minute, $now->second);
        $totalDays = $pickup->diffInDays($return);
        if ($totalDays === 0) $totalDays = 1;

        DB::beginTransaction();

        try {


            foreach ($cart->items as $item) {

                $product = \App\Models\Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if ($product->stock < $item->qty) {
                    throw new \Exception('Stok tidak cukup: ' . $product->name);
                }

                $product->decrement('stock', $item->qty);
            }
            // HITUNG TOTAL HARGA
            $totalPrice = 0;
            foreach ($cart->items as $item) {
                $totalPrice += $item->qty * $item->price_per_day * $totalDays;
            }

            // BUAT TRANSACTION
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
                'pickup_datetime' => $pickup,
                'return_datetime' => $return,
                'total_days' => $totalDays,
                'total_price' => $totalPrice,
                'payment_status' => 'unpaid',
                'status' => 'pending',
            ]);

            // PINDAHKAN CART ITEMS -> TRANSACTION ITEMS
            foreach ($cart->items as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'price_per_day' => $item->price_per_day,
                    'subtotal' => $item->qty * $item->price_per_day * $totalDays,
                ]);
            }

            // GENERATE QR CODE
            QrCode::create([
                'transaction_id' => $transaction->id,
                'code' => 'QR-' . strtoupper(Str::random(10)),
            ]);

            // KOSONGKAN CART
            $cart->items()->delete();

            DB::commit();

            $transaction->load('items.product');

            $detailProduk = "";
            foreach ($transaction->items as $item) {
                $detailProduk .=
                    "Produk: {$item->product->name}\n" .
                    "Qty: {$item->qty}\n" .
                    "Harga / hari: Rp " . number_format($item->price_per_day) . "\n" .
                    "Subtotal: Rp " . number_format($item->subtotal) . "\n\n";
            }

            // Kirim ke Admin
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                Mail::raw(
                    "Ada transaksi baru!\n\n" .
                    "Kode: {$transaction->transaction_code}\n" .
                    "Nama: {$user->name}\n" .
                    "Email: {$user->email}\n\n" .
                    "Detail Produk:\n\n" .
                    $detailProduk .
                    "Total Hari: {$transaction->total_days}\n" .
                    "Total Harga: Rp " . number_format($transaction->total_price),
                    function ($message) use ($admin) {
                        $message->to($admin->email)
                                ->subject('Pesanan Baru Masuk');
                    }
                );
            }

            // Kirim ke Pelanggan
            Mail::raw(
                "Halo {$user->name},\n\n" .
                "Checkout kamu berhasil!\n\n" .
                "Kode Transaksi: {$transaction->transaction_code}\n\n" .
                "Detail Produk:\n\n" .
                $detailProduk .
                "Total Hari: {$transaction->total_days}\n" .
                "Total Bayar: Rp " . number_format($transaction->total_price) . "\n\n" .
                "Terima kasih sudah menggunakan layanan kami!",
                function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Konfirmasi Checkout');
                }
            );
            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Checkout berhasil, silakan tunggu konfirmasi admin');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}
