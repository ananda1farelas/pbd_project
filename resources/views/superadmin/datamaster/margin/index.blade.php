@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">💰 Daftar Margin Penjualan</h3>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tombol Filter --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('superadmin.margin.index', ['filter' => 'aktif']) }}" 
               class="btn {{ $filter == 'aktif' ? 'btn-primary' : 'btn-outline-primary' }}">
                Margin Aktif
            </a>
            <a href="{{ route('superadmin.margin.index', ['filter' => 'semua']) }}" 
               class="btn {{ $filter == 'semua' ? 'btn-primary' : 'btn-outline-primary' }}">
                Semua Margin
            </a>
        </div>
        <a href="{{ route('superadmin.margin.create') }}" class="btn btn-success">➕ Tambah Margin</a>
    </div>

    {{-- Tabel Data --}}
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID Margin</th>
                <th>Persen (%)</th>
                <th>Status</th>
                <th>Dibuat Oleh</th>
                <th>Dibuat Pada</th>
                <th>Terakhir Update</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($margin as $m)
                <tr>
                    <td>{{ $m->idmargin_penjualan }}</td>
                    <td>{{ $m->persen }}%</td>
                    <td>{{ $m->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                    <td>{{ $m->dibuat_oleh ?? '-' }}</td>
                    <td>{{ $m->created_at }}</td>
                    <td>{{ $m->updated_at }}</td>
                    <td>
                        <a href="{{ route('superadmin.margin.edit', $m->idmargin_penjualan) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                        <a href="{{ route('superadmin.margin.delete', $m->idmargin_penjualan) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin mau hapus margin ini?')">🗑️ Hapus</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data margin</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
