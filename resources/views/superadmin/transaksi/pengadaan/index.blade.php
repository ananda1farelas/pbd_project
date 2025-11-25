@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Daftar Pengadaan</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('pengadaan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah Pengadaan
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

    <!-- Filter Status -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Data</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pengadaan.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-10">
                    <label class="form-label">Filter Berdasarkan Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="A" {{ $status == 'A' ? 'selected' : '' }}>Approved</option>
                        <option value="S" {{ $status == 'S' ? 'selected' : '' }}>Selesai</option>
                        <option value="C" {{ $status == 'C' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
            @if($status)
                <div class="mt-2">
                    <a href="{{ route('pengadaan.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-times"></i> Reset Filter
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Pengadaan</th>
                            <th>Tanggal</th>
                            <th>Vendor</th>
                            <th>User</th>
                            <th>Subtotal</th>
                            <th>PPN (10%)</th>
                            <th>Total Nilai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengadaan as $item)
                        <tr>
                            <td>
                                <a href="{{ route('pengadaan.show', $item->idpengadaan) }}" class="text-decoration-none">
                                    <strong>{{ $item->idpengadaan }}</strong>
                                </a>
                            </td>
                            <td>{{ date('d/m/Y H:i', strtotime($item->timestamp)) }}</td>
                            <td>{{ $item->nama_vendor }}</td>
                            <td>{{ $item->username }}</td>
                            <td>Rp {{ number_format($item->subtotal_nilai, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->ppn, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($item->status == 'A')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </span>
                                @elseif($item->status == 'S')
                                    <span class="badge bg-primary">
                                        <i class="fas fa-check-double"></i> Selesai
                                    </span>
                                @elseif($item->status == 'C')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle"></i> Cancelled
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- Tombol Detail -->
                                    <a href="{{ route('pengadaan.show', $item->idpengadaan) }}" 
                                    class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    
                                    <!-- Tombol Cancel (hanya untuk status Approved) -->
                                    @if($item->status == 'A')
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Yakin ingin membatalkan pengadaan ini?')) { document.getElementById('cancel-form-{{ $item->idpengadaan }}').submit(); }">
                                            <i class="fas fa-ban"></i> Cancel
                                        </button>
                                        
                                        <form id="cancel-form-{{ $item->idpengadaan }}" 
                                            action="{{ route('pengadaan.cancel', $item->idpengadaan) }}" 
                                            method="POST" 
                                            style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                @if($status)
                                    Tidak ada pengadaan dengan status <strong>{{ $status == 'A' ? 'Approved' : 'Cancelled' }}</strong>
                                @else
                                    Belum ada data pengadaan
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