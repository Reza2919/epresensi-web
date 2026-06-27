<?php

use App\Http\Controllers\ManualBookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SistemKerjaController;
use App\Http\Controllers\LiburController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\SatkerController;
use App\Http\Controllers\SatkerUserController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\SatkerKoordinatController;
use App\Http\Controllers\SatkerSettingController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\TukinController;
use App\Http\Controllers\PeriodeTukinController;
use App\Http\Controllers\RekapPresensiController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\GolonganPajakController;
use App\Http\Controllers\PotonganTukinController;
use App\Http\Controllers\StatusPegawaiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\UserController;

Route::get('/login', [AuthController::class, 'index'])->name('login-form');
Route::get('/faq', [FaqController::class, 'index']);
Route::get('/libur/delete/{id}', [LiburController::class,'destroy']) ->name('libur.delete');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/kebijakan-privasi', [ConfigController::class, 'kebijakanPrivasi'])->name('kebijakan-privasi');
Route::get('sso-callback', [AuthController::class, 'ssoLogin']);
Route::middleware(['login'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::middleware(['login'])->group(function () {
        Route::get('/profil', [AuthController::class, 'profil'])->name('profil');
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('config', ConfigController::class);
            Route::resource('libur', LiburController::class);
            Route::resource('user', UserController::class);
            Route::resource('cuti', CutiController::class);
            Route::resource('satker-user', SatkerUserController::class);
            Route::resource('tukin', TukinController::class);
            Route::resource('golongan-pajak', GolonganPajakController::class);
            Route::resource('periode', PeriodeTukinController::class);
            Route::post('periode/set-active/{id}', [PeriodeTukinController::class, 'setActive'])->name('periode.set-active');
            Route::resource('sistem-kerja', SistemKerjaController::class);
            Route::patch('sistem-kerja-detail/{id}/update', [SistemKerjaController::class, 'updateDetail'])->name('sistem-kerja-detail.update');
            Route::post('sistem-kerja/set-active/{id}', [SistemKerjaController::class, 'setActive'])->name('sistem-kerja.set-active');

            Route::get('/satker', [SatkerController::class, 'index'])->name('satker.index');
            Route::get('/satker/{id}', [SatkerController::class, 'show'])->name('satker.show');

            Route::get('/koordinator/{satkerid}/detail', [KoordinatorController::class, 'listKoordinator']);

            Route::post('/change-password', [AuthController::class, 'changePassword']);
            Route::get('/generate-presensi', [PresensiController::class, 'generatePresensi']);
            Route::post('/generate-presensi', [PresensiController::class, 'doGeneratePresensi']);
            Route::get('/list-generate-presensi', [PresensiController::class, 'getListJob']);
            Route::post('/potongan-tukin/{id}', [PotonganTukinController::class, 'update']);
            Route::delete('/potongan-tukin/{id}', [PotonganTukinController::class, 'destroy']);

            Route::delete('/presensi/{id}', [PresensiController::class, 'destroy'])->name('presensi.delete');

            Route::get('/manual-book/destroy/{id}', [ManualBookController::class, 'destroyManualBook']);
            Route::get('/manual-book/ajax', [ManualBookController::class, 'getListManualBook']);
            Route::resource('/manual-book', ManualBookController::class);

            Route::get('/setting/faq', [FaqController::class, 'listFAQ'])->name('faq.list');
            Route::get('/setting/faq/form', [FaqController::class, 'form'])->name('faq.form');
            Route::post('/setting/faq/form', [FaqController::class, 'submitFaq'])->name('faq.submit');
            Route::get('/setting/faq/form/{id}', [FaqController::class, 'editFaq'])->name('faq.edit');
            Route::post('/setting/faq/form/{id}', [FaqController::class, 'updateFaq'])->name('faq.update');
            Route::get('/setting/faq/destroy/{id}', [FaqController::class, 'destroyFaq'])->name('faq.destroy');

            Route::get('/setting/kategori', [FaqController::class, 'listKategori'])->name('kategori.list');
            Route::get('/setting/kategori/form', [FaqController::class, 'formKategori'])->name('kategori.form');
            Route::get('/setting/kategori/form/{id}', [FaqController::class, 'editKategori'])->name('kategori.edit');
            Route::post('/setting/kategori/form/{id}', [FaqController::class, 'updateKategori'])->name('kategori.update');
            Route::post('/setting/kategori/form', [FaqController::class, 'submitKategori'])->name('kategori.submit');
            Route::get('/setting/kategori/destroy/{id}', [FaqController::class, 'destroyKategori'])->name('kategori.destroy');
            Route::get('/presensi-log', [PresensiController::class, 'presensiLog'])->name('presensi-log.get');
        });
        Route::middleware(['role:admin,tu,pimpinan'])->group(function () {
           Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');

Route::get('/pegawai/create', [PegawaiController::class, 'create']);
Route::post('/pegawai/store', [PegawaiController::class, 'store']);

Route::get('/pegawai/{id}', [PegawaiController::class, 'show'])->name('pegawai.show');
Route::get('/pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');

Route::post('/pegawai/{id}/update', [PegawaiController::class, 'update'])->name('pegawai.update');
Route::get('/pegawai/{id}/delete', [PegawaiController::class, 'delete'])->name('pegawai.delete');

            Route::delete('/bidang/{id_satker_bidang}', [KoordinatorController::class, 'destroy'])->name('bidang.destroy');
            Route::delete('/satker-user/{id_satker_user}/delete', [SatkerUserController::class, 'destroy']);
            Route::post('/koordinator/{satkerid}/generate', [KoordinatorController::class, 'generate'])->name('koordinator.generate');
            Route::resource('/satker-koordinat', SatkerKoordinatController::class);
            Route::get('/get-trend-presensi', [PresensiController::class,'getTrendPresensi']);
            Route::post('/potongan-tukin', [PotonganTukinController::class, 'store']);
        });
        Route::middleware(['role:tu'])->group(function () {
            Route::post('/koordinator-pegawai', [KoordinatorController::class, 'storePegawai'])->name('koordinator.store-bidang');
            Route::delete('/koordinator-pegawai/{id_satker_bidang_pegawai}', [PegawaiController::class, 'deleteKoordinatorPegawai'])->name('koordinator-pegawai.destroy');
            Route::resource('/koordinator', KoordinatorController::class);

        });
        Route::middleware(['role:tu,pimpinan,admin'])->group(function () {
            Route::resource('/presensi', RekapPresensiController::class);
            Route::get('/presensi-latlong', [RekapPresensiController::class,'getPresensiLatLong']);
            Route::post('/presensi/{id}/set-tidak-presensi', [PresensiController::class, 'setTidakPresensi'])->name('presensi.set-tidak-presensi');
            Route::get('/presensi/edit/{id}/', [PresensiController::class, 'edit']);
            Route::patch('/presensi/{id}/save', [PresensiController::class, 'save'])->name('presensi.save');
            Route::post('/potongan-tukin', [PotonganTukinController::class, 'store']);

            Route::resource('/satker-setting', SatkerSettingController::class);
            Route::get('/laporan-presensi', [ReportController::class, 'index']);
            Route::get('/download-laporan-presensi/{id}', [ReportController::class, 'downloadFileJob']);
            Route::post('/laporan-presensi/print', [ReportController::class, 'print']);
            Route::post('/list-laporan-presensi', [ReportController::class, 'getListJob']);
            Route::delete('/list-laporan-presensi/{id}', [ReportController::class, 'destroyListJob']);

            Route::get('/get-statistic', [DashboardController::class, 'getStatistic']);
        });

        Route::group(['prefix' => 'api'], function () {
            Route::get('/get-sistem-kerja/{mode?}', [SistemKerjaController::class, 'get']);
            Route::get('/get-sistem-kerja-by-id/{id}', [SistemKerjaController::class, 'getById']);
            Route::get('/get-libur/{mode?}', [LiburController::class, 'get']);
            Route::post('/libur/store/bulk', [LiburController::class, 'store_bulk'])->name('libur.storebulk');
            Route::get('/get-user/{mode?}', [UserController::class, 'get']);
            Route::get('/get-cuti/{mode?}', [CutiController::class, 'get']);
            Route::get('/get-satker/{mode?}', [SatkerController::class, 'get']);
            Route::get('/get-status-pegawai/{mode?}', [StatusPegawaiController::class, 'get']);
            Route::get('/get-pegawai/{mode?}', [PegawaiController::class, 'get']);
            Route::get('/get-satker-user/{mode?}', [SatkerUserController::class, 'getSatkerUser']);
            Route::get('/get-satker-setting/{idsatker}', [SatkerSettingController::class, 'getSatkerSettingById']);
            Route::get('/get-koordinator/{mode?}', [KoordinatorController::class, 'get']);
            Route::get('/get-bidang/{mode?}', [BidangController::class, 'get']);
            Route::get('/get-pegawai-bidang/{mode?}', [PegawaiController::class, 'getPegawaiBidang']);
            Route::get('/get-presensi-pegawai/{mode?}/{pegawaiid?}', [PresensiController::class, 'get']);
            Route::get('/get-potongan-tukin/{mode?}/{pegawaiid?}', [PotonganTukinController::class, 'get']);
            Route::post('/get-rekap-tukin', [PotonganTukinController::class, 'getRekap']);
            Route::get('/get-tukin/{mode?}', [TukinController::class, 'get']);
            Route::get('/get-golongan-pajak/{mode?}', [GolonganPajakController::class, 'get']);
            Route::get('/get-periode/{mode?}', [PeriodeTukinController::class, 'get']);
            Route::get('/get-presensi/{mode?}', [RekapPresensiController::class, 'get']);
            Route::get('/get-config/{mode?}', [ConfigController::class, 'get']);
            Route::get('/get-satker-user-by-satkerid/{idsatker}/', [SatkerUserController::class, 'getBySatkerId']);
            Route::get('/get-koordinator-by-satkerid/{idsatker}/', [KoordinatorController::class, 'getBySatkerId']);
            Route::get('/get-koordinat-by-satkerid/{idsatker}/', [SatkerKoordinatController::class, 'getSatkerKoordinat']);
            Route::get('/get-kategori-faq/{mode?}', [FaqController::class, 'getKategori']);
            Route::get('/get-faq/{mode?}', [FaqController::class, 'getListFaq']);
            Route::get('/get-presensi-log', [PresensiController::class, 'getPresensiLog'])->name('presensi-log.getdata');
        });
    });

});

Route::middleware(['cors','login'])->group(function () {
    Route::post('rekap-presensi/{id}', [RekapPresensiController::class, 'rekapPresensiPdf'])->name('cetak-rekap');
    Route::post('rekap-presensi-all/{id}', [RekapPresensiController::class, 'rekapPresensiAllPdf'])->name('cetak-semua-rekap');
    Route::post('rekap-tukin/{id}', [RekapPresensiController::class, 'rekapTukinPdf'])->name('cetak-rekap-tukin');
});
