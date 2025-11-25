@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3>➕ Tambah Barang</h3>

    <form action="{{ route('barang.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="jenis" class="form-label">ID Barang</label>
            <input type="text" name="idbarang" id="idbarang" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis</label>
            <input type="text" name="jenis" id="jenis" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Barang</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="harga_satuan" class="form-label">Harga Barang</label>
            <input type="text-number" name="harga_satuan" id="harga_satuan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="idsatuan" class="form-label">Satuan</label>
            <select name="idsatuan" id="idsatuan" class="form-select" required>
                <option value="">-- Pilih Satuan --</option>
                @foreach ($satuan as $s)
                    <option value="{{ $s->idsatuan }}">{{ $s->nama_satuan }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">💾 Simpan</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">↩️ Kembali</a>
    </form>
</div>
@endsection
