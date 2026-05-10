@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title">Validasi Pengambilan</h2>
        <button onclick="startScan()" class="btn btn-navy shadow-sm">
            <i class="fas fa-qrcode me-2"></i> Scan QR
        </button>
    </div>

    <div id="reader-container" class="mb-4 d-none">
        <div class="card card-custom">
            <div class="card-body">
                <div id="reader" style="width: 100%"></div>
                <button onclick="stopScan()" class="btn btn-danger btn-sm mt-3 w-100">Tutup Kamera</button>
            </div>
        </div>
    </div>

    <div class="card card-custom mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-12 col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama member atau kode transaksi..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-navy w-100">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-items mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Kode</th>
                            <th>Member</th>
                            <th>Jadwal Pickup</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $trx)
                        <tr>
                            <td class="ps-4 fw-bold" data-label="Kode">{{ $trx->transaction_code }}</td>
                            <td data-label="Member">
                                <div class="fw-semibold">{{ $trx->user->name }}</div>
                            </td>
                            <td data-label="Pickup">
                                <span class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $trx->pickup_datetime->translatedFormat('d F Y H:i') }} WIB</span>
                            </td>
                            <td class="text-center" data-label="Aksi">
                                <a href="{{ route('admin.pickups.show', $trx) }}" class="btn btn-navy btn-sm px-4 shadow-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrCode;

    function startScan() {
        document.getElementById('reader-container').classList.remove('d-none');
        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrCode.start({ facingMode: "environment" }, config, (decodedText) => {
            window.location.href = "/admin/scan/" + decodedText;
        });
    }

    function stopScan() {
        if(html5QrCode) {
            html5QrCode.stop().then(() => {
                document.getElementById('reader-container').classList.add('d-none');
            });
        }
    }
</script>
@endpush
@endsection