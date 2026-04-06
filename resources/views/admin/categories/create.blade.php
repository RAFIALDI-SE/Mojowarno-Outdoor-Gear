@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold m-0 text-navy">Tambah Kategori Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nama Kategori</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Contoh: Tenda, Carrier, Sepatu" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('categories.index') }}" class="btn btn-light px-4">Kembali</a>
                            <button type="submit" class="btn btn-navy px-4">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection