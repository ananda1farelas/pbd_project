@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Summary Kartu Stok</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('kartustok.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Ringkasan Stok Barang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th class="text-center">Total Masuk</th>
                            <th class="text-center">Total Keluar</th>
                            <th class="text-center">Stok Akhir</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $no = 1;
                            $grandTotalMasuk = 0;
                            $grandTotalKeluar = 0;
                            $grandStokAkhir = 0;
                        @endphp
                        @forelse($summary as $item)
                        @php
                            $grandTotalMasuk += $item->total_masuk;
                            $grandTotalKeluar += $item->total_keluar;
                            $grandStokAkhir += $item->stok_akhir;
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>
                                <a href="{{ route('kartustok.show', $item->idbarang) }}" class="text-decoration-none">
                                    {{ $item->nama_barang }}
                                </a>
                            </td>
                            <td>{{ $item->nama_satuan }}</td>
                            <td class="text-center text-success fw-bold">{{ $item->total_masuk }}</td>
                            <td class="text-center text-danger fw-bold">{{ $item->total_keluar }}</td>
                            <td class="text-center">
                                <h5 class="mb-0">
                                    @if($item->stok_akhir == 0)
                                        <span class="badge bg-danger">{{ $item->stok_akhir }}</span>
                                    @elseif($item->stok_akhir < 10)
                                        <span class="badge bg-warning">{{ $item->stok_akhir }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $item->stok_akhir }}</span>
                                    @endif
                                </h5>
                            </td>
                            <td class="text-center">
                                @if($item->stok_akhir == 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($item->stok_akhir < 10)
                                    <span class="badge bg-warning">Menipis</span>
                                @else
                                    <span class="badge bg-success">Aman</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th colspan="3" class="text-end">TOTAL:</th>
                            <th class="text-center text-success">{{ $grandTotalMasuk }}</th>
                            <th class="text-center text-danger">{{ $grandTotalKeluar }}</th>
                            <th class="text-center">{{ $grandStokAkhir }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection