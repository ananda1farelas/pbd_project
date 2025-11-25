@extends('layout.admin')

@section('title', 'Detail Penjualan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Penjualan #{{ $penjualan->idpenjualan }}</h2>
        <div>
            <a href="{{ route('admin.penjualan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Cetak
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Transaksi -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-receipt"></i> Informasi Transaksi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th width="40%">ID Penjualan</th>
                                    <td>: <strong>#{{ $penjualan->idpenjualan }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>: {{ \Carbon\Carbon::parse($penjualan->created_at)->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu</th>
                                    <td>: {{ \Carbon\Carbon::parse($penjualan->created_at)->format('H:i:s') }} WIB</td>
                                </tr>
                                <tr>
                                    <th>Kasir</th>
                                    <td>: 
                                        <i class="bi bi-person-circle"></i>
                                        <strong>{{ isset($user) && is_object($user) ? $user->username : '-' }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th width="40%">Margin</th>
                                    <td>: 
                                        <span class="badge bg-info">
                                            {{ $margin ? number_format($margin->persen, 2) : '0' }}%
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status Margin</th>
                                    <td>: 
                                        @if($margin && ($margin->status == 1 || strtolower($margin->status) == 'aktif'))
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cash-coin"></i> Ringkasan
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th>Subtotal</th>
                            <td class="text-end">
                                Rp {{ number_format($penjualan->subtotal_nilai, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="text-warning">
                            <th>PPN (10%)</th>
                            <td class="text-end">
                                Rp {{ number_format($penjualan->ppn, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-top border-2">
                            <th class="fs-5">Total</th>
                            <td class="text-end fs-4 fw-bold text-success">
                                Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Barang -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="bi bi-cart3"></i> Detail Barang
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th class="text-end">Harga Asli</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $index => $detail)
                            @php
                                $marginRupiah = $detail->subtotal - ($detail->harga_asli * $detail->jumlah);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $detail->nama_barang }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $detail->nama_satuan }}
                                    </span>
                                </td>
                                <td class="text-end text-muted">
                                    Rp {{ number_format($detail->harga_asli, 0, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    <strong>Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">
                                        {{ $detail->jumlah }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <strong class="text-success">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="text-end">
                                    <span class="text-info">
                                        +Rp {{ number_format($marginRupiah, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">Tidak ada detail barang</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($details) > 0)
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="5" class="text-end">Total:</td>
                                <td class="text-center">
                                    {{ array_sum(array_column($details, 'jumlah')) }}
                                </td>
                                <td class="text-end text-success">
                                    Rp {{ number_format(array_sum(array_column($details, 'subtotal')), 0, ',', '.') }}
                                </td>
                                <td class="text-end text-info">
                                    @php
                                        $totalMargin = 0;
                                        foreach($details as $detail) {
                                            $totalMargin += ($detail->subtotal - ($detail->harga_asli * $detail->jumlah));
                                        }
                                    @endphp
                                    +Rp {{ number_format($totalMargin, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Informasi Tambahan -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-info-circle"></i> Catatan
                    </h6>
                    <p class="small mb-0">
                        Harga jual sudah termasuk margin sebesar <strong>{{ $margin ? number_format($margin->persen, 2) : '0' }}%</strong> dari harga asli barang.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-warning bg-opacity-10 border-warning">
                <div class="card-body">
                    <h6 class="text-warning mb-2">
                        <i class="bi bi-calculator"></i> Perhitungan PPN
                    </h6>
                    <p class="small mb-0">
                        PPN 10% dihitung dari subtotal sebelum margin: 
                        <strong>Rp {{ number_format($penjualan->subtotal_nilai, 0, ',', '.') }} × 10%</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .btn, .card-header, nav, footer {
            display: none !important;
        }
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush
@endsection