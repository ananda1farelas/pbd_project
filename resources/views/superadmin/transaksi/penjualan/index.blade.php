@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Daftar Penjualan</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('superadmin.penjualan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah Penjualan
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Penjualan</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Margin</th>
                            <th>Subtotal</th>
                            <th>PPN (10%)</th>
                            <th>Total Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualan as $item)
                        <tr>
                            <td>{{ $item->idpenjualan }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                            <td>{{ $item->username }}</td>
                            <td><span class="badge bg-info">{{ $item->margin }}%</span></td>
                            <td>Rp {{ number_format($item->subtotal_nilai, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->ppn, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</strong></td>
                            <td>
                                <a href="{{ route('superadmin.penjualan.show', $item->idpenjualan) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <form action="{{ route('superadmin.penjualan.destroy', $item->idpenjualan) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data penjualan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection