@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Daftar Retur Barang</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('retur.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah Retur
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
                            <th>ID Retur</th>
                            <th>Tanggal</th>
                            <th>ID Penerimaan</th>
                            <th>ID Pengadaan</th>
                            <th>Vendor</th>
                            <th>Dicatat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($retur as $item)
                        <tr>
                            <td>{{ $item->idretur }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($item->tanggal_retur)) }}</td>
                            <td>{{ $item->idpenerimaan }}</td>
                            <td>{{ $item->idpengadaan }}</td>
                            <td>{{ $item->nama_vendor }}</td>
                            <td>{{ $item->dicatat_oleh }}</td>
                            <td>
                                <a href="{{ route('retur.show', $item->idretur) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data retur</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection