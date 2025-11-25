@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">👤 Daftar Role</h3>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tombol Tambah --}}
    <div class="mb-3 text-end">
        <a href="{{ route('role.create') }}" class="btn btn-success">➕ Tambah Role</a>
    </div>

    {{-- Tabel Data --}}
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID Role</th>
                <th>Nama Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($role as $r)
                <tr>
                    <td>{{ $r->idrole }}</td>
                    <td>{{ $r->nama_role }}</td>
                    <td>
                        <a href="{{ route('role.edit', $r->idrole) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                        <a href="{{ route('role.delete', $r->idrole) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin mau hapus role ini?')">🗑️ Hapus</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Belum ada data role</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
