@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold m-0" style="color: #0c2140;">Edit Data Konten</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('contents.update', $content->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Konten</label>
                            <input type="text" name="title" value="{{ $content->title }}" class="form-control" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold d-block">Gambar Saat Ini</label>
                                <img src="{{ asset('storage/'.$content->image) }}"
                                     class="img-preview w-100 shadow-sm rounded border"
                                     style="object-fit: cover; max-height: 150px;">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Ganti Gambar (Opsional)</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted mt-2 d-block fst-italic">
                                    *Kosongkan jika tidak ingin mengganti gambar.
                                </small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Redirect URL</label>
                            <input type="url" name="redirect_url" value="{{ $content->redirect_url }}" class="form-control" required>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('contents.index') }}" class="btn btn-light px-4 me-2">Batal</a>
                            <button type="submit" class="btn px-4 text-white" style="background-color: #0c2140;">
                                Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection