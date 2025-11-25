@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Detail Retur Barang</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('retur.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Informasi Retur --}}
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi Retur</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">ID Retur</th>
                            <td>: {{ $retur->idretur }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Retur</th>
                            <td>: {{ date('d/m/Y H:i', strtotime($retur->created_at)) }}</td>
                        </tr>
                        <tr>
                            <th>ID Penerimaan</th>
                            <td>: {{ $retur->idpenerimaan }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">ID Pengadaan</th>
                            <td>: {{ $penerimaan?->idpengadaan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Vendor</th>
                            <td>: {{ $penerimaan?->nama_vendor ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dicatat Oleh</th>
                            <td>: {{ isset($user) && is_object($user) ? $user->username : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Barang Retur --}}
    <div class="card mb-3">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Detail Barang yang Di-retur</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th class="text-center">Jumlah Retur</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($details as $detail)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $detail->nama_barang }}</td>
                            <td>{{ $detail->nama_satuan }}</td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $detail->jumlah_retur }}</span>
                            </td>
                            <td>{{ $detail->alasan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
