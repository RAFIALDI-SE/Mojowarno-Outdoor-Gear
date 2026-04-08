@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card p-3 h-100 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-secondary mb-1" style="font-size: 0.85rem;">User Terdaftar</p>
                        <h4 class="fw-bold mb-0">{{ $totalUsers }}</h4>
                    </div>
                    <div class="stat-icon d-none d-sm-flex"><i class="fas fa-users"></i></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card p-3 h-100 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-secondary mb-1" style="font-size: 0.85rem;">Total Produk</p>
                        <h4 class="fw-bold mb-0">{{ $totalProducts }}</h4>
                    </div>
                    <div class="stat-icon d-none d-sm-flex"><i class="fas fa-box"></i></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card p-3 h-100 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-secondary mb-1" style="font-size: 0.85rem;">Penyewaan Aktif</p>
                        <h4 class="fw-bold mb-0">{{ $activeRentals }}</h4>
                    </div>
                    <div class="stat-icon d-none d-sm-flex" style="background-color: #e3f2fd;"><i class="fas fa-clock"></i></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card p-3 h-100 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-secondary mb-1" style="font-size: 0.85rem;">Pendapatan</p>
                        <h4 class="fw-bold mb-0">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="stat-icon d-none d-sm-flex" style="background-color: #d1e7dd; color: #0f5132;"><i class="fas fa-wallet"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-4 border-0 shadow-sm">

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold mb-0">Grafik Pendapatan</h5>

                    <form method="GET" class="d-flex gap-2">
                        <input type="date"
                               name="start_date"
                               value="{{ request('start_date') ?? $startDate->format('Y-m-d') }}"
                               class="form-control form-control-sm">

                        <input type="date"
                               name="end_date"
                               value="{{ request('end_date') ?? $endDate->format('Y-m-d') }}"
                               class="form-control form-control-sm">

                        <button class="btn btn-sm btn-navy">
                            Filter
                        </button>
                    </form>
                </div>

                <div style="height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 border-0 shadow-sm h-100">
                <h5 class="fw-bold mb-4">User Terbaru</h5>
                <div class="list-group list-group-flush">
                    @foreach($latestUsers as $user)
                        <div class="list-group-item d-flex align-items-center border-0 px-0 mb-2">
                            <img
                                src="{{ $user->avatar
                                    ? asset('storage/'.$user->avatar)
                                    : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0c2140&color=fff' }}"
                                class="rounded-circle me-3"
                                width="40"
                            >
                            <div class="overflow-hidden">
                                <h6 class="mb-0 fw-bold text-truncate">{{ $user->name }}</h6>
                                <small class="text-muted text-truncate d-block">{{ $user->email }}</small>
                            </div>
                        </div>
                    @endforeach

                </div>
                <form action="#" method="GET">
                    <button class="btn btn-outline-primary btn-sm mt-auto w-100">Lihat Semua User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Pendapatan',
                data: @json($data),
                borderColor: '#0c2140',
                backgroundColor: 'rgba(154, 193, 248, 0.2)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#0c2140'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
