@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">👥 Daftar User</h3>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tombol Tambah --}}
    <div class="mb-3 text-end">
        <a href="{{ route('user.create') }}" class="btn btn-success">➕ Tambah User</a>
    </div>

    {{-- Tabel Data --}}
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $u)
                <tr>
                    <td>{{ $u->iduser }}</td>
                    <td>{{ $u->username }}</td>
                    <td>{{ $u->nama_role ?? '-' }}</td>
                    <td>
                        <a href="{{ route('user.edit', $u->iduser) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                        <a href="{{ route('user.delete', $u->iduser) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin mau hapus user ini?')">🗑️ Hapus</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data user</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
