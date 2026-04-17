<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Settings\RolesController;
use App\Http\Controllers\Admin\Settings\UsersController;
use App\Http\Controllers\Admin\Settings\NavigationsController;
use App\Http\Controllers\Admin\Settings\PreferencesController;
use App\Http\Controllers\Landing\LandingController;
use App\Http\Controllers\Landing\DaftarPasarController;
use App\Http\Controllers\Landing\PasarController;
use App\Http\Controllers\Landing\PasarDetailController;
use App\Http\Controllers\Landing\MatriksHargaController;
use App\Http\Controllers\Landing\TrendHargaController;
use App\Http\Controllers\Landing\PerbandinganHargaController;
use App\Http\Controllers\Landing\TabelHargaController;

use App\Http\Controllers\LurahKomoditasController;
use App\Http\Controllers\LurahStokController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* 
|--------------------------------------------------------------------------
| Landing Routes
|--------------------------------------------------------------------------
*/
// Landingpage is now the default route
Route::redirect('/', '/sigapan');

// Landing Pages - Now using Controller
Route::get('/sigapan', [LandingController::class, 'index'])->name('sigapan');
Route::get('/pasar', [PasarController::class, 'pasar'])->name('pasar');
Route::get('/pasar/{id}', [PasarDetailController::class, 'pasarDetail'])->name('pasar.detail');
Route::get('/daftar-pasar', [DaftarPasarController::class, 'daftarPasar'])->name('daftar-pasar');
Route::get('/stock-pasar', [PasarController::class, 'stockPasar'])->name('stock-pasar');
Route::get('/perbandingan-harga', [PerbandinganHargaController::class, 'perbandinganHarga'])->name('perbandingan-harga');
Route::get('/matriks-harga', [MatriksHargaController::class, 'matriksHarga'])->name('matriks-harga');
Route::get('/trend-harga', [TrendHargaController::class, 'trendHarga'])->name('trend-harga');
Route::get('/tabel-harga', [TabelHargaController::class, 'index'])->name('tabel-harga.index');
// Halaman preview PDF
Route::get('/tabel-harga/preview', [TabelHargaController::class, 'preview'])->name('tabel-harga.preview');
Route::get('/lurah', [LurahKomoditasController::class, 'index'])->name('lurah');
Route::get('/lurah', [LurahKomoditasController::class, 'index'])->name('lurah');
// Generate & download PDF
Route::get('/tabel-harga/download', [TabelHargaController::class, 'download'])->name('tabel-harga.download');
Route::get('/tabel-harga/preview-pdf', [TabelHargaController::class, 'previewPdfStream'])
    ->name('tabel-harga.preview-pdf');



// API for charts
Route::get('/api/chart-data', [TrendHargaController::class, 'chartData'])->name('api.chart-data');

/* 
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
// Route::middleware(['auth', 'verified'])->group(function () {
Route::middleware('auth')->group(function () {
    /* ---- Dashboard */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* ---- My Profile */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ---- Settings */
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        /* Users */
        Route::resource('users', UsersController::class)->names('users');
        /* Roles */
        Route::resource('roles', RolesController::class)->names('roles');
        Route::put('/roles/{role}/permissions', [RolesController::class, 'givePermission'])->name('roles.permissions');
        /* Navigation */
        Route::resource('navs', NavigationsController::class)->names('navs');
        /* Preferences */
        Route::resource('preferences', PreferencesController::class)->names('preferences');
    });

    /* ---- Pasar - Harga Bapok */
Route::prefix('pasar/{pasarSlug}')->name('pasar.')->group(function () {
    Route::get('/harga-bapok', [App\Http\Controllers\Admin\HargaBapokController::class, 'index'])->name('harga-bapok.index');
    Route::get('/harga-bapok/export-excel', [App\Http\Controllers\Admin\HargaBapokController::class, 'exportExcel'])->name('harga-bapok.export-excel');
    Route::get('/harga-bapok/create', [App\Http\Controllers\Admin\HargaBapokController::class, 'create'])->name('harga-bapok.create');
    Route::post('/harga-bapok', [App\Http\Controllers\Admin\HargaBapokController::class, 'store'])->name('harga-bapok.store');
    Route::get('/harga-bapok/{id}', [App\Http\Controllers\Admin\HargaBapokController::class, 'show'])->name('harga-bapok.show');
    Route::get('/harga-bapok/{id}/edit', [App\Http\Controllers\Admin\HargaBapokController::class, 'edit'])->name('harga-bapok.edit');
    Route::put('/harga-bapok/{id}', [App\Http\Controllers\Admin\HargaBapokController::class, 'update'])->name('harga-bapok.update');
    Route::delete('/harga-bapok/{id}', [App\Http\Controllers\Admin\HargaBapokController::class, 'destroy'])->name('harga-bapok.destroy');
    Route::post('/harga-bapok/publish', [App\Http\Controllers\Admin\HargaBapokController::class, 'publish'])->name('harga-bapok.publish');

    // Stok Bapok Routes
    Route::get('/stok-bapok/export-excel', [App\Http\Controllers\Admin\StokBapokController::class, 'exportExcel'])->name('stok-bapok.export-excel');
    Route::get('/stok-bapok', [App\Http\Controllers\Admin\StokBapokController::class, 'index'])->name('stok-bapok.index');
    Route::post('/stok-bapok/update', [App\Http\Controllers\Admin\StokBapokController::class, 'updateStok'])->name('stok-bapok.update');
    Route::post('/stok-bapok/publish', [App\Http\Controllers\Admin\StokBapokController::class, 'publish'])->name('stok-bapok.publish');
    Route::get('/stok-bapok/get-pending', [App\Http\Controllers\Admin\StokBapokController::class, 'getPendingItems'])->name('stok-bapok.get-pending');
}); 

    // Admin Update Harga AJAX endpoint
    Route::post('/admin/harga-bapok/update-harga', [App\Http\Controllers\Admin\HargaBapokController::class, 'updateHarga'])->name('admin.harga-bapok.update-harga');
    
    // AJAX endpoint for filter pasar
    Route::get('/admin/harga-bapok/get-data', [App\Http\Controllers\Admin\HargaBapokController::class, 'getDataByPasar'])->name('admin.harga-bapok.get-data');
    Route::get('/admin/harga-bapok/get-pending-items', [App\Http\Controllers\Admin\HargaBapokController::class, 'getPendingItems'])->name('admin.harga-bapok.get-pending-items');
    Route::post('/admin/harga-bapok/store-komoditas', [App\Http\Controllers\Admin\HargaBapokController::class, 'storeKomoditas'])->name('admin.harga-bapok.store-komoditas');
});

require __DIR__ . '/auth.php';


// Route khusus lurah untuk kelola komoditas
Route::middleware(['auth'])->prefix('lurah')->name('lurah.')->group(function () {
    Route::get('komoditas', [LurahKomoditasController::class, 'index'])->name('komoditas.index');
    Route::get('komoditas/create', [LurahKomoditasController::class, 'create'])->name('komoditas.create');
    Route::post('komoditas', [LurahKomoditasController::class, 'store'])->name('komoditas.store');
    Route::get('komoditas/{id}/edit', [LurahKomoditasController::class, 'edit'])->name('komoditas.edit');
    Route::put('komoditas/{id}', [LurahKomoditasController::class, 'update'])->name('komoditas.update');
    Route::delete('komoditas/{id}', [LurahKomoditasController::class, 'destroy'])->name('komoditas.destroy');
    Route::post('komoditas/update-harga', [LurahKomoditasController::class, 'updateHargaKomoditas'])->name('komoditas.update-harga');
    
    // Route untuk update stok
    Route::get('stok', [LurahStokController::class, 'index'])->name('stok.index');
    Route::post('stok/update', [LurahStokController::class, 'updateStok'])->name('stok.update');
    Route::post('stok/update-all', [LurahStokController::class, 'updateAllStok'])->name('stok.updateAll');
});

// Change Locale Language
Route::get('change-locale/{lang}', [LocaleController::class, 'changeLocale'])->name('change-locale');
