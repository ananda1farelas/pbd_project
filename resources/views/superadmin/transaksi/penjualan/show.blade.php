@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Detail Penjualan</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('superadmin.penjualan.index') }}" class="btn btn-secondary">
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
            <h5 class="mb-0">Informasi Penjualan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">ID Penjualan</th>
                            <td>: {{ $penjualan->idpenjualan }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: {{ date('d/m/Y H:i', strtotime($penjualan->created_at)) }}</td>
                        </tr>
                        <tr>
                            <th>Kasir</th>
                            <td>: {{ isset($user) && is_object($user) ? $user->username : '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Margin</th>
                            <td>: <span class="badge bg-info">{{ isset($margin) && is_object($margin) ? $margin->persen : 0 }}%</span></td>
                        </tr>
                        <tr>
                            <th>Status Margin</th>
                            <td>: 
                            @if(isset($margin) && is_object($margin) && ($margin->status == '1' || $margin->status == 'aktif'))
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
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
            <h5 class="mb-0">Detail Barang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th class="text-end">Harga Asli</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">Keuntungan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $no = 1; 
                            $totalKeuntungan = 0;
                        @endphp
                        @foreach($details as $detail)
                        @php
                            $keuntungan = ($detail->harga_jual - $detail->harga_asli) * $detail->jumlah;
                            $totalKeuntungan += $keuntungan;
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $detail->nama_barang }}</td>
                            <td>{{ $detail->nama_satuan }}</td>
                            <td class="text-end">Rp {{ number_format($detail->harga_asli, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $detail->jumlah }}</td>
                            <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            <td class="text-end text-success">
                                <strong>Rp {{ number_format($keuntungan, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="table-info">
                            <td colspan="7" class="text-end"><strong>Total Keuntungan Kotor:</strong></td>
                            <td class="text-end text-success">
                                <strong>Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Total Penjualan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 text-end">
                    <h5>Subtotal:</h5>
                    <h5>PPN (10%):</h5>
                    <h4><strong>Total Nilai:</strong></h4>
                </div>
                <div class="col-md-4 text-end">
                    <h5>Rp {{ number_format($penjualan->subtotal_nilai, 0, ',', '.') }}</h5>
                    <h5>Rp {{ number_format($penjualan->ppn, 0, ',', '.') }}</h5>
                    <h4><strong>Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}</strong></h4>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8 text-end">
                    <h5 class="text-success"><strong>Keuntungan Kotor:</strong></h5>
                </div>
                <div class="col-md-4 text-end">
                    <h5 class="text-success"><strong>Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</strong></h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection