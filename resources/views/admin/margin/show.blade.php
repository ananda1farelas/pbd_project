@extends('layout.admin')

@section('title', 'Detail Margin Penjualan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Margin Penjualan</h2>
        <a href="{{ route('admin.margin.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Informasi Margin -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Informasi Margin
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="40%">ID Margin</th>
                            <td>: <strong>{{ $margin->idmargin_penjualan }}</strong></td>
                        </tr>
                        <tr>
                            <th>Persentase Margin</th>
                            <td>: 
                                <span class="badge bg-primary fs-5">
                                    <i class="bi bi-percent"></i> {{ number_format($margin->persen, 2) }}%
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($margin->status == 1 || strtolower($margin->status) == 'aktif')
                                    <span class="badge bg-success fs-6">
                                        <i class="bi bi-check-circle"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="bi bi-x-circle"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>: 
                                <i class="bi bi-person-circle"></i> 
                                <strong>{{ $margin->dibuat_oleh }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>: 
                                <i class="bi bi-calendar-plus"></i>
                                {{ \Carbon\Carbon::parse($margin->created_at)->format('d F Y, H:i') }} WIB
                            </td>
                        </tr>
                        <tr>
                            <th>Terakhir Diupdate</th>
                            <td>: 
                                <i class="bi bi-calendar-check"></i>
                                {{ \Carbon\Carbon::parse($margin->updated_at)->format('d F Y, H:i') }} WIB
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistik Penggunaan -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up"></i> Statistik Penggunaan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="display-4 text-success mb-2">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-success">
                            {{ count($penjualan) }}
                        </h2>
                        <p class="text-muted mb-0">Transaksi Menggunakan Margin Ini</p>
                        <small class="text-muted">(10 Terakhir ditampilkan)</small>
                    </div>

                    @if(count($penjualan) > 0)
                        @php
                            $totalNilai = array_sum(array_column($penjualan, 'total_nilai'));
                            $rataRata = count($penjualan) > 0 ? $totalNilai / count($penjualan) : 0;
                        @endphp
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Total Nilai</h6>
                                <h5 class="text-success mb-0">
                                    Rp {{ number_format($totalNilai, 0, ',', '.') }}
                                </h5>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Rata-rata</h6>
                                <h5 class="text-info mb-0">
                                    Rp {{ number_format($rataRata, 0, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Penjualan -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="bi bi-clock-history"></i> Riwayat Penjualan (10 Terakhir)
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID Penjualan</th>
                            <th>Tanggal Transaksi</th>
                            <th>User</th>
                            <th>Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualan as $item)
                            <tr>
                                <td>
                                    <span class="badge bg-dark">
                                        #{{ $item->idpenjualan }}
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar3"></i>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                                    <br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <i class="bi bi-person-circle"></i>
                                    {{ $item->username }}
                                </td>
                                <td>
                                    <strong class="text-success">
                                        Rp {{ number_format($item->total_nilai, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        <p class="mb-0">Belum ada transaksi yang menggunakan margin ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($penjualan) > 0)
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Total (10 Terakhir):</th>
                                <th class="text-success">
                                    Rp {{ number_format(array_sum(array_column($penjualan, 'total_nilai')), 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Contoh Perhitungan -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary bg-opacity-10">
                    <h6 class="mb-0 text-primary">
                        <i class="bi bi-calculator"></i> Contoh Perhitungan Margin
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">Dengan margin <strong>{{ number_format($margin->persen, 2) }}%</strong>, perhitungan harga jual adalah:</p>
                    <div class="bg-light p-3 rounded">
                        <code>
                            Harga Jual = Harga Satuan + (Harga Satuan × {{ number_format($margin->persen, 2) }}%)
                        </code>
                        <hr>
                        <strong>Contoh:</strong><br>
                        Jika Harga Satuan = Rp 100.000<br>
                        Maka Harga Jual = Rp 100.000 + (Rp 100.000 × {{ number_format($margin->persen, 2) }}%) 
                        = Rp {{ number_format(100000 + (100000 * $margin->persen / 100), 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection