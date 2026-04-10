@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold m-0" style="color: #0c2140;">Tambah Syarat & Ketentuan</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('terms.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Upload Gambar</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted mt-2 d-block">
                                Upload gambar syarat & ketentuan dalam format JPG/PNG/WEBP.
                            </small>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('terms.index') }}" class="btn btn-light px-4 me-2">Batal</a>
                            <button type="submit" class="btn px-4 text-white" style="background-color: #0c2140;">
                                Simpan Gambar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection