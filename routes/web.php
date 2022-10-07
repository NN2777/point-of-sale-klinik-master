<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    DokterController,
    LaporanController,
    LaporanPenjualanController,
    LaporanLabaRugiController,
    LaporanPembelianController,
    LaporanPersediaanController,
    ProdukController,
    MemberController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    SettingController,
    SupplierController,
    UserController,
    PembayaranController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);
        Route::post('/kategori/import', [KategoriController::class, 'importKategori'])->name('kategori.import');

        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::get('/produk/import', [ProdukController::class, 'import'])->name('produk.import');
        Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
        Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
        Route::resource('/produk', ProdukController::class);

        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::resource('/member', MemberController::class);

        Route::get('/dokter/data', [DokterController::class, 'data'])->name('dokter.data');
        Route::post('/dokter/cetak-dokter', [DokterController::class, 'cetakDokter'])->name('dokter.cetak_dokter');
        Route::resource('/dokter', DokterController::class);

        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);

        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::get('/pembelian/{id}/edit', [PembelianController::class, 'edit'])->name('pembelian.edit');
        Route::resource('/pembelian', PembelianController::class)
            ->except('create','edit');

        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/loadform/{diskon}/{ppn}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::resource('/pembelian_detail', PembelianDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/pembayaran/data', [PembayaranController::class, 'data'])->name('pembayaran.data');
        Route::get('/pembayaran/loadform/{id}', [PembayaranController::class, 'loadForm'])->name('pembayaran.loadform');
        Route::get('/pembayaran/{id}/bayar', [PembayaranController::class, 'bayar'])->name('pembayaran.bayar');
        Route::get('/pembayaran/{id}/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
        Route::get('/pembayaran/{id}/store', [PembayaranController::class, 'store'])->name('pembayaran.store');
        Route::get('/pembayaran/{id}/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
        Route::delete('/pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
        Route::resource('/pembayaran', PembayaranController::class)
            ->except('create','edit','store','delete');
        
        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::get('/penjualan/{id}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
        Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
        Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
        Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

        Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}/{ppn}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
        Route::resource('/transaksi', PenjualanDetailController::class)
            ->except('create', 'show', 'edit');
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/laporan/index', [LaporanController::class, 'index'])->name('laporan.index');

        Route::get('/laporan-labarugi', [LaporanLabaRugiController::class, 'index'])->name('labarugi.index');
        Route::get('/laporan-labarugi/data/{awal}/{akhir}', [LaporanLabaRugiController::class, 'data'])->name('labarugi.data');
        Route::get('/laporan-labarugi/pdf/{awal}/{akhir}', [LaporanLabaRugiController::class, 'exportPDF'])->name('labarugi.export_pdf');
        Route::get('/laporan-labarugi/excel/{awal}/{akhir}', [LaporanLabaRugiController::class, 'exportExcel'])->name('labarugi.export_excel');

        /* laporan pembelian total, kredit, tunai */
        Route::get('/laporan-pembelian', [LaporanPembelianController::class, 'index'])->name('laporan-pembelian.index');
        Route::get('/laporan-pembelian/data/{awal}/{akhir}', [LaporanPembelianController::class, 'data'])->name('pembeliantotal.data');
        Route::get('/laporan-pembelian/pdf/{awal}/{akhir}', [LaporanPembelianController::class, 'exportPDF'])->name('pembeliantotal.export_pdf');
        Route::get('/laporan-pembelian/excel/{awal}/{akhir}', [LaporanPembelianController::class, 'exportExcel'])->name('pembeliantotal.export_excel');
        // Route::get('/laporan-pembelian/importexcel/{awal}/{akhir}', [LaporanPembelianController::class, 'importExcel'])->name('pembeliantotal.import_excel');

        Route::get('/laporan-pembelian-tunai', [LaporanPembelianController::class, 'indexTunai'])->name('laporan-pembelian-tunai.index');
        Route::get('/laporan-pembelian-tunai/data/{awal}/{akhir}', [LaporanPembelianController::class, 'dataTunai'])->name('pembeliantunai.data');
        Route::get('/laporan-pembelian-tunai/pdf/{awal}/{akhir}', [LaporanPembelianController::class, 'exportTunaiPDF'])->name('pembeliantunai.export_pdf');
        Route::get('/laporan-pembelian-tunai/excel/{awal}/{akhir}', [LaporanPembelianController::class, 'exportTunaiExcel'])->name('pembeliantunai.export_excel');

        Route::get('/laporan-pembelian-nota', [LaporanPembelianController::class, 'indexNota'])->name('laporan-pembelian-nota.index');
        Route::get('/laporan-pembelian-nota/data/{awal}/{akhir}', [LaporanPembelianController::class, 'dataNota'])->name('pembeliannota.data');
        Route::get('/laporan-pembelian-nota/pdf/{awal}/{akhir}', [LaporanPembelianController::class, 'exportNotaPDF'])->name('pembeliannota.export_pdf');
        Route::get('/laporan-pembelian-nota/excel/{awal}/{akhir}', [LaporanPembelianController::class, 'exportNotaExcel'])->name('pembeliannota.export_excel');
        
        Route::get('/laporan-pembelian-kredit', [LaporanPembelianController::class, 'indexKredit'])->name('laporan-pembelian-kredit.index');
        Route::get('/laporan-pembelian-kredit/data/{awal}/{akhir}', [LaporanPembelianController::class, 'dataKredit'])->name('pembeliankredit.data');
        Route::get('/laporan-pembelian-kredit/pdf/{awal}/{akhir}', [LaporanPembelianController::class, 'exportKreditPDF'])->name('pembeliankredit.export_pdf');
        Route::get('/laporan-pembelian-kredit/excel/{awal}/{akhir}', [LaporanPembelianController::class, 'exportKreditExcel'])->name('pembeliankredit.export_excel');
    
         /* laporan penjualan total, kredit, tunai */
        Route::get('/laporan-penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan-penjualan.index');
        Route::get('/laporan-penjualan/data/{awal}/{akhir}', [LaporanPenjualanController::class, 'data'])->name('penjualantotal.data');
        Route::get('/laporan-penjualan/pdf/{awal}/{akhir}', [LaporanPenjualanController::class, 'exportPDF'])->name('penjualantotal.export_pdf');
        Route::get('/laporan-penjualan/excel/{awal}/{akhir}', [LaporanPenjualanController::class, 'exportExcel'])->name('penjualantotal.export_excel');

        Route::get('/laporan-penjualan-tunai', [LaporanPenjualanController::class, 'indexTunai'])->name('laporan-penjualan-tunai.index');
        Route::get('/laporan-penjualan-tunai/data/{awal}/{akhir}', [LaporanPenjualanController::class, 'dataTunai'])->name('penjualantunai.data');
        Route::get('/laporan-penjualan-tunai/pdf/{awal}/{akhir}', [LaporanPenjualanController::class, 'exportTunaiPDF'])->name('penjualantunai.export_pdf');
        Route::get('/laporan-penjualan-tunai/excel/{awal}/{akhir}', [LaporanPenjualanController::class, 'exportTunaiExcel'])->name('penjualantunai.export_excel');

        Route::get('/laporan-penjualan-kredit', [LaporanPenjualanController::class, 'indexKredit'])->name('laporan-penjualan-kredit.index');
        Route::get('/laporan-penjualan-kredit/data/{awal}/{akhir}', [LaporanPenjualanController::class, 'dataKredit'])->name('penjualankredit.data');
        Route::get('/laporan-penjualan-kredit/pdf/{awal}/{akhir}', [LaporanPenjualanController::class, 'exportKreditPDF'])->name('penjualankredit.export_pdf');
        Route::get('/laporan-penjualan-kredit/excel/{awal}/{akhir}', [LaporanPenjualanController::class, 'exportKreditExcel'])->name('penjualankredit.export_excel');

        Route::get('/laporan-penjualan-nota', [LaporanPenjualanController::class, 'indexNota'])->name('laporan-penjualan-nota.index');
        Route::get('/laporan-penjualan-nota/data/{awal}/{akhir}', [LaporanPenjualanController::class, 'dataNota'])->name('penjualannota.data');
        Route::get('/laporan-penjualan-nota/pdf/{awal}/{akhir}', [LaporanPenjualanController::class, 'exportNotaPDF'])->name('penjualannota.export_pdf');
        Route::get('/laporan-penjualan-nota/excel/{awal}/{akhir}', [LaporanPenjualanController::class, 'exportNotaExcel'])->name('penjualannota.export_excel');

        /*laporan persediaan*/
        Route::get('/laporan-persediaan', [LaporanPersediaanController::class, 'index'])->name('laporan-persediaan.index');
        Route::get('/laporan-persediaan/data/{tanggal}', [LaporanPersediaanController::class, 'data'])->name('persediaan.data');
        Route::get('/laporan-persediaan/pdf/{tanggal}', [LaporanPersediaanController::class, 'exportPDF'])->name('persediaan.export_pdf');
        Route::get('/laporan-persediaan/excel/{tanggal}', [LaporanPersediaanController::class, 'exportExcel'])->name('persediaan.export_excel');

        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');

    });
 
    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    });
});