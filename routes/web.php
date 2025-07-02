<?php

use App\Http\Controllers\ProdiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AngkatanController;
use App\Http\Controllers\DpaController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MhsController;

Route::get('/', function () {
    return view('layouts.app');
});

// DATA MASTER
Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('create', [UserController::class, 'create'])->name('user.create');
    Route::post('store', [UserController::class, 'store'])->name('user.store');
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::group(['prefix' => 'prodi'], function () {
    Route::get('/', [ProdiController::class, 'index'])->name('prodi.index');
    Route::get('create', [ProdiController::class, 'create'])->name('prodi.create');
    Route::post('store', [ProdiController::class, 'store'])->name('prodi.store');
    Route::get('/{id}/edit', [ProdiController::class, 'edit'])->name('prodi.edit');
    Route::put('/{id}', [ProdiController::class, 'update'])->name('prodi.update');
    Route::delete('/{id}', [ProdiController::class, 'destroy'])->name('prodi.destroy');
});

Route::group(['prefix' => 'angkatan'], function () {
    Route::get('/', [AngkatanController::class, 'index'])->name('angkatan.index');
    Route::get('create', [AngkatanController::class, 'create'])->name('angkatan.create');
    Route::post('store', [AngkatanController::class, 'store'])->name('angkatan.store');
    Route::get('/{id}/edit', [AngkatanController::class, 'edit'])->name('angkatan.edit');
    Route::put('/{id}', [AngkatanController::class, 'update'])->name('angkatan.update');
    Route::delete('/{id}', [AngkatanController::class, 'destroy'])->name('angkatan.destroy');
});

// Route::group(['prefix' => 'mahasiswa'], function () {
//     Route::get('/', [MahasiswaController::class, 'index'])->name('mhs.index');
//     Route::post('store', [MahasiswaController::class, 'store'])->name('mhs.store');
//     Route::get('/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
//     Route::put('/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
//     Route::delete('/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
// });


Route::group(['prefix' => 'mhs'], function () {
    Route::get('/', [MhsController::class, 'index'])->name('mhs.index');
    Route::post('store', [MhsController::class, 'store'])->name('mhs.store');
    Route::get('/{id}/edit', [MhsController::class, 'edit'])->name('mhs.edit');
    Route::put('/{id}', [MhsController::class, 'update'])->name('mhs.update');
    Route::delete('/{id}', [MhsController::class, 'destroy'])->name('mhs.destroy');
});

Route::group(['prefix' => 'dpa'], function () {
    Route::get('/', [DpaController::class, 'index'])->name('dpa.index');
    Route::post('store', [DpaController::class, 'store'])->name('dpa.store');
    Route::get('/{id}/edit', [DpaController::class, 'edit'])->name('dpa.edit');
    Route::put('/{id}', [DpaController::class, 'update'])->name('dpa.update');
    Route::delete('/{id}', [DpaController::class, 'destroy'])->name('dpa.destroy');
    Route::get('mahasiswa-by-dosen/{dosenId}', [DpaController::class, 'getMahasiswaByDosen']);
});
