@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Stok Terkini</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('kartustok.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Kartu Stok
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Item Barang</h5>
                    <h2 class="mb-0">{{ count($stok) }}</h2>
                    <small>Item aktif</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Stok Tersedia</h5>
                    <h2 class="mb-0">{{ array_sum(array_column($stok, 'stok_tersedia')) }}</h2>
                    <small>Total unit</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Stok Menipis</h5>
                    <h2 class="mb-0">
                        @php
                            $stokMenipis = array_filter($stok, fn($item) => $item->stok_tersedia > 0 && $item->stok_tersedia < 10);
                        @endphp
                        {{ count($stokMenipis) }}
                    </h2>
                    <small>Item < 10 unit</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Stok Habis</h5>
                    <h2 class="mb-0">
                        @php
                            $stokHabis = array_filter($stok, fn($item) => $item->stok_tersedia == 0);
                        @endphp
                        {{ count($stokHabis) }}
                    </h2>
                    <small>Item habis</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Stok -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-box-open"></i> Daftar Stok Barang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th class="text-center">Stok Tersedia</th>
                            <th class="text-center">Status</th>
                            <th>Update Terakhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($stok as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->nama_satuan }}</td>
                            <td class="text-center">
                                <h5 class="mb-0">
                                    @if($item->stok_tersedia == 0)
                                        <span class="badge bg-danger">{{ $item->stok_tersedia }}</span>
                                    @elseif($item->stok_tersedia < 10)
                                        <span class="badge bg-warning">{{ $item->stok_tersedia }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $item->stok_tersedia }}</span>
                                    @endif
                                </h5>
                            </td>
                            <td class="text-center">
                                @if($item->stok_tersedia == 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($item->stok_tersedia < 10)
                                    <span class="badge bg-warning">Menipis</span>
                                @else
                                    <span class="badge bg-success">Aman</span>
                                @endif
                            </td>
                            <td>
                                @if($item->update_terakhir)
                                    {{ date('d/m/Y H:i', strtotime($item->update_terakhir)) }}
                                @else
                                    <span class="text-muted">Belum ada transaksi</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('kartustok.show', $item->idbarang) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-history"></i> History
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data barang</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection