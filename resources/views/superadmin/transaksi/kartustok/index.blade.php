@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Kartu Stok - Riwayat Transaksi</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('kartustok.stok-terkini') }}" class="btn btn-info">
                <i class="fas fa-box"></i> Stok Terkini
            </a>
            <a href="{{ route('kartustok.summary') }}" class="btn btn-success">
                <i class="fas fa-chart-bar"></i> Summary
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Card -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Data</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kartustok.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Filter Barang</label>
                        <select name="idbarang" class="form-select">
                            <option value="">-- Semua Barang --</option>
                            @foreach($barang as $item)
                                <option value="{{ $item->idbarang }}" {{ $idbarang == $item->idbarang ? 'selected' : '' }}>
                                    {{ $item->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
                @if($idbarang || $start_date || $end_date)
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <a href="{{ route('kartustok.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-times"></i> Reset Filter
                            </a>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Data Card -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Jenis Transaksi</th>
                            <th class="text-center">Masuk</th>
                            <th class="text-center">Keluar</th>
                            <th class="text-center">Stok</th>
                            <th>ID Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kartuStok as $item)
                        <tr>
                            <td>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                            <td>
                                <a href="{{ route('kartustok.show', $item->idbarang) }}" class="text-decoration-none">
                                    {{ $item->nama_barang }}
                                </a>
                            </td>
                            <td>{{ $item->nama_satuan }}</td>
                            <td>
                                @if($item->jenis_transaksi == 'M')
                                    <span class="badge bg-success">
                                        <i class="fas fa-arrow-down"></i> Masuk
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-arrow-up"></i> Keluar
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
                                <span class="badge bg-primary">{{ $item->stock }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $item->idtransaksi }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                @if($idbarang || $start_date || $end_date)
                                    Tidak ada data dengan filter yang dipilih
                                @else
                                    Belum ada transaksi kartu stok
                                @endif
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