@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">📦 Daftar Barang</h3>

    {{-- Notifikasi sukses / error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter + Search --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">

        {{-- Filter --}}
        <div>
            <a href="{{ route('barang.index', ['filter' => 'aktif']) }}" 
                class="btn {{ $filter == 'aktif' ? 'btn-primary' : 'btn-outline-primary' }}">
                Barang Aktif
            </a>
            <a href="{{ route('barang.index', ['filter' => 'semua']) }}" 
                class="btn {{ $filter == 'semua' ? 'btn-primary' : 'btn-outline-primary' }}">
                Semua Barang
            </a>
        </div>

        {{-- 🔍 FORM PENCARIAN (INI BAGIAN YANG ROSAK SEBELUMNYA) --}}
        <form action="{{ route('barang.index') }}" method="GET" class="d-flex gap-2">

            {{-- kirim filter supaya searching tidak hilangkan status aktif/semua --}}
            <input type="hidden" name="filter" value="{{ $filter }}">

            <input type="text" name="keyword" 
                   class="form-control" placeholder="Cari barang..."
                   value="{{ $keyword }}">

            <button class="btn btn-primary">Cari</button>
        </form>

        <a href="{{ route('barang.create') }}" class="btn btn-success">➕ Tambah Barang</a>
    </div>

    {{-- Tabel Barang --}}
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Jenis</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Status</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barang as $b)
                <tr>
                    <td>{{ $b->idbarang }}</td>

                    {{-- kategori hasil mapping --}}
                    <td>{{ $b->kategori }}</td>

                    <td>{{ $b->nama_barang }}</td>
                    <td>{{ $b->nama_satuan }}</td>
                    <td>{{ $b->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>

                    <td>Rp {{ number_format($b->harga_satuan, 0, ',', '.') }}</td>

                    <td>
                        <a href="{{ route('barang.edit', $b->idbarang) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                        <a href="{{ route('barang.delete', $b->idbarang) }}" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin mau hapus barang ini?')">
                           🗑️ Hapus
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data barang</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
