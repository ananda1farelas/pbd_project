<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('auth.login'));

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('auth.login.perform');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


// SUPERADMIN
Route::middleware([\App\Http\Middleware\CekLogin::class.':2'])->group(function () {
    Route::get('/superadmin/dashboard', [App\Http\Controllers\Superadmin\DashboardController::class, 'index'])->name('superadmin.dashboard');
    //barang
    Route::prefix('superadmin/datamaster/barang')->name('superadmin.barang.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\Datamaster\BarangController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\Datamaster\BarangController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Superadmin\Datamaster\BarangController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\Superadmin\Datamaster\BarangController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [App\Http\Controllers\Superadmin\Datamaster\BarangController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [App\Http\Controllers\Superadmin\Datamaster\BarangController::class, 'destroy'])->name('delete');
    });
    //margin    
    Route::prefix('superadmin/datamaster/margin')->name('superadmin.margin.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\Datamaster\MarginController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\Datamaster\MarginController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Superadmin\Datamaster\MarginController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\Superadmin\Datamaster\MarginController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [App\Http\Controllers\Superadmin\Datamaster\MarginController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [App\Http\Controllers\Superadmin\Datamaster\MarginController::class, 'destroy'])->name('delete');
    });
    //role
    Route::prefix('superadmin/datamaster/role')->name('role.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\Datamaster\RoleController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\Datamaster\RoleController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Superadmin\Datamaster\RoleController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\Superadmin\Datamaster\RoleController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [App\Http\Controllers\Superadmin\Datamaster\RoleController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [App\Http\Controllers\Superadmin\Datamaster\RoleController::class, 'destroy'])->name('delete');
    });
    //satuan
    Route::prefix('superadmin/master/satuan')->name('satuan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\Datamaster\SatuanController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\Datamaster\SatuanController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Superadmin\Datamaster\SatuanController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\Superadmin\Datamaster\SatuanController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [App\Http\Controllers\Superadmin\Datamaster\SatuanController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [App\Http\Controllers\Superadmin\Datamaster\SatuanController::class, 'destroy'])->name('delete');
    });
    //user
    Route::prefix('superadmin/master/user')->name('user.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\Datamaster\UserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\Datamaster\UserController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Superadmin\Datamaster\UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\Superadmin\Datamaster\UserController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [App\Http\Controllers\Superadmin\Datamaster\UserController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [App\Http\Controllers\Superadmin\Datamaster\UserController::class, 'destroy'])->name('delete');
    });
    //vendor
    Route::prefix('superadmin/datamaster/vendor')->name('vendor.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\Datamaster\VendorController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\Datamaster\VendorController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Superadmin\Datamaster\VendorController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\Superadmin\Datamaster\VendorController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [App\Http\Controllers\Superadmin\Datamaster\VendorController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [App\Http\Controllers\Superadmin\Datamaster\VendorController::class, 'destroy'])->name('delete');
    });
    //penerimaan
    Route::prefix('superadmin/transaksi/penerimaan')->name('penerimaan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\Transaksi\PenerimaanController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\Transaksi\PenerimaanController::class, 'create'])->name('create');
        Route::get('/create/{idpengadaan}', [App\Http\Controllers\Superadmin\Transaksi\PenerimaanController::class, 'createDetail'])->name('create.detail');
        Route::post('/store', [App\Http\Controllers\Superadmin\Transaksi\PenerimaanController::class, 'store'])->name('store');
        Route::get('/api/pengadaan/{idpengadaan}/barang', [App\Http\Controllers\Superadmin\Transaksi\PenerimaanController::class, 'getBarangPengadaan'])->name('getBarangPengadaan');
        Route::get('/{id}', [App\Http\Controllers\Superadmin\Transaksi\PenerimaanController::class, 'show'])->name('show');
    });
    //pengadaan
    Route::prefix('superadmin/transaksi/pengadaan')->name('pengadaan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\Transaksi\PengadaanController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\Transaksi\PengadaanController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Superadmin\Transaksi\PengadaanController::class, 'store'])->name('store');
        Route::put('/{id}/approve', [App\Http\Controllers\Superadmin\Transaksi\PengadaanController::class, 'approve'])->name('approve');
        Route::put('/{id}/cancel', [App\Http\Controllers\Superadmin\Transaksi\PengadaanController::class, 'cancel'])->name('cancel');
        Route::get('/{id}', [App\Http\Controllers\Superadmin\Transaksi\PengadaanController::class, 'show'])->name('show');
    });
    //penjualan
    Route::prefix('superadmin/transaksi/penjualan')->name('superadmin.penjualan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\Transaksi\PenjualanController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\Transaksi\PenjualanController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Superadmin\Transaksi\PenjualanController::class, 'store'])->name('store');
        Route::post('/api/hitung-harga', [App\Http\Controllers\Superadmin\Transaksi\PenjualanController::class, 'hitungHargaJual'])->name('hitungHarga');
        Route::get('/{id}', [App\Http\Controllers\Superadmin\Transaksi\PenjualanController::class, 'show'])->name('show');
        Route::delete('/{id}', [App\Http\Controllers\Superadmin\Transaksi\PenjualanController::class, 'destroy'])->name('destroy');
    });
    //kartu stok
    Route::prefix('superadmin/transaksi/kartustok')->name('kartustok.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\Transaksi\KartuStokController::class, 'index'])->name('index');
        Route::get('/stok-terkini', [App\Http\Controllers\Superadmin\Transaksi\KartuStokController::class, 'stokTerkini'])->name('stok-terkini');
        Route::get('/summary', [App\Http\Controllers\Superadmin\Transaksi\KartuStokController::class, 'summary'])->name('summary');
        Route::get('/{idbarang}', [App\Http\Controllers\Superadmin\Transaksi\KartuStokController::class, 'show'])->name('show');
        Route::get('/export/data', [App\Http\Controllers\Superadmin\Transaksi\KartuStokController::class, 'export'])->name('export');
    });
    //retur
    Route::prefix('superadmin/transaksi/retur')->name('retur.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\Transaksi\ReturController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\Transaksi\ReturController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Superadmin\Transaksi\ReturController::class, 'store'])->name('store');
        Route::get('/api/penerimaan/{idpenerimaan}/barang', [App\Http\Controllers\Superadmin\Transaksi\ReturController::class, 'getBarangPenerimaan'])->name('getBarangPenerimaan');
        Route::get('/{id}', [App\Http\Controllers\Superadmin\Transaksi\ReturController::class, 'show'])->name('show');
    });
});

//Admin
Route::middleware([\App\Http\Middleware\CekLogin::class . ':1'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        // Barang (Read Only)
    Route::prefix('admin/barang')->name('admin.barang.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\BarangController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\BarangController::class, 'show'])->name('show');
    });
    // Margin (Read Only)
    Route::prefix('admin/margin')->name('admin.margin.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\MarginController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\MarginController::class, 'show'])->name('show');
    });
    // Penjualan (Read Only)
    Route::prefix('admin/penjualan')->name('admin.penjualan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PenjualanController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\PenjualanController::class, 'show'])->name('show');
    });
});