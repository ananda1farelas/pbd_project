@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3>✏️ Edit Barang</h3>

    <form action="{{ route('barang.update', $barang->idbarang) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis</label>
            <input type="text" name="jenis" id="jenis" 
                class="form-control" value="{{ $barang->jenis }}" required>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Barang</label>
            <input type="text" name="nama" id="nama" 
                class="form-control" value="{{ $barang->nama }}" required>
        </div>

        <div class="mb-3">
            <label for="harga_satuan" class="form-label">Harga Satuan</label>
            <input type="number" name="harga_satuan" id="harga_satuan" 
            class="form-control" value="{{ $barang->harga_satuan }}" required>
        </div>

        <div class="mb-3">
            <label for="idsatuan" class="form-label">Satuan</label>
            <select name="idsatuan" id="idsatuan" class="form-select" required>
                @foreach ($satuan as $s)
                    <option value="{{ $s->idsatuan }}" 
                        {{ $barang->idsatuan == $s->idsatuan ? 'selected' : '' }}>
                        {{ $s->nama_satuan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="1" {{ $barang->status == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $barang->status != 1 ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">🔄 Update</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">↩️ Kembali</a>
    </form>

</div>
@endsection
