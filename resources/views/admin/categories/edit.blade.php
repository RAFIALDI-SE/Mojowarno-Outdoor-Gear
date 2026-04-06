@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0 text-navy">
                    <h5 class="fw-bold m-0">Edit Kategori</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nama Kategori</label>
                            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('categories.index') }}" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-navy px-4">Update Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection