@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Tambah Penerimaan Barang</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary">
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

    <form action="{{ route('penerimaan.store') }}" method="POST" id="formPenerimaan">
        @csrf

        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Penerimaan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pilih Pengadaan <span class="text-danger">*</span></label>
                            <select name="idpengadaan" id="selectPengadaan" class="form-select" required>
                                <option value="">-- Pilih Pengadaan --</option>
                                @foreach($pengadaan as $pg)
                                    <option value="{{ $pg->idpengadaan }}">
                                        {{ $pg->idpengadaan }} - {{ $pg->nama_vendor }} 
                                        ({{ date('d/m/Y', strtotime($pg->timestamp)) }}) 
                                        - Rp {{ number_format($pg->total_nilai, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih pengadaan yang akan diterima barangnya</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Penerimaan</label>
                            <input type="text" class="form-control" value="{{ date('d/m/Y H:i') }}" readonly>
                            <small class="text-muted">Otomatis diambil dari sistem</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="cardBarang" style="display: none;">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Daftar Barang yang Diterima</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Masukkan jumlah barang yang diterima. Kosongkan atau isi 0 jika barang tidak diterima.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th class="text-center">Jumlah Pesan</th>
                                <th class="text-center">Sudah Diterima</th>
                                <th class="text-center">Sisa Belum Terima</th>
                                <th class="text-center">Terima Sekarang</th>
                            </tr>
                        </thead>
                        <tbody id="barangContainer">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-end" id="btnSubmitContainer" style="display: none;">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Simpan Penerimaan
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
// Fungsi format rupiah
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Event listener untuk select pengadaan
$('#selectPengadaan').change(function() {
    const idpengadaan = $(this).val();
    
    if (!idpengadaan) {
        $('#cardBarang').hide();
        $('#btnSubmitContainer').hide();
        return;
    }
    
    // Loading state
    $('#barangContainer').html('<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>');
    $('#cardBarang').show();
    
    // Fetch data barang via AJAX
    $.ajax({
        url: "{{ route('penerimaan.getBarangPengadaan', ':idpengadaan') }}".replace(':idpengadaan', idpengadaan),
        method: 'GET',
        success: function(response) {
            if (response.success && response.data.length > 0) {
                let html = '';
                let index = 0;
                
                response.data.forEach(function(item) {
                    html += `
                        <tr>
                            <td>
                                ${item.nama_barang}
                                <input type="hidden" name="barang[${index}][idbarang]" value="${item.idbarang}">
                            </td>
                            <td>${item.nama_satuan}</td>
                            <td class="text-center"><strong>${item.jumlah_pesan}</strong></td>
                            <td class="text-center">${item.total_diterima}</td>
                            <td class="text-center">
                                <span class="badge bg-warning">${item.sisa_belum_terima}</span>
                            </td>
                            <td>
                                <input type="number" 
                                       name="barang[${index}][jumlah_terima]" 
                                       class="form-control text-center" 
                                       min="0" 
                                       max="${item.sisa_belum_terima}"
                                       placeholder="0"
                                       required>
                                <small class="text-muted">Max: ${item.sisa_belum_terima}</small>
                            </td>
                        </tr>
                    `;
                    index++;
                });
                
                $('#barangContainer').html(html);
                $('#btnSubmitContainer').show();
            } else {
                $('#barangContainer').html('<tr><td colspan="6" class="text-center text-warning">Semua barang sudah diterima lengkap</td></tr>');
                $('#btnSubmitContainer').hide();
            }
        },
        error: function(xhr) {
            $('#barangContainer').html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data barang</td></tr>');
            $('#btnSubmitContainer').hide();
            alert('Error: ' + (xhr.responseJSON?.message || 'Gagal memuat data'));
        }
    });
});

// Validasi sebelum submit
$('#formPenerimaan').submit(function(e) {
    let adaBarang = false;
    
    $('input[name*="jumlah_terima"]').each(function() {
        if ($(this).val() > 0) {
            adaBarang = true;
            return false;
        }
    });
    
    if (!adaBarang) {
        e.preventDefault();
        alert('Minimal harus ada 1 barang yang diterima!');
        return false;
    }
});
</script>
@endsection