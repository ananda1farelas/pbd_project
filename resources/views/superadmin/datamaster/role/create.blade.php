@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3>➕ Tambah Role</h3>

    <form action="{{ route('role.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama_role" class="form-label">Nama Role</label>
            <input type="text" name="nama_role" id="nama_role" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">💾 Simpan</button>
        <a href="{{ route('role.index') }}" class="btn btn-secondary">↩️ Kembali</a>
    </form>
</div>
@endsection
