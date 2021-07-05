<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RiwayatPeminjamanController;
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

//Dashboard
Route::get('_admin', [DashboardController::class, 'page'])->name('dashboard');

//Peminjaman Kendaraan
Route::get('data-peminjaman', [PeminjamanController::class, 'page'])->name('data-peminjaman');
Route::get('api/peminjaman/getAll', [PeminjamanController::class, 'getAll']);
Route::post('api/peminjaman/save', [PeminjamanController::class, 'store']);
Route::put('api/peminjaman/{id}', [PeminjamanController::class, 'update']);

//History Peminjaman
Route::get('data-history', [RiwayatPeminjamanController::class, 'page'])->name('data-history');
Route::get('api/history/getAll', [RiwayatPeminjamanController::class, 'getAll']);
Route::get('api/history/getById/{id}', [RiwayatPeminjamanController::class, 'edit']);
Route::post('api/history/save', [RiwayatPeminjamanController::class, 'store']);
Route::delete('api/history/{id}', [RiwayatPeminjamanController::class, 'destroy']);

//Data Kendaraan
Route::get('data-kendaraan', [KendaraanController::class, 'page'])->name('data-kendaraan');
Route::get('api/kendaraan/getAll', [KendaraanController::class, 'getAll']);
Route::get('api/kendaraan/getById/{id}', [KendaraanController::class, 'edit']);
Route::post('api/kendaraan/save', [KendaraanController::class, 'store']);
Route::delete('api/kendaraan/{id}', [KendaraanController::class, 'destroy']);

//Data Pegawai
Route::get('data-pegawai', [PegawaiController::class, 'page'])->name('data-pegawai');
Route::get('api/pegawai/getAll', [PegawaiController::class, 'getAll']);
Route::get('api/pegawai/getById/{nip}', [PegawaiController::class, 'edit']);
Route::post('api/pegawai/save', [PegawaiController::class, 'store']);
Route::delete('api/pegawai/{id}', [PegawaiController::class, 'destroy']);

//Login
Route::get('login', [LoginController::class, 'show_login_form'])->name('login');
Route::post('login', [LoginController::class, 'process_login'])->name('login');
Route::get('register', [LoginController::class, 'show_signup_form'])->name('register');
Route::post('register', [LoginController::class, 'process_signup'])->name('register');
Route::get('logout', [LoginController::class, 'logout']);
