@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h3 class="fw-bold mb-0" style="color: #0c2140;">Syarat & Ketentuan</h3>
        <a href="{{ route('terms.create') }}" class="btn px-4 shadow-sm text-white" style="background-color: #0c2140;">
            <i class="fas fa-plus me-2"></i> Tambah Gambar
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
    @foreach($terms as $item)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-2">
                    <img src="{{ asset('storage/'.$item->image) }}"
                         class="rounded w-100 mb-3"
                         style="height: 180px; object-fit: contain; background-color: #f8f9fa;">

                    <div class="d-flex justify-content-center gap-2 pb-2">
                        <a href="{{ route('terms.edit', $item->id) }}"
                           class="btn btn-light btn-sm text-warning shadow-sm flex-fill">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>

                        <form action="{{ route('terms.delete', $item->id) }}" method="POST" class="flex-fill">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus gambar ini?')"
                                    class="btn btn-light btn-sm text-danger shadow-sm w-100">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    const alertElement = document.querySelector('.alert');
    if (alertElement) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertElement);
            bsAlert.close();
        }, 3000);
    }
</script>
@endpush