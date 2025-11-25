@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3>✏️ Edit User</h3>

    <form action="{{ route('user.update', data_get($user, 'iduser')) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" 
                   value="{{ data_get($user, 'username') }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (kosongkan jika tidak diganti)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <div class="mb-3">
            <label for="idrole" class="form-label">Role</label>
            <select name="idrole" id="idrole" class="form-select" required>
                @foreach ($roles as $r)
                    <option value="{{ $r->idrole }}" {{ data_get($user, 'idrole') == $r->idrole ? 'selected' : '' }}>
                        {{ $r->nama_role }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">🔄 Update</button>
        <a href="{{ route('user.index') }}" class="btn btn-secondary">↩️ Kembali</a>
    </form>
</div>
@endsection
