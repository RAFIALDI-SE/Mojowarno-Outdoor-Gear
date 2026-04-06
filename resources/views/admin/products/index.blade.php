@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h3 class="fw-bold text-navy mb-0">Katalog Alat Outdoor</h3>
        <a href="{{ route('products.create') }}" class="btn btn-navy px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Produk
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm p-2 p-md-3">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kategori</th>
                        <th>Nama Produk</th>
                        <th>Harga / Hari</th>
                        <th>Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $key => $item)
                    <tr>
                        <td class="fw-bold text-secondary">{{ $key + 1 }}</td>
                        <td>
                            <span class="badge px-3 py-2 fw-medium" style="background-color: var(--light-blue); color: var(--dark-navy);">
                                {{ $item->category->name }}
                            </span>
                        </td>
                        <td class="fw-semibold text-navy">{{ $item->name }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</td>
                        <td>
                            <span class="fw-bold">{{ $item->stock }}</span> <small class="text-muted">Unit</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('product_images.index', $item->id) }}" class="btn btn-light btn-sm text-primary shadow-sm" title="Kelola Gambar">
                                    <i class="fas fa-images"></i>
                                    <span class="d-none d-md-inline">Gambar</span>
                                </a>
                                <a href="{{ route('products.edit', $item->id) }}" class="btn btn-light btn-sm text-warning shadow-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                    <span class="d-none d-md-inline">Edit</span>
                                </a>

                                <form action="{{ route('products.delete', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin hapus?')" class="btn btn-light btn-sm text-danger shadow-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-none d-md-inline">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">Belum ada produk yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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