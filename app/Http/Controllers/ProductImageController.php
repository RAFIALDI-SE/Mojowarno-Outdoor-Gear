<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    // tampilkan gambar berdasarkan produk
    public function index($productId)
    {
        $product = Product::with('images')->findOrFail($productId);
        return view('admin.product_images.index', compact('product'));
    }

    // simpan gambar
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $request->file('image')->store('product-images', 'public');

        ProductImage::create([
            'product_id' => $request->product_id,
            'image'      => $imagePath
        ]);

        return back()->with('success', 'Gambar berhasil ditambahkan');
    }

    // hapus gambar
    public function destroy($id)
    {
        $image = ProductImage::findOrFail($id);

        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus');
    }
}
