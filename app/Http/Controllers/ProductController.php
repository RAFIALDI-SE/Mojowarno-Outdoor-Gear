<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\TermsCondition;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // tampilkan semua produk
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    // form tambah produk
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // simpan produk
    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'description'   => 'required',
            'price_per_day' => 'required|integer|min:0',
            'stock'         => 'required|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    // form edit produk
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // update produk
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'description'   => 'required',
            'price_per_day' => 'required|integer|min:0',
            'stock'         => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    // hapus produk
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    public function productAll(Request $request)
    {
        $query = Product::with(['images', 'category']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }


        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products   = $query->latest()->paginate(12);
        $categories = Category::orderBy('name')->get();

        $terms = TermsCondition::all();

        return view('member.product.all', compact(
            'products',
            'categories',
            'terms'
        ));
    }
}
