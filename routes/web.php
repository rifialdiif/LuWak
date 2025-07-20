<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AngkatanController;
use App\Http\Controllers\DpaController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MhsController;
use App\Http\Controllers\AkademikController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Storage;
use App\Models\RiwayatAkademik;
use App\Http\Controllers\NotifikasiController;


Route::middleware('auth')->group(function () {
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    });

    // DATA MASTER - Hanya untuk Admin
    Route::group(['prefix' => 'user', 'middleware' => 'role:admin'], function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('create', [UserController::class, 'create'])->name('user.create');
        Route::post('store', [UserController::class, 'store'])->name('user.store');
        Route::get('{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    });

    Route::group(['prefix' => 'prodi', 'middleware' => 'role:admin'], function () {
        Route::get('/', [ProdiController::class, 'index'])->name('prodi.index');
        Route::get('create', [ProdiController::class, 'create'])->name('prodi.create');
        Route::post('store', [ProdiController::class, 'store'])->name('prodi.store');
        Route::get('/{id}/edit', [ProdiController::class, 'edit'])->name('prodi.edit');
        Route::put('/{id}', [ProdiController::class, 'update'])->name('prodi.update');
        Route::delete('/{id}', [ProdiController::class, 'destroy'])->name('prodi.destroy');
    });

    Route::group(['prefix' => 'angkatan', 'middleware' => 'role:admin'], function () {
        Route::get('/', [AngkatanController::class, 'index'])->name('angkatan.index');
        Route::get('create', [AngkatanController::class, 'create'])->name('angkatan.create');
        Route::post('store', [AngkatanController::class, 'store'])->name('angkatan.store');
        Route::get('/{id}/edit', [AngkatanController::class, 'edit'])->name('angkatan.edit');
        Route::put('/{id}', [AngkatanController::class, 'update'])->name('angkatan.update');
        Route::delete('/{id}', [AngkatanController::class, 'destroy'])->name('angkatan.destroy');
    });

    Route::group(['prefix' => 'mhs', 'middleware' => 'role:admin'], function () {
        Route::get('/', [MhsController::class, 'index'])->name('mhs.index');
        Route::post('store', [MhsController::class, 'store'])->name('mhs.store');
        Route::get('/{id}/edit', [MhsController::class, 'edit'])->name('mhs.edit');
        Route::put('/{id}', [MhsController::class, 'update'])->name('mhs.update');
        Route::delete('/{id}', [MhsController::class, 'destroy'])->name('mhs.destroy');
    });

    Route::group(['prefix' => 'dpa', 'middleware' => 'role:admin'], function () {
        Route::get('/', [DpaController::class, 'index'])->name('dpa.index');
        Route::post('store', [DpaController::class, 'store'])->name('dpa.store');
        Route::get('/{id}/edit', [DpaController::class, 'edit'])->name('dpa.edit');
        Route::put('/{id}', [DpaController::class, 'update'])->name('dpa.update');
        Route::delete('/{id}', [DpaController::class, 'destroy'])->name('dpa.destroy');
        Route::get('mahasiswa-by-dosen/{dosenId}', [DpaController::class, 'getMahasiswaByDosen']);
    });

    Route::group(['prefix' => 'akademik'], function () {
        Route::get('/', [AkademikController::class, 'index'])->name('akademik.index');
        Route::get('/{id}', [AkademikController::class, 'show'])->name('akademik.show');
        Route::post('/store', [AkademikController::class, 'store'])->name('akademik.store');
        Route::delete('/{id}', [AkademikController::class, 'destroy'])->name('akademik.destroy');
        Route::put('/{id}', [AkademikController::class, 'update'])->name('akademik.update');
        Route::post('/{id}', [AkademikController::class, 'validasiTranskrip'])->name('akademik.validasi');
        Route::get('/export/angkatan-list', [AkademikController::class, 'getAngkatanList'])->name('akademik.angkatanList');
        Route::post('/export/info', [AkademikController::class, 'getExportInfo'])->name('akademik.exportInfo');
        Route::post('/export/csv', [AkademikController::class, 'exportCsv'])->name('akademik.exportCsv');
    });

    Route::group(['prefix' => 'prediksi'], function () {
        Route::get('/', [App\Http\Controllers\PrediksiController::class, 'index'])->name('prediksi.index');
        Route::get('/{id}', [App\Http\Controllers\PrediksiController::class, 'show'])->name('prediksi.show');
        Route::post('/{id}/predict', [\App\Http\Controllers\PrediksiController::class, 'predict'])->name('prediksi.predict');
        Route::get('/{id}/riwayat-ajax', [\App\Http\Controllers\PrediksiController::class, 'riwayatAjax'])->name('prediksi.riwayatAjax');
        Route::get('/{id}/riwayat-intervensi-ajax', [\App\Http\Controllers\PrediksiController::class, 'riwayatIntervensiAjax'])->name('prediksi.riwayatIntervensiAjax');
        Route::get('/preview/form-intervensi', [App\Http\Controllers\PrediksiController::class, 'formIntervensi'])->name('prediksi.formIntervensi');
    });

    Route::prefix('notifikasi')->group(function () {
        Route::post('/intervensi', [NotifikasiController::class, 'kirimIntervensi'])->name('notifikasi.kirimIntervensi');
    });

    // Laporan routes - Hanya untuk Admin
    Route::group(['prefix' => 'laporan', 'middleware' => 'role:admin'], function () {
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/generate-pdf', [LaporanController::class, 'generatePdf'])->name('laporan.generatePdf');
    });

    // Profile routes
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    });
});

// Route auth tetap di luar middleware agar bisa diakses publik
Route::group(['prefix' => 'auth'], function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'authenticate'])->name('login.process');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/formintervensi', [App\Http\Controllers\PrediksiController::class, 'formIntervensi']);

// Error handling routes
// Route::get('/404', [App\Http\Controllers\ErrorController::class, 'notFound'])->name('error.404');
// Route::get('/403', [App\Http\Controllers\ErrorController::class, 'forbidden'])->name('error.403');

// // Test routes untuk testing error handling
// Route::get('/test-404', function () {
//     abort(404);
// });

// Route::get('/test-403', function () {
//     abort(403);
// });

use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/test-pdf', function () {
    $pdf = Pdf::loadHtml('<h1>Hello World</h1>');
    return $pdf->download('test.pdf');
});
