@extends('layout.superadmin')

@section('content')
<div class="container-fluid mt-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">Dashboard Superadmin</h2>
            <p class="text-muted">Selamat datang di sistem manajemen inventory</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Barang -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Barang
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats->total_barang ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total User -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total User
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats->total_user ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendor Aktif -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Vendor Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats->total_vendor_aktif ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penjualan -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pendapatan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats->total_pendapatan ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row Stats -->
    <div class="row mb-4">
        <!-- Total Pengadaan -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow h-100 py-2" style="border-left: 4px solid #4e73df;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #4e73df;">
                                Total Pengadaan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats->total_pengadaan ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penerimaan -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow h-100 py-2" style="border-left: 4px solid #1cc88a;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1cc88a;">
                                Total Penerimaan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats->total_penerimaan ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-inbox fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penjualan Transaksi -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow h-100 py-2" style="border-left: 4px solid #36b9cc;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #36b9cc;">
                                Total Penjualan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats->total_penjualan ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cash-register fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Retur -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow h-100 py-2" style="border-left: 4px solid #e74a3b;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #e74a3b;">
                                Total Retur
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats->total_retur ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-undo fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Barang Stok Menipis -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-exclamation-triangle"></i> Barang Stok Menipis
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($barangStokMenipis as $item)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <h6 class="mb-0">{{ $item->nama_barang }}</h6>
                                <small class="text-muted">{{ $item->nama_satuan }}</small>
                            </div>
                            <span class="badge bg-warning text-dark">
                                {{ $item->stok_tersedia }} unit
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-muted mb-0">
                            <i class="fas fa-check-circle text-success"></i><br>
                            Semua stok aman
                        </p>
                    @endforelse

                    @if(count($barangStokMenipis) > 0)
                        <a href="{{ route('kartustok.stok-terkini') }}" class="btn btn-sm btn-warning w-100 mt-2">
                            <i class="fas fa-eye"></i> Lihat Semua Stok
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top 5 Stok Terbanyak -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-bar"></i> Top 5 Stok Terbanyak
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($topStok as $item)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <h6 class="mb-0">{{ $item->nama_barang }}</h6>
                            </div>
                            <span class="badge bg-success">
                                {{ $item->stok_tersedia }} unit
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-muted mb-0">Belum ada data stok</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-list"></i> Transaksi Terbaru
                    </h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($transaksiTerbaru as $item)
                        <div class="d-flex justify-content-between align-items-start mb-3 pb-2 border-bottom">
                            <div>
                                <h6 class="mb-0">
                                    @if($item->jenis_transaksi == 'Pengadaan')
                                        <span class="badge bg-primary">{{ $item->jenis_transaksi }}</span>
                                    @elseif($item->jenis_transaksi == 'Penerimaan')
                                        <span class="badge bg-success">{{ $item->jenis_transaksi }}</span>
                                    @elseif($item->jenis_transaksi == 'Penjualan')
                                        <span class="badge bg-info">{{ $item->jenis_transaksi }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $item->jenis_transaksi }}</span>
                                    @endif
                                </h6>
                                <small class="text-muted">{{ $item->keterangan }}</small><br>
                                <small class="text-muted">{{ date('d/m/Y H:i', strtotime($item->tanggal)) }}</small>
                            </div>
                            @if($item->nilai > 0)
                                <small class="text-success fw-bold">
                                    Rp {{ number_format($item->nilai, 0, ',', '.') }}
                                </small>
                            @endif
                        </div>
                    @empty
                        <p class="text-center text-muted mb-0">Belum ada transaksi</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Penjualan 7 Hari -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-line"></i> Grafik Penjualan 7 Hari Terakhir
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="chartPenjualan" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-info {
    border-left: 4px solid #36b9cc !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart Penjualan
const ctx = document.getElementById('chartPenjualan').getContext('2d');

const labels = [
    @foreach($chartPenjualan as $data)
        '{{ date("d/m", strtotime($data->tanggal)) }}',
    @endforeach
];

const dataValues = [
    @foreach($chartPenjualan as $data)
        {{ $data->total_nilai ?? 0 }},
    @endforeach
];

const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Total Penjualan (Rp)',
            data: dataValues,
            backgroundColor: 'rgba(102, 126, 234, 0.2)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endsection