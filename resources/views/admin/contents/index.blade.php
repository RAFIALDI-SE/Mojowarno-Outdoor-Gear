@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h3 class="fw-bold mb-0" style="color: #0c2140;">Data Konten</h3>
        <a href="{{ route('contents.create') }}" class="btn px-4 shadow-sm text-white" style="background-color: #0c2140;">
            <i class="fas fa-plus me-2"></i> Tambah Konten
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
                        <th>Gambar</th>
                        <th>Informasi Konten</th>
                        <th>Redirect URL</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contents as $key => $item)
                    <tr>
                        <td class="fw-bold text-secondary">{{ $key + 1 }}</td>
                        <td>
                            <img src="{{ asset('storage/'.$item->image) }}"
                                 class="shadow-sm rounded"
                                 style="width: 100px; height: 60px; object-fit: cover; border: 1px solid #eee;">
                        </td>
                        <td>
                            <h6 class="fw-bold mb-1 text-truncate" style="color: #0c2140; max-width: 250px;">
                                {{ $item->title }}
                            </h6>
                            <small class="text-muted">Konten Aktif</small>
                        </td>
                        <td>
                            <a href="{{ $item->redirect_url }}" target="_blank" class="text-decoration-none small text-primary">
                                {{ Str::limit($item->redirect_url, 30) }}
                                <i class="fas fa-external-link-alt ms-1" style="font-size: 10px;"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('contents.edit', $item->id) }}"
                                   class="btn btn-light btn-sm text-warning shadow-sm"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                    <span class="d-none d-md-inline">Edit</span>
                                </a>

                                <form action="{{ route('contents.delete', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus konten ini?')"
                                            class="btn btn-light btn-sm text-danger shadow-sm"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-none d-md-inline">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script auto-hide alert agar konsisten dengan slider
    const alertElement = document.querySelector('.alert');
    if (alertElement) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertElement);
            bsAlert.close();
        }, 3000);
    }
</script>
@endpush