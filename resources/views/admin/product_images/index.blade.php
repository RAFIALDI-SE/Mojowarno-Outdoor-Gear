@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h3 class="fw-bold text-navy mb-1">Kelola Gambar Produk</h3>
            <p class="text-muted mb-0">Produk: <span class="fw-bold" style="color: #9ac1f8">{{ $product->name }}</span></p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-light px-4 border shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Produk
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-navy mb-3"><i class="fas fa-upload me-2"></i>Upload Gambar Baru</h6>
            <form action="{{ route('product_images.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="row align-items-end g-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-bold">Pilih File Gambar</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" required>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted mt-1 d-block">Format: JPG, PNG, WEBP. Maks: 2MB</small>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-navy w-100 shadow-sm">
                            <i class="fas fa-cloud-upload-alt me-2"></i> Mulai Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <h6 class="fw-bold text-navy mb-3"><i class="fas fa-th me-2"></i>Gallery Gambar ({{ $product->images->count() }})</h6>
    <div class="row g-3">
        @forelse($product->images as $img)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100 gallery-card">
                    <div class="position-relative overflow-hidden" style="border-radius: 12px 12px 0 0;">
                        <img src="{{ asset('storage/'.$img->image) }}"
                             class="card-img-top"
                             style="height: 180px; object-fit: cover;">

                        <div class="gallery-overlay">
                            <form action="{{ route('product_images.delete', $img->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm px-3 shadow"
                                        onclick="return confirm('Hapus gambar ini?')">
                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="card border-0 shadow-sm py-5">
                    <i class="fas fa-images fa-3x text-light-blue mb-3"></i>
                    <p class="text-muted">Belum ada gambar untuk produk ini.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Cari element alert
    const alert = document.querySelector('.alert');
    if (alert) {
        // Hilangkan otomatis setelah 3 detik (3000ms)
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 3000);
    }
</script>
@endpush