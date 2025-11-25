@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3>✏️ Edit Vendor</h3>

    <form action="{{ route('vendor.update', $vendor->idvendor) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama_vendor" class="form-label">Nama Vendor</label>
            <input type="text" name="nama_vendor" id="nama_vendor" class="form-control"
                   value="{{ $vendor->nama_vendor }}" required>
        </div>

        <div class="mb-3">
            <label for="badan_hukum" class="form-label">Badan Hukum</label>
            <select name="badan_hukum" id="badan_hukum" class="form-select" required>
                <option value="Y" {{ $vendor->jenis_vendor == 'Y' ? 'selected' : '' }}>Berbadan Hukum</option>
                <option value="N" {{ $vendor->jenis_vendor == 'N' ? 'selected' : '' }}>Tidak Berbadan Hukum</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="1" {{ $vendor->status == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $vendor->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">🔄 Update</button>
        <a href="{{ route('vendor.index') }}" class="btn btn-secondary">↩️ Kembali</a>
    </form>
</div>
@endsection
