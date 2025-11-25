@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">📏 Daftar Satuan</h3>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tombol Filter --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('satuan.index', ['filter' => 'aktif']) }}" 
               class="btn {{ $filter == 'aktif' ? 'btn-primary' : 'btn-outline-primary' }}">
                Satuan Aktif
            </a>
            <a href="{{ route('satuan.index', ['filter' => 'semua']) }}" 
               class="btn {{ $filter == 'semua' ? 'btn-primary' : 'btn-outline-primary' }}">
                Semua Satuan
            </a>
        </div>
        <a href="{{ route('satuan.create') }}" class="btn btn-success">➕ Tambah Satuan</a>
    </div>

    {{-- Tabel Data --}}
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama Satuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($satuan as $s)
                <tr>
                    <td>{{ $s->idsatuan }}</td>
                    <td>{{ $s->nama_satuan }}</td>
                    <td>{{ $s->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                    <td>
                        <a href="{{ route('satuan.edit', $s->idsatuan) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                        <a href="{{ route('satuan.delete', $s->idsatuan) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin mau hapus satuan ini?')">🗑️ Hapus</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data satuan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
