@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Detail Pengadaan</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary">
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
            <h5 class="mb-0">Informasi Pengadaan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">ID Pengadaan</th>
                            <td>: {{ $pengadaan->idpengadaan }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: {{ date('d/m/Y H:i', strtotime($pengadaan->timestamp)) }}</td>
                        </tr>
                        <tr>
                            <th>Vendor</th>
                            <td>: 
                                @php
                                    $vendor = DB::select("SELECT nama_vendor FROM vendor WHERE idvendor = ?", [$pengadaan->vendor_idvendor]);
                                @endphp
                                {{ $vendor[0]->nama_vendor ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">User</th>
                            <td>: 
                                @php
                                    $user = DB::select("SELECT username FROM user WHERE iduser = ?", [$pengadaan->user_iduser]);
                                @endphp
                                {{ $user[0]->username ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($pengadaan->status == 'A')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($pengadaan->status == 'P')
                                    <span class="badge bg-warning">Proses</span>
                                @elseif($pengadaan->status == 'S')
                                    <span class="badge bg-info">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Aksi</th>
                            <td>:
                                @if($pengadaan->status == 'A')
                                    <form action="{{ route('pengadaan.cancel', $pengadaan->idpengadaan) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Batalkan pengadaan ini?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-ban"></i> Cancel
                                        </button>
                                    </form>
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
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($details as $detail)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $detail->nama_barang }}</td>
                            <td>{{ $detail->nama_satuan }}</td>
                            <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $detail->jumlah }}</td>
                            <td class="text-end">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Total Pengadaan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 text-end">
                    <h5>Subtotal:</h5>
                    <h5>PPN (10%):</h5>
                    <h4><strong>Total Nilai:</strong></h4>
                </div>
                <div class="col-md-4 text-end">
                    <h5>Rp {{ number_format($pengadaan->subtotal_nilai, 0, ',', '.') }}</h5>
                    <h5>Rp {{ number_format($pengadaan->ppn, 0, ',', '.') }}</h5>
                    <h4><strong>Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}</strong></h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection