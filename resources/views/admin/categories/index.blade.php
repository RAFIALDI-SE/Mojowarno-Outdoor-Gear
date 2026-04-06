@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h3 class="fw-bold text-navy mb-0">Kategori Alat Outdoor</h3>
        <a href="{{ route('categories.create') }}" class="btn btn-navy px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Kategori
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
                        <th>Nama Kategori</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $key => $item)
                    <tr>
                        <td class="fw-bold text-secondary">{{ $key + 1 }}</td>
                        <td class="fw-semibold text-navy">{{ $item->name }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('categories.edit', $item->id) }}" class="btn btn-light btn-sm text-warning shadow-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                    <span class="d-none d-md-inline">Edit</span> </a>

                                <form action="{{ route('categories.delete', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus kategori ini?')" class="btn btn-light btn-sm text-danger shadow-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-none d-md-inline">Hapus</span> </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Belum ada data kategori.</td>
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