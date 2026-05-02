@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold m-0 text-navy">Tambah Slider Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Slider</label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Diskon Alat Camping" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sub Judul</label>
                            <input type="text" name="subtitle" class="form-control" placeholder="Contoh: Potongan s/d 50%">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Unggah Gambar</label>
                            <input type="file" name="image" class="form-control" required>
                            <small class="text-muted mt-2 d-block">Rekomendasi ukuran: 1920x600 px</small>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('sliders.index') }}" class="btn btn-light px-4 me-2">Batal</a>
                            <button type="submit" class="btn btn-navy px-4">Simpan Slider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection