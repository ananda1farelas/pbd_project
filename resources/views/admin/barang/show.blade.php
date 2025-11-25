@extends('layout.admin')

@section('title', 'Detail Barang')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Barang</h2>
        <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Informasi Barang -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Barang</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">ID Barang</th>
                            <td>: {{ $barang->idbarang }}</td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>: <strong>{{ $barang->nama }}</strong></td>
                        </tr>
                        <tr>
                            <th>Jenis</th>
                            <td>: {{ $barang->jenis }}</td>
                        </tr>
                        <tr>
                            <th>Satuan</th>
                            <td>: {{ $barang->nama_satuan }}</td>
                        </tr>
                        <tr>
                            <th>Harga Satuan</th>
                            <td>: <span class="text-success fw-bold">Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }}</span></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($barang->status == 1)
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

        <!-- Stok Info -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Informasi Stok</h5>
                </div>
                <div class="card-body text-center">
                    <h6 class="text-muted">Stok Tersedia</h6>
                    @php
                        $stokTersedia = $historyStok[0]->stock ?? 0;
                    @endphp
                    <h1 class="display-4 fw-bold 
                        {{ $stokTersedia > 10 ? 'text-success' : ($stokTersedia > 0 ? 'text-warning' : 'text-danger') }}">
                        {{ $stokTersedia }}
                    </h1>
                    <p class="text-muted">{{ $barang->nama_satuan }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- History Stok -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Riwayat Stok (10 Terakhir)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Transaksi</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Stok Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historyStok as $history)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($history->jenis_transaksi == 'M')
                                        <span class="badge bg-success">{{ $history->jenis_label }}</span>
                                    @elseif($history->jenis_transaksi == 'K')
                                        <span class="badge bg-danger">{{ $history->jenis_label }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ $history->jenis_label }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($history->masuk > 0)
                                        <span class="text-success">+{{ $history->masuk }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($history->keluar > 0)
                                        <span class="text-danger">-{{ $history->keluar }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><strong>{{ $history->stock }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted mb-0">Belum ada riwayat stok</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection