@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold m-0" style="color: #0c2140;">Edit Syarat & Ketentuan</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('terms.update', $term->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-5 text-center mb-3 mb-md-0">
                                <label class="form-label fw-semibold d-block text-start">Gambar Saat Ini</label>
                                <div class="p-2 border rounded bg-light">
                                    <img src="{{ asset('storage/'.$term->image) }}"
                                         class="w-100 shadow-sm rounded"
                                         style="max-height: 250px; object-fit: contain;">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Ganti Gambar</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-info mt-2 d-block fst-italic">
                                    *Pilih file hanya jika ingin mengganti gambar yang sudah ada.
                                </small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('terms.index') }}" class="btn btn-light px-4 me-2">Kembali</a>
                            <button type="submit" class="btn px-4 text-white" style="background-color: #0c2140;">
                                Update Gambar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection