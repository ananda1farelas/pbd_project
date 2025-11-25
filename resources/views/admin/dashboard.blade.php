@extends('layout.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard</h2>
        <div class="text-muted">
            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <!-- Total Barang -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Barang Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalBarang) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box-seam fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penjualan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Transaksi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalPenjualan) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cart-check fs-2 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Margin Aktif -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Margin Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($marginAktif) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-percent fs-2 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pendapatan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-stack fs-2 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Penjualan Hari Ini & Chart -->
    <div class="row">
        <!-- Penjualan Hari Ini -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Penjualan Hari Ini</h6>
                </div>
                <div class="card-body text-center">
                    <div class="display-4 text-primary mb-3">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h2 class="display-3 font-weight-bold">{{ $penjualanHariIni }}</h2>
                    <p class="text-muted mb-0">Transaksi</p>
                    <hr>
                    <small class="text-muted">
                        <i class="bi bi-calendar-day"></i> {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Chart Penjualan 7 Hari -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Grafik Penjualan 7 Hari Terakhir</h6>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Ringkasan 7 Hari -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">Detail Penjualan 7 Hari Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah Transaksi</th>
                                    <th>Total Nilai</th>
                                    <th>Rata-rata per Transaksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($chartPenjualan as $data)
                                    <tr>
                                        <td>
                                            <i class="bi bi-calendar3"></i> 
                                            {{ \Carbon\Carbon::parse($data->tanggal)->isoFormat('dddd, D MMM YYYY') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $data->jumlah_transaksi }} transaksi</span>
                                        </td>
                                        <td class="text-success fw-bold">
                                            Rp {{ number_format($data->total_nilai, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            Rp {{ number_format($data->jumlah_transaksi > 0 ? $data->total_nilai / $data->jumlah_transaksi : 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <p class="text-muted mb-0">Belum ada data penjualan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($chartPenjualan) > 0)
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td>Total</td>
                                        <td>
                                            <span class="badge bg-dark">{{ array_sum(array_column($chartPenjualan, 'jumlah_transaksi')) }} transaksi</span>
                                        </td>
                                        <td class="text-success">
                                            Rp {{ number_format(array_sum(array_column($chartPenjualan, 'total_nilai')), 0, ',', '.') }}
                                        </td>
                                        <td>-</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .text-xs {
        font-size: .7rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk chart
        const chartData = @json($chartPenjualan);
        
        const labels = chartData.map(item => {
            const date = new Date(item.tanggal);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        
        const jumlahTransaksi = chartData.map(item => item.jumlah_transaksi);
        const totalNilai = chartData.map(item => parseFloat(item.total_nilai));
        
        // Setup chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Jumlah Transaksi',
                        data: jumlahTransaksi,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Total Nilai (Rp)',
                        data: totalNilai,
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.dataset.yAxisID === 'y1') {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                } else {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Jumlah Transaksi'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Total Nilai (Rp)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection