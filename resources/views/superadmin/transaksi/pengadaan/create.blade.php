@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Tambah Pengadaan Baru</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengadaan.store') }}" method="POST" id="formPengadaan">
        @csrf

        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Pengadaan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pilih Vendor <span class="text-danger">*</span></label>
                            <select name="vendor_idvendor" class="form-select" required>
                                <option value="">-- Pilih Vendor --</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->idvendor }}">
                                        {{ $vendor->nama_vendor }} ({{ $vendor->jenis_vendor }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengadaan</label>
                            <input type="text" class="form-control" value="{{ date('d/m/Y H:i') }}" readonly>
                            <small class="text-muted">Otomatis diambil dari sistem</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Daftar Barang</h5>
            </div>
            <div class="card-body">
                <div id="barangContainer">
                    <!-- Item barang pertama -->
                    <div class="row mb-3 barang-item">
                        <div class="col-md-4">
                            <label class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                            <select name="barang[0][idbarang]" class="form-select barang-select" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->idbarang }}" data-harga="{{ $item->harga_satuan }}">
                                        {{ $item->nama_barang }} - {{ $item->nama_satuan }} (Rp {{ number_format($item->harga_satuan, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="barang[0][jumlah]" class="form-control jumlah-input" min="1" required placeholder="0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Harga Satuan</label>
                            <input type="text" class="form-control harga-satuan" readonly placeholder="Rp 0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Subtotal</label>
                            <input type="text" class="form-control subtotal-item" readonly placeholder="Rp 0">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="button" class="btn btn-secondary btn-sm w-100" disabled>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-success btn-sm" id="btnTambahBarang">
                    <i class="fas fa-plus-circle"></i> Tambah Barang
                </button>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Total Pengadaan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 text-end">
                        <h5>Subtotal:</h5>
                        <h5>PPN (10%):</h5>
                        <h4><strong>Total Nilai:</strong></h4>
                    </div>
                    <div class="col-md-4 text-end">
                        <h5 id="displaySubtotal">Rp 0</h5>
                        <h5 id="displayPPN">Rp 0</h5>
                        <h4><strong id="displayTotal">Rp 0</strong></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Simpan Pengadaan
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
let itemCount = 1;

// Fungsi format rupiah
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Fungsi hitung subtotal item
function hitungSubtotalItem(row) {
    const harga = parseFloat(row.find('.barang-select option:selected').data('harga')) || 0;
    const jumlah = parseInt(row.find('.jumlah-input').val()) || 0;
    const subtotal = harga * jumlah;
    
    row.find('.harga-satuan').val(formatRupiah(harga));
    row.find('.subtotal-item').val(formatRupiah(subtotal));
    
    hitungTotalPengadaan();
}

// Fungsi hitung total pengadaan
function hitungTotalPengadaan() {
    let totalSubtotal = 0;
    
    $('.barang-item').each(function() {
        const harga = parseFloat($(this).find('.barang-select option:selected').data('harga')) || 0;
        const jumlah = parseInt($(this).find('.jumlah-input').val()) || 0;
        totalSubtotal += (harga * jumlah);
    });
    
    const ppn = totalSubtotal * 0.1;
    const total = totalSubtotal + ppn;
    
    $('#displaySubtotal').text(formatRupiah(totalSubtotal));
    $('#displayPPN').text(formatRupiah(ppn));
    $('#displayTotal').text(formatRupiah(total));
}

// Event listener untuk select barang
$(document).on('change', '.barang-select', function() {
    hitungSubtotalItem($(this).closest('.barang-item'));
});

// Event listener untuk input jumlah
$(document).on('input', '.jumlah-input', function() {
    hitungSubtotalItem($(this).closest('.barang-item'));
});

// Tambah barang baru
$('#btnTambahBarang').click(function() {
    const newItem = `
        <div class="row mb-3 barang-item">
            <div class="col-md-4">
                <select name="barang[${itemCount}][idbarang]" class="form-select barang-select" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barang as $item)
                        <option value="{{ $item->idbarang }}" data-harga="{{ $item->harga_satuan }}">
                            {{ $item->nama_barang }} - {{ $item->nama_satuan }} (Rp {{ number_format($item->harga_satuan, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="barang[${itemCount}][jumlah]" class="form-control jumlah-input" min="1" required placeholder="0">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control harga-satuan" readonly placeholder="Rp 0">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control subtotal-item" readonly placeholder="Rp 0">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm w-100 btn-hapus-barang">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    $('#barangContainer').append(newItem);
    itemCount++;
});

// Hapus barang
$(document).on('click', '.btn-hapus-barang', function() {
    $(this).closest('.barang-item').remove();
    hitungTotalPengadaan();
});

// Validasi sebelum submit
$('#formPengadaan').submit(function(e) {
    if ($('.barang-item').length === 0) {
        e.preventDefault();
        alert('Minimal harus ada 1 barang!');
        return false;
    }
});
</script>
@endsection