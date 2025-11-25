@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>History Kartu Stok - {{ $barang->nama_barang }}</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('kartustok.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Info Barang Card -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi Barang</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">ID Barang</th>
                            <td>: {{ $barang->idbarang }}</td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>: <strong>{{ $barang->nama_barang }}</strong></td>
                        </tr>
                        <tr>
                            <th>Satuan</th>
                            <td>: {{ $barang->nama_satuan }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Stok Terkini</th>
                            <td>: 
                                <h4 class="mb-0">
                                    @if($barang->stok_tersedia == 0)
                                        <span class="badge bg-danger">{{ $barang->stok_tersedia }}</span>
                                    @elseif($barang->stok_tersedia < 10)
                                        <span class="badge bg-warning">{{ $barang->stok_tersedia }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $barang->stok_tersedia }}</span>
                                    @endif
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <th>Update Terakhir</th>
                            <td>: 
                                @if($barang->update_terakhir)
                                    {{ date('d/m/Y H:i', strtotime($barang->update_terakhir)) }}
                                @else
                                    <span class="text-muted">Belum ada transaksi</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- History Card -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Transaksi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis Transaksi</th>
                            <th class="text-center">Masuk</th>
                            <th class="text-center">Keluar</th>
                            <th class="text-center">Stok Saldo</th>
                            <th>ID Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($history as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                            <td>
                                @if($item->jenis_transaksi == 'M')
                                    <span class="badge bg-success">
                                        <i class="fas fa-arrow-down"></i> Masuk (Penerimaan)
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-arrow-up"></i> Keluar (Penjualan)
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->masuk > 0)
                                    <span class="text-success fw-bold">+{{ $item->masuk }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->keluar > 0)
                                    <span class="text-danger fw-bold">-{{ $item->keluar }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                <strong>{{ $item->stock }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">{{ $item->idtransaksi }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada transaksi untuk barang ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection