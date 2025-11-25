@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Detail Penerimaan Barang</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary">
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

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi Penerimaan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">ID Penerimaan</th>
                            <td>: {{ $penerimaan->idpenerimaan }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: {{ date('d/m/Y H:i', strtotime($penerimaan->created_at)) }}</td>
                        </tr>
                        <tr>
                            <th>ID Pengadaan</th>
                            <td>: {{ $penerimaan->idpengadaan }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Vendor</th>
                            <td>: {{ $pengadaan->nama_vendor ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Diterima Oleh</th>
                            <td>: 
                                @php
                                    $user = DB::select("SELECT username FROM user WHERE iduser = ?", [$penerimaan->iduser]);
                                @endphp
                                {{ $user[0]->username ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($penerimaan->status == 'C')
                                    <span class="badge bg-success">Complete (Lengkap)</span>
                                @elseif($penerimaan->status == 'P')
                                    <span class="badge bg-warning">Partial (Sebagian)</span>
                                @else
                                    <span class="badge bg-secondary">{{ $penerimaan->status }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Detail Barang yang Diterima</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th class="text-center">Jumlah Terima</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($details as $detail)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $detail->nama_barang }}</td>
                            <td class="text-center">{{ $detail->jumlah_terima }}</td>
                            <td class="text-end">Rp {{ number_format($detail->harga_satuan_terima, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($detail->sub_total_terima, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($progress)
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Progress Penerimaan Pengadaan {{ $penerimaan->idpengadaan }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Barang</th>
                            <th class="text-center">Jumlah Pesan</th>
                            <th class="text-center">Total Diterima</th>
                            <th class="text-center">Sisa</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($progress as $item)
                        <tr>
                            <td>{{ $item->nama_barang }}</td>
                            <td class="text-center">{{ $item->jumlah_pesan }}</td>
                            <td class="text-center">{{ $item->total_diterima }}</td>
                            <td class="text-center">{{ $item->sisa_belum_terima }}</td>
                            <td class="text-center">
                                @if($item->status_barang == 'Lengkap')
                                    <span class="badge bg-success">Lengkap</span>
                                @elseif($item->status_barang == 'Partial')
                                    <span class="badge bg-warning">Partial</span>
                                @else
                                    <span class="badge bg-secondary">Belum Diterima</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $persen = ($item->total_diterima / $item->jumlah_pesan) * 100;
                                @endphp
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar {{ $persen == 100 ? 'bg-success' : 'bg-warning' }}" 
                                         role="progressbar" 
                                         style="width: {{ $persen }}%">
                                        {{ number_format($persen, 0) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection