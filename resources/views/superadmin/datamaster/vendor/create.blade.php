@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3>➕ Tambah Vendor</h3>

    <form action="{{ route('vendor.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama_vendor" class="form-label">Nama Vendor</label>
            <input type="text" name="nama_vendor" id="nama_vendor" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="badan_hukum" class="form-label">Badan Hukum</label>
            <input type="text" name="badan_hukum" id="badan_hukum" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">💾 Simpan</button>
        <a href="{{ route('vendor.index') }}" class="btn btn-secondary">↩️ Kembali</a>
    </form>
</div>
@endsection
