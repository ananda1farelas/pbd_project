@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3>✏️ Edit Role</h3>

    <form action="{{ route('role.update', $role->idrole) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama_role" class="form-label">Nama Role</label>
            <input type="text" name="nama_role" id="nama_role" class="form-control" 
                   value="{{ $role->nama_role }}" required>
        </div>

        <button type="submit" class="btn btn-primary">🔄 Update</button>
        <a href="{{ route('role.index') }}" class="btn btn-secondary">↩️ Kembali</a>
    </form>
</div>
@endsection
