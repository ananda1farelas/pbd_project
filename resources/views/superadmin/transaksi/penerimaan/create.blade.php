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
                                        @if(isset($pg->status_label))
                                            - [{{ $pg->status_label }}]
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih pengadaan yang akan diterima barangnya (Status: Approved/Proses)</small>
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
                    <strong>Catatan:</strong> 
                    <ul class="mb-0 mt-2">
                        <li>Isi jumlah barang yang diterima untuk setiap item</li>
                        <li>Kosongkan atau isi <strong>0</strong> jika barang tidak diterima saat ini</li>
                        <li>Penerimaan dengan jumlah 0 akan dicatat sebagai dokumentasi</li>
                    </ul>
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
                                <th class="text-center" width="200">Terima Sekarang</th>
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
                    // Tentukan badge color berdasarkan sisa
                    let badgeClass = 'bg-success';
                    if (item.sisa_belum_terima > 0) {
                        badgeClass = 'bg-warning';
                    }
                    if (item.sisa_belum_terima == item.jumlah_pesan) {
                        badgeClass = 'bg-danger';
                    }
                    
                    html += `
                        <tr>
                            <td>
                                ${item.nama_barang}
                                <input type="hidden" name="barang[${index}][idbarang]" value="${item.idbarang}">
                            </td>
                            <td>${item.nama_satuan}</td>
                            <td class="text-center"><strong>${item.jumlah_pesan}</strong></td>
                            <td class="text-center">
                                <span class="badge bg-info">${item.total_diterima}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge ${badgeClass}">${item.sisa_belum_terima}</span>
                            </td>
                            <td>
                                <input type="number" 
                                       name="barang[${index}][jumlah_terima]" 
                                       class="form-control text-center" 
                                       min="0" 
                                       max="${item.sisa_belum_terima}"
                                       value="0"
                                       placeholder="0">
                                <small class="text-muted">Max: ${item.sisa_belum_terima}</small>
                            </td>
                        </tr>
                    `;
                    index++;
                });
                
                $('#barangContainer').html(html);
                $('#btnSubmitContainer').show();
            } else {
                $('#barangContainer').html('<tr><td colspan="6" class="text-center text-muted"><i class="fas fa-check-circle"></i> Tidak ada data barang untuk pengadaan ini</td></tr>');
                $('#btnSubmitContainer').hide();
            }
        },
        error: function(xhr) {
            $('#barangContainer').html('<tr><td colspan="6" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data barang</td></tr>');
            $('#btnSubmitContainer').hide();
            alert('Error: ' + (xhr.responseJSON?.message || 'Gagal memuat data'));
        }
    });
});

// Validasi sebelum submit - DIHAPUS karena boleh terima 0 barang
$('#formPenerimaan').submit(function(e) {
    // Konfirmasi saja
    const totalBarang = $('input[name*="jumlah_terima"]').length;
    let totalTerima = 0;
    
    $('input[name*="jumlah_terima"]').each(function() {
        totalTerima += parseInt($(this).val() || 0);
    });
    
    if (totalTerima === 0) {
        return confirm('Anda akan mencatat penerimaan dengan jumlah 0 barang. Ini akan tercatat sebagai dokumentasi saja. Lanjutkan?');
    }
    
    return true;
});
</script>
@endsection