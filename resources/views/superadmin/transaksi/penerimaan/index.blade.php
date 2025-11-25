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
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penerimaan as $item)
                        <tr>
                            <td>{{ $item->idpenerimaan }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($item->tanggal_penerimaan)) }}</td>
                            <td>{{ $item->idpengadaan }}</td>
                            <td>{{ $item->nama_vendor }}</td>
                            <td>{{ $item->diterima_oleh }}</td>
                            <td>
                                @if($item->status == 'C')
                                    <span class="badge bg-success">selesai</span>
                                @elseif($item->status == 'P')
                                    <span class="badge bg-warning">sebagian</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('penerimaan.show', $item->idpenerimaan) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data penerimaan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection