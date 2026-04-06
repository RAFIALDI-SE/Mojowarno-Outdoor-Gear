@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold m-0 text-navy">Tambah Produk Alat Outdoor</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-navy">Kategori</label>
                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-navy">Nama Produk</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Contoh: Tenda Dome 4P" value="{{ old('name') }}">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-navy">Harga Sewa / Hari</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">Rp</span>
                                        <input type="number" name="price_per_day" class="form-control @error('price_per_day') is-invalid @enderror"
                                               placeholder="0" value="{{ old('price_per_day') }}">
                                    </div>
                                    @error('price_per_day') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-navy">Stok Tersedia</label>
                                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                           placeholder="Jumlah unit" value="{{ old('stock') }}">
                                    @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-navy">Deskripsi Produk</label>
                                    <textarea name="description" class="form-control" rows="4"
                                              placeholder="Jelaskan detail alat, kelengkapan, dan kondisi...">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-navy px-4 shadow-sm">Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection