@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Tambah Penjualan Baru</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('superadmin.penjualan.index') }}" class="btn btn-secondary">
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

    <form action="{{ route('superadmin.penjualan.store') }}" method="POST" id="formPenjualan">
        @csrf

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi Penjualan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Margin Penjualan Aktif</label>
                        <input type="text" class="form-control" value="{{ $marginAktif->persen }}%" readonly>
                        <small class="text-muted">Margin otomatis diambil dari sistem</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Penjualan</label>
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
                                    <option value="{{ $item->idbarang }}" 
                                            data-harga="{{ $item->harga_satuan }}"
                                            data-stok="{{ $item->stok_tersedia }}"
                                            data-satuan="{{ $item->nama_satuan }}">
                                        {{ $item->nama_barang }} - {{ $item->nama_satuan }} 
                                        (Stok: {{ $item->stok_tersedia }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="barang[0][jumlah]" class="form-control jumlah-input" min="1" required placeholder="0">
                            <small class="text-muted stok-info"></small>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Harga Asli</label>
                            <input type="text" class="form-control harga-asli" readonly placeholder="Rp 0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Harga Jual</label>
                            <input type="text" class="form-control harga-jual" readonly placeholder="Rp 0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Subtotal</label>
                            <input type="text" class="form-control subtotal-item" readonly placeholder="Rp 0">
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
                <h5 class="mb-0">Total Penjualan</h5>
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
                <i class="fas fa-save"></i> Simpan Penjualan
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

function hitungSubtotalItem(row) {
    const idbarang = row.find('.barang-select').val();
    const jumlah = parseInt(row.find('.jumlah-input').val()) || 0;
    const hargaAsli = parseFloat(row.find('.barang-select option:selected').data('harga')) || 0;
    const stok = parseInt(row.find('.barang-select option:selected').data('stok')) || 0;

    if (!idbarang || jumlah === 0) {
        row.find('.harga-asli').val('');
        row.find('.harga-jual').val('');
        row.find('.subtotal-item').val('');
        hitungTotalPenjualan();
        return;
    }

    // Update info stok
    row.find('.stok-info').text('Max: ' + stok);
    row.find('.jumlah-input').attr('max', stok);

    // Validasi stok
    if (jumlah > stok) {
        alert('Jumlah melebihi stok tersedia!');
        row.find('.jumlah-input').val(stok);
        return;
    }

    // Tampilkan harga asli
    row.find('.harga-asli').val(formatRupiah(hargaAsli));

    // Hitung harga jual via AJAX (otomatis pakai margin aktif)
    $.ajax({
        url: "{{ route('superadmin.penjualan.hitungHarga') }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            idbarang: idbarang  // ✅ Cuma kirim idbarang aja
        },
        success: function(response) {
            if (response.success) {
                const hargaJual = response.harga_jual;
                const subtotal = hargaJual * jumlah;

                row.find('.harga-jual').val(formatRupiah(hargaJual));
                row.find('.subtotal-item').val(formatRupiah(subtotal));

                hitungTotalPenjualan();
            }
        },
        error: function() {
            alert('Gagal menghitung harga jual');
        }
    });
}

// Fungsi hitung total penjualan
function hitungTotalPenjualan() {
    let totalSubtotal = 0;

    $('.barang-item').each(function() {
        const subtotalText = $(this).find('.subtotal-item').val().replace(/[^0-9]/g, '');
        const subtotal = parseInt(subtotalText) || 0;
        totalSubtotal += subtotal;
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

// Event listener untuk select margin (reset form jika ganti margin)
$('#selectMargin').change(function() {
    if ($('.barang-item').length > 1 || $('.barang-select').val()) {
        if (confirm('Mengganti margin akan mereset barang yang sudah dipilih. Lanjutkan?')) {
            // Reset semua barang
            $('#barangContainer').html(`
                <div class="row mb-3 barang-item">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                        <select name="barang[0][idbarang]" class="form-select barang-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barang as $item)
                                <option value="{{ $item->idbarang }}" 
                                        data-harga="{{ $item->harga_satuan }}"
                                        data-stok="{{ $item->stok_tersedia }}"
                                        data-satuan="{{ $item->nama_satuan }}">
                                    {{ $item->nama_barang }} - {{ $item->nama_satuan }} 
                                    (Stok: {{ $item->stok_tersedia }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="barang[0][jumlah]" class="form-control jumlah-input" min="1" required placeholder="0">
                        <small class="text-muted stok-info"></small>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Harga Asli</label>
                        <input type="text" class="form-control harga-asli" readonly placeholder="Rp 0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Harga Jual</label>
                        <input type="text" class="form-control harga-jual" readonly placeholder="Rp 0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Subtotal</label>
                        <input type="text" class="form-control subtotal-item" readonly placeholder="Rp 0">
                    </div>
                </div>
            `);
            itemCount = 1;
            hitungTotalPenjualan();
        } else {
            // Cancel, kembalikan margin ke nilai sebelumnya
            $(this).val($(this).data('previous'));
        }
    }
    $(this).data('previous', $(this).val());
});

// Tambah barang baru
$('#btnTambahBarang').click(function() {

    const newItem = `
        <div class="row mb-3 barang-item">
            <div class="col-md-4">
                <select name="barang[${itemCount}][idbarang]" class="form-select barang-select" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barang as $item)
                        <option value="{{ $item->idbarang }}" 
                                data-harga="{{ $item->harga_satuan }}"
                                data-stok="{{ $item->stok_tersedia }}"
                                data-satuan="{{ $item->nama_satuan }}">
                            {{ $item->nama_barang }} - {{ $item->nama_satuan }} 
                            (Stok: {{ $item->stok_tersedia }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="barang[${itemCount}][jumlah]" class="form-control jumlah-input" min="1" required placeholder="0">
                <small class="text-muted stok-info"></small>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control harga-asli" readonly placeholder="Rp 0">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control harga-jual" readonly placeholder="Rp 0">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control subtotal-item" readonly placeholder="Rp 0">
            </div>
        </div>
    `;

    $('#barangContainer').append(newItem);
    itemCount++;
});

// Hapus barang
$(document).on('click', '.btn-hapus-barang', function() {
    $(this).closest('.barang-item').remove();
    hitungTotalPenjualan();
});

// Validasi sebelum submit
$('#formPenjualan').submit(function(e) {
    if ($('.barang-item').length === 0) {
        e.preventDefault();
        alert('Minimal harus ada 1 barang!');
        return false;
    }

    let adaBarang = false;
    $('.barang-select').each(function() {
        if ($(this).val()) {
            adaBarang = true;
            return false;
        }
    });

    if (!adaBarang) {
        e.preventDefault();
        alert('Minimal harus ada 1 barang yang dipilih!');
        return false;
    }
});
</script>
@endsection