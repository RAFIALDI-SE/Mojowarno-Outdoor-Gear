@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 text-navy">
                    <h5 class="fw-bold m-0">Edit Data Slider</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Slider</label>
                            <input type="text" name="title" value="{{ $slider->title }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sub Judul</label>
                            <input type="text" name="subtitle" value="{{ $slider->subtitle }}" class="form-control">
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold d-block">Gambar Saat Ini</label>
                                <img src="{{ asset('storage/'.$slider->image) }}" class="img-preview w-100 shadow-sm">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Ganti Gambar (Opsional)</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('sliders.index') }}" class="btn btn-light px-4 me-2">Kembali</a>
                            <button type="submit" class="btn btn-navy px-4">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection