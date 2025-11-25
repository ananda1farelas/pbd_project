@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">🏢 Daftar Vendor</h3>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tombol Filter --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('vendor.index', ['filter' => 'aktif']) }}" 
               class="btn {{ $filter == 'aktif' ? 'btn-primary' : 'btn-outline-primary' }}">
                Vendor Aktif
            </a>
            <a href="{{ route('vendor.index', ['filter' => 'semua']) }}" 
               class="btn {{ $filter == 'semua' ? 'btn-primary' : 'btn-outline-primary' }}">
                Semua Vendor
            </a>
        </div>
        <a href="{{ route('vendor.create') }}" class="btn btn-success">➕ Tambah Vendor</a>
    </div>

    {{-- Tabel Data --}}
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama Vendor</th>
                <th>Badan Hukum</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vendor as $v)
                <tr>
                    <td>{{ $v->idvendor }}</td>
                    <td>{{ $v->nama_vendor }}</td>
                    <td>{{ $v->jenis_vendor }}</td>
                    <td>{{ $v->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                    <td>
                        <a href="{{ route('vendor.edit', $v->idvendor) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                        <a href="{{ route('vendor.delete', $v->idvendor) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin mau hapus vendor ini?')">🗑️ Hapus</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data vendor</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
