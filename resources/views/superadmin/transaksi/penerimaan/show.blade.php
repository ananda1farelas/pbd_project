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
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Penerimaan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">ID Penerimaan</th>
                            <td>: <strong>{{ $penerimaan->idpenerimaan }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: {{ date('d/m/Y H:i', strtotime($penerimaan->created_at)) }}</td>
                        </tr>
                        <tr>
                            <th>ID Pengadaan</th>
                            <td>: <span class="badge bg-secondary">{{ $penerimaan->idpengadaan }}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Vendor</th>
                            <td>: <strong>{{ $pengadaan->nama_vendor ?? '-' }}</strong></td>
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
                            <th>Status Penerimaan</th>
                            <td>: 
                                @if($penerimaan->status == 'L')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Lengkap
                                    </span>
                                @elseif($penerimaan->status == 'S')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock"></i> Sebagian
                                    </span>
                                @elseif($penerimaan->status == 'B')
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-file"></i> Belum Diterima (Dokumentasi)
                                    </span>
                                @else
                                    <span class="badge bg-dark">{{ $penerimaan->status }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status Pengadaan</th>
                            <td>: 
                                @if(isset($pengadaan->status))
                                    @if($pengadaan->status == 'S')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-double"></i> Selesai
                                        </span>
                                    @elseif($pengadaan->status == 'P')
                                        <span class="badge bg-info">
                                            <i class="fas fa-sync"></i> Proses Penerimaan
                                        </span>
                                    @elseif($pengadaan->status == 'A')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-check"></i> Approved
                                        </span>
                                    @elseif($pengadaan->status == 'C')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times"></i> Cancelled
                                        </span>
                                    @else
                                        <span class="badge bg-dark">{{ $pengadaan->status }}</span>
                                    @endif
                                @else
                                    -
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
            <h5 class="mb-0"><i class="fas fa-box-open"></i> Detail Barang yang Diterima</h5>
        </div>
        <div class="card-body">
            @if(count($details) > 0)
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
                            @php 
                                $no = 1; 
                                $total = 0;
                            @endphp
                            @foreach($details as $detail)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $detail->nama_barang }}</td>
                                <td class="text-center">
                                    <strong>{{ $detail->jumlah_terima }}</strong>
                                </td>
                                <td class="text-end">Rp {{ number_format($detail->harga_satuan_terima, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($detail->sub_total_terima, 0, ',', '.') }}</td>
                            </tr>
                            @php
                                $total += $detail->sub_total_terima;
                            @endphp
                            @endforeach
                            <tr class="table-info">
                                <td colspan="4" class="text-end"><strong>TOTAL NILAI PENERIMAAN</strong></td>
                                <td class="text-end"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Tidak ada barang yang diterima pada penerimaan ini (Dokumentasi saja)
                </div>
            @endif
        </div>
    </div>

    @if($progress)
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Progress Penerimaan Pengadaan {{ $penerimaan->idpengadaan }}</h5>
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
                            <th class="text-center" width="250">Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($progress as $item)
                        <tr>
                            <td>{{ $item->nama_barang }}</td>
                            <td class="text-center"><strong>{{ $item->jumlah_pesan }}</strong></td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $item->total_diterima }}</span>
                            </td>
                            <td class="text-center">
                                @if($item->sisa_belum_terima == 0)
                                    <span class="badge bg-success">{{ $item->sisa_belum_terima }}</span>
                                @else
                                    <span class="badge bg-warning">{{ $item->sisa_belum_terima }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status_barang == 'Lengkap')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Lengkap
                                    </span>
                                @elseif($item->status_barang == 'Partial')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock"></i> Sebagian
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-hourglass-start"></i> Belum Diterima
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $persen = $item->jumlah_pesan > 0 ? ($item->total_diterima / $item->jumlah_pesan) * 100 : 0;
                                @endphp
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar {{ $persen == 100 ? 'bg-success' : ($persen > 0 ? 'bg-warning' : 'bg-secondary') }}" 
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
            
            @php
                $totalPesan = array_sum(array_column($progress, 'jumlah_pesan'));
                $totalDiterima = array_sum(array_column($progress, 'total_diterima'));
                $persenTotal = $totalPesan > 0 ? ($totalDiterima / $totalPesan) * 100 : 0;
            @endphp
            
            <div class="alert alert-info mt-3">
                <div class="row">
                    <div class="col-md-8">
                        <strong><i class="fas fa-info-circle"></i> Progress Keseluruhan Pengadaan:</strong>
                        <div class="progress mt-2" style="height: 30px;">
                            <div class="progress-bar {{ $persenTotal == 100 ? 'bg-success' : 'bg-warning' }}" 
                                 role="progressbar" 
                                 style="width: {{ $persenTotal }}%">
                                <strong>{{ number_format($persenTotal, 1) }}%</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <p class="mb-1"><strong>Total Pesan:</strong> {{ $totalPesan }}</p>
                        <p class="mb-1"><strong>Total Diterima:</strong> {{ $totalDiterima }}</p>
                        <p class="mb-0"><strong>Sisa:</strong> {{ $totalPesan - $totalDiterima }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection