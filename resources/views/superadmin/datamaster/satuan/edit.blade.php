@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3>✏️ Edit Satuan</h3>

    <form action="{{ route('satuan.update', $satuan->idsatuan) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama_satuan" class="form-label">Nama Satuan</label>
            <input type="text" name="nama_satuan" id="nama_satuan" class="form-control"
                   value="{{ $satuan->nama_satuan }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="1" {{ $satuan->status == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $satuan->status != 1 ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">🔄 Update</button>
        <a href="{{ route('satuan.index') }}" class="btn btn-secondary">↩️ Kembali</a>
    </form>
</div>
@endsection
