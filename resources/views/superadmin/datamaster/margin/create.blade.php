@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3>➕ Tambah Margin Penjualan</h3>

    <form action="{{ route('superadmin.margin.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="persen" class="form-label">Persentase Margin (%)</label>
            <input type="number" step="0.01" name="persen" id="persen" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="iduser" class="form-label">Dibuat Oleh</label>
            <select name="iduser" id="iduser" class="form-select" required>
                <option value="">-- Pilih User --</option>
                @foreach ($user as $u)
                    <option value="{{ $u->iduser }}">{{ $u->username }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">💾 Simpan</button>
        <a href="{{ route('superadmin.margin.index') }}" class="btn btn-secondary">↩️ Kembali</a>
    </form>
</div>
@endsection
