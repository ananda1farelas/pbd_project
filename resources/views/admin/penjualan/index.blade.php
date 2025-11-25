@extends('layout.admin')

@section('title', 'Data Penjualan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Penjualan</h2>
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

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-receipt"></i> Total Transaksi
                    </h6>
                    <h3 class="mb-0 text-primary">{{ number_format(count($penjualan)) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-cash-stack"></i> Total Pendapatan
                    </h6>
                    <h3 class="mb-0 text-success">
                        Rp {{ number_format(array_sum(array_column($penjualan, 'total_nilai')), 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-calculator"></i> Rata-rata Transaksi
                    </h6>
                    <h3 class="mb-0 text-info">
                        Rp {{ count($penjualan) > 0 ? number_format(array_sum(array_column($penjualan, 'total_nilai')) / count($penjualan), 0, ',', '.') : 0 }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-percent"></i> Total PPN
                    </h6>
                    <h3 class="mb-0 text-warning">
                        Rp {{ number_format(array_sum(array_column($penjualan, 'ppn')), 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="penjualanTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Margin</th>
                            <th>Subtotal</th>
                            <th>PPN</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualan as $item)
                            <tr>
                                <td>
                                    <span class="badge bg-dark">
                                        #{{ $item->idpenjualan }}
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar3"></i>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i>
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <i class="bi bi-person-circle"></i>
                                    {{ $item->username }}
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ number_format($item->margin, 2) }}%
                                    </span>
                                </td>
                                <td>Rp {{ number_format($item->subtotal_nilai, 0, ',', '.') }}</td>
                                <td>
                                    <span class="text-warning">
                                        Rp {{ number_format($item->ppn, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-success">
                                        Rp {{ number_format($item->total_nilai, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <a href="{{ route('admin.penjualan.show', $item->idpenjualan) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        <p class="mb-0">Belum ada data penjualan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($penjualan) > 0)
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">Total:</td>
                                <td>Rp {{ number_format(array_sum(array_column($penjualan, 'subtotal_nilai')), 0, ',', '.') }}</td>
                                <td class="text-warning">
                                    Rp {{ number_format(array_sum(array_column($penjualan, 'ppn')), 0, ',', '.') }}
                                </td>
                                <td class="text-success">
                                    Rp {{ number_format(array_sum(array_column($penjualan, 'total_nilai')), 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#penjualanTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [[1, 'desc']], // Sort by date
            columnDefs: [
                { orderable: false, targets: 7 } // Disable sort on action column
            ]
        });
    });
</script>
@endpush
@endsection