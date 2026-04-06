@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold m-0 text-navy">Edit Data: {{ $product->name }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-navy">Kategori</label>
                                    <select name="category_id" class="form-select">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-navy">Nama Produk</label>
                                    <input type="text" name="name" class="form-control" value="{{ $product->name }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-navy">Harga Sewa / Hari</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price_per_day" class="form-control" value="{{ $product->price_per_day }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-navy">Stok</label>
                                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-navy">Deskripsi</label>
                                    <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-navy px-4 shadow-sm">Update Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection