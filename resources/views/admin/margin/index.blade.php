@extends('layout.admin')

@section('title', 'Margin Penjualan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Margin Penjualan</h2>
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
                <table class="table table-hover" id="marginTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Persentase Margin</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Tanggal Dibuat</th>
                            <th>Terakhir Diupdate</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($margin as $item)
                            <tr>
                                <td>{{ $item->idmargin_penjualan }}</td>
                                <td>
                                    <span class="badge bg-primary fs-6">
                                        <i class="bi bi-percent"></i> {{ number_format($item->persen, 2) }}%
                                    </span>
                                </td>
                                <td>
                                    @if($item->status == 1 || strtolower($item->status) == 'aktif')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-x-circle"></i> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <i class="bi bi-person-circle"></i> {{ $item->dibuat_oleh }}
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-plus"></i>
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check"></i>
                                        {{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.margin.show', $item->idmargin_penjualan) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1"></i>
                                        <p class="mb-0 mt-2">Tidak ada data margin penjualan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-info-circle"></i> Informasi
                    </h6>
                    <p class="small mb-0">
                        Margin penjualan digunakan untuk menghitung harga jual berdasarkan persentase keuntungan dari harga satuan barang.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success bg-opacity-10 border-success">
                <div class="card-body">
                    <h6 class="text-success mb-2">
                        <i class="bi bi-check-circle"></i> Margin Aktif
                    </h6>
                    <h3 class="mb-0">
                        {{ collect($margin)->where('status', 1)->count() + collect($margin)->where('status', 'aktif')->count() }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-secondary bg-opacity-10 border-secondary">
                <div class="card-body">
                    <h6 class="text-secondary mb-2">
                        <i class="bi bi-x-circle"></i> Margin Nonaktif
                    </h6>
                    <h3 class="mb-0">
                        {{ collect($margin)->where('status', '!=', 1)->where('status', '!=', 'aktif')->count() }}
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#marginTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [[4, 'desc']], // Sort by created_at
            columnDefs: [
                { orderable: false, targets: 6 } // Disable sort on action column
            ]
        });
    });
</script>
@endpush
@endsection