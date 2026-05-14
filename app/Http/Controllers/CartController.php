<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Halaman cart
    public function index()
    {
        $user = Auth::user();

        $cart = Cart::with('items.product.images')
            ->firstOrCreate(['user_id' => $user->id]);

        return view('member.cart.index', compact('cart', 'user'));
    }

    // Tambah ke cart
    public function add(Product $product)
    {
        $user = Auth::user();

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            if ($item->qty + 1 > $product->stock) {
                return back()->with('error', 'Stok tidak mencukupi');
            }

            $item->increment('qty');
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'qty' => 1,
                'price_per_day' => $product->price_per_day,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang');
    }

    // Update qty
    public function update(Request $request, CartItem $item)
    {

        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        if ($request->qty > $item->product->stock) {

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok yang tersedia (' . $item->product->stock . ')'
                ], 422);
            }

            return back()->with('error', 'Qty melebihi stok');
        }

        $item->update([
            'qty' => $request->qty
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang diperbarui'
            ]);
        }

        return back()->with('success', 'Keranjang diperbarui');
    }

    // Hapus item
    public function remove(CartItem $item)
    {
        $item->delete();
        return back()->with('success', 'Item dihapus dari keranjang');
    }
}
