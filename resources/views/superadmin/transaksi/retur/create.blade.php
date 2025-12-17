@extends('layout.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Tambah Retur Barang</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('retur.index') }}" class="btn btn-secondary">
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

    <form action="{{ route('retur.store') }}" method="POST" id="formRetur">
        @csrf

        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Retur</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pilih Penerimaan <span class="text-danger">*</span></label>
                            <select name="idpenerimaan" id="selectPenerimaan" class="form-select" required>
                                <option value="">-- Pilih Penerimaan --</option>
                                @foreach($penerimaan as $p)
                                    <option value="{{ $p->idpenerimaan }}">
                                        {{ $p->idpenerimaan }} - {{ $p->nama_vendor }} 
                                        ({{ date('d/m/Y', strtotime($p->created_at)) }})
                                        - PO: {{ $p->idpengadaan }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih penerimaan yang ada barang rusak/cacat</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Retur</label>
                            <input type="text" class="form-control" value="{{ date('d/m/Y H:i') }}" readonly>
                            <small class="text-muted">Otomatis diambil dari sistem</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="cardBarang" style="display: none;">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Daftar Barang yang Di-retur</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Masukkan jumlah barang yang rusak/cacat dan alasan retur.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th class="text-center">Jumlah Terima</th>
                                <th class="text-center">Sudah Retur</th>
                                <th class="text-center">Sisa Bisa Retur</th>
                                <th class="text-center">Jumlah Retur</th>
                                <th>Alasan</th>
                            </tr>
                        </thead>
                        <tbody id="barangContainer">
                            <!-- Data barang akan dimuat via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-end" id="btnSubmitContainer" style="display: none;">
            <button type="submit" class="btn btn-danger btn-lg">
                <i class="fas fa-undo"></i> Proses Retur
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
// Event listener untuk select penerimaan
$('#selectPenerimaan').change(function() {
    const idpenerimaan = $(this).val();
    
    if (!idpenerimaan) {
        $('#cardBarang').hide();
        $('#btnSubmitContainer').hide();
        return;
    }
    
    // Loading state
    $('#barangContainer').html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>');
    $('#cardBarang').show();
    
    // Fetch data barang via AJAX
    $.ajax({
        url: "{{ route('retur.getBarangPenerimaan', ':idpenerimaan') }}".replace(':idpenerimaan', idpenerimaan),
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
                                <input type="hidden" name="barang[${index}][iddetail_penerimaan]" value="${item.iddetail_penerimaan}">
                            </td>
                            <td>${item.nama_satuan}</td>
                            <td class="text-center"><strong>${item.jumlah_terima}</strong></td>
                            <td class="text-center">${item.total_retur}</td>
                            <td class="text-center">
                                <span class="badge bg-warning">${item.sisa_bisa_retur}</span>
                            </td>
                            <td>
                                <input type="number" 
                                       name="barang[${index}][jumlah_retur]" 
                                       class="form-control text-center" 
                                       min="0" 
                                       max="${item.sisa_bisa_retur}"
                                       placeholder="0">
                                <small class="text-muted">Max: ${item.sisa_bisa_retur}</small>
                            </td>
                            <td>
                                <textarea name="barang[${index}][alasan]" 
                                          class="form-control" 
                                          rows="2"
                                          placeholder="Contoh: Barang rusak, cacat produksi, dll"></textarea>
                            </td>
                        </tr>
                    `;
                    index++;
                });
                
                $('#barangContainer').html(html);
                $('#btnSubmitContainer').show();
            } else {
                $('#barangContainer').html('<tr><td colspan="7" class="text-center text-success">Semua barang sudah baik, tidak ada yang perlu di-retur</td></tr>');
                $('#btnSubmitContainer').hide();
            }
        },
        error: function(xhr) {
            $('#barangContainer').html('<tr><td colspan="7" class="text-center text-danger">Gagal memuat data barang</td></tr>');
            $('#btnSubmitContainer').hide();
            alert('Error: ' + (xhr.responseJSON?.message || 'Gagal memuat data'));
        }
    });
});

// Validasi sebelum submit
$('#formRetur').submit(function(e) {
    let adaRetur = false;
    let adaAlasanKosong = false;
    
    $('input[name*="jumlah_retur"]').each(function() {
        const jumlah = parseInt($(this).val()) || 0;
        if (jumlah > 0) {
            adaRetur = true;
            
            // Cek alasan
            const alasan = $(this).closest('tr').find('textarea[name*="alasan"]').val().trim();
            if (!alasan) {
                adaAlasanKosong = true;
            }
        }
    });
    
    if (!adaRetur) {
        e.preventDefault();
        alert('Minimal harus ada 1 barang yang di-retur!');
        return false;
    }
    
    if (adaAlasanKosong) {
        e.preventDefault();
        alert('Alasan retur wajib diisi untuk setiap barang yang di-retur!');
        return false;
    }
});
</script>
@endsection