@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Daftar Penerimaan Barang</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('penerimaan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah Penerimaan
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
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-box"></i> Data Penerimaan Barang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Penerimaan</th>
                            <th>Tanggal</th>
                            <th>ID Pengadaan</th>
                            <th>Vendor</th>
                            <th>Diterima Oleh</th>
                            <th>Status Penerimaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penerimaan as $item)
                        <tr>
                            <td><strong>{{ $item->idpenerimaan }}</strong></td>
                            <td>{{ date('d/m/Y H:i', strtotime($item->tanggal_penerimaan)) }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->idpengadaan }}</span>
                            </td>
                            <td>{{ $item->nama_vendor }}</td>
                            <td>{{ $item->diterima_oleh }}</td>
                            <td>
                                @if($item->status == 'L')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Lengkap
                                    </span>
                                @elseif($item->status == 'S')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock"></i> Sebagian
                                    </span>
                                @elseif($item->status == 'B')
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-file"></i> Belum Diterima
                                    </span>
                                @else
                                    <span class="badge bg-dark">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('penerimaan.show', $item->idpenerimaan) }}" 
                                   class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>Belum ada data penerimaan</p>
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