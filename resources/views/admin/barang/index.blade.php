@extends('layout.admin')

@section('title', 'Data Barang')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Barang</h2>
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

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="barangTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Stok Tersedia</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barang as $item)
                            <tr>
                                <td>{{ $item->idbarang }}</td>
                                <td><strong>{{ $item->nama_barang }}</strong></td>
                                <td>{{ $item->jenis }}</td>
                                <td>{{ $item->nama_satuan }}</td>
                                <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $item->stok_tersedia > 10 ? 'bg-success' : ($item->stok_tersedia > 0 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $item->stok_tersedia }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->status == 1)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.barang.show', $item->idbarang) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">Tidak ada data barang</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#barangTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [[1, 'asc']]
        });
    });
</script>
@endpush
@endsection