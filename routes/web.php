<?php

use App\Http\Controllers\KfaController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\OraganizationController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PracticionerController;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware('auth');

Route::get('/', [LoginController::class, 'index']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/proses-login', [LoginController::class, 'authenticate'])->name('proses-login');
Route::get('/proses-logout', [LoginController::class, 'signOut'])->name('proses-logout');

Route::get('/parameter', [ParameterController::class, 'index'])->name('parameter')->middleware('auth');
Route::get('/parameter-ubah', [ParameterController::class, 'ubah'])->name('parameter-ubah')->middleware('auth');
Route::post('/parameter-update', [ParameterController::class, 'update'])->name('parameter-update')->middleware('auth');
Route::get('/parameter-lihat', [ParameterController::class, 'lihat'])->name('parameter-lihat')->middleware('auth');

Route::get('/organisasi', [OraganizationController::class, 'index'])->name('organisasi')->middleware('auth');
Route::get('/organisasi-tambah', [OraganizationController::class, 'tambah'])->name('organisasi-tambah')->middleware('auth');
Route::post('/organisasi-simpan', [OraganizationController::class, 'simpan'])->name('organisasi-simpan')->middleware('auth');
Route::get('/organisasi-struktur', [OraganizationController::class, 'struktur'])->name('organisasi-struktur')->middleware('auth');
Route::get('/organisasi-ubah/{id}', [OraganizationController::class, 'ubah'])->name('organisasi-ubah')->middleware('auth');
Route::post('/organisasi-update', [OraganizationController::class, 'update'])->name('organisasi-update')->middleware('auth');
Route::get('/organisasi-hapus/{id}', [OraganizationController::class, 'hapus'])->name('organisasi-hapus')->middleware('auth');
Route::post('/organisasi-hapus-data', [OraganizationController::class, 'hapusData'])->name('organisasi-hapus-data')->middleware('auth');
Route::get('/organisasi-response-ss/{id}', [OraganizationController::class, 'responseSS'])->name('organisasi-response-ss')->middleware('auth');


Route::get('/lokasi', [LocationController::class, 'index'])->name('lokasi')->middleware('auth');
Route::get('/lokasi-tambah', [LocationController::class, 'tambah'])->name('lokasi-tambah')->middleware('auth');
Route::post('/lokasi-simpan', [LocationController::class, 'simpan'])->name('lokasi-simpan')->middleware('auth');
Route::get('/lokasi-struktur', [LocationController::class, 'struktur'])->name('lokasi-struktur')->middleware('auth');
Route::get('/lokasi-ubah/{id}', [LocationController::class, 'ubah'])->name('lokasi-ubah')->middleware('auth');
Route::post('/lokasi-update', [LocationController::class, 'update'])->name('lokasi-update')->middleware('auth');
Route::get('/lokasi-hapus/{id}', [LocationController::class, 'hapus'])->name('lokasi-hapus')->middleware('auth');
Route::post('/lokasi-hapus-data', [LocationController::class, 'hapusData'])->name('lokasi-hapus-data')->middleware('auth');
Route::get('/lokasi-response-ss/{id}', [LocationController::class, 'responseSS'])->name('lokasi-response-ss')->middleware('auth');

Route::get('/pasien', [PatientController::class, 'index'])->name('pasien')->middleware('auth');
Route::get('/pasien-response-ss/{id}', [PatientController::class, 'responseSS'])->name('pasien-response-ss')->middleware('auth');
Route::get('/pasien-ubah-ihs/{id}', [PatientController::class, 'ubahIHS'])->name('pasien-ubah-ihs')->middleware('auth');
Route::post('/pasien-update-ihs', [PatientController::class, 'updateIHS'])->name('pasien-update-ihs')->middleware('auth');
Route::get('/pasien-ubah/{id}', [PatientController::class, 'ubah'])->name('pasien-ubah')->middleware('auth');
Route::post('/pasien-update', [PatientController::class, 'update'])->name('pasien-update')->middleware('auth');

Route::get('/praktisi', [PracticionerController::class, 'index'])->name('praktisi')->middleware('auth');
Route::get('/praktisi-response-ss/{id}', [PracticionerController::class, 'responseSS'])->name('praktisi-response-ss')->middleware('auth');
Route::get('/praktisi-ubah-ihs/{id}', [PracticionerController::class, 'ubahIHS'])->name('praktisi-ubah-ihs')->middleware('auth');
Route::post('/praktisi-update-ihs', [PracticionerController::class, 'updateIHS'])->name('praktisi-update-ihs')->middleware('auth');
Route::get('/praktisi-ubah/{id}', [PracticionerController::class, 'ubah'])->name('praktisi-ubah')->middleware('auth');
Route::post('/praktisi-update', [PracticionerController::class, 'update'])->name('praktisi-update')->middleware('auth');

Route::get('/kfa', [KfaController::class, 'index'])->name('kfa')->middleware('auth');
Route::get('/medication', [MedicationController::class, 'index'])->name('medication')->middleware('auth');
Route::get('/medication-kfa/{id}', [MedicationController::class, 'modalKfa'])->name('medication-kfa')->middleware('auth');
Route::get('/medication-kfa-data/{id}', [MedicationController::class, 'getDataKFa'])->name('medication-kfa-data')->middleware('auth');
Route::get('/medication-kfa-update', [MedicationController::class, 'update'])->name('medication-kfa-update')->middleware('auth');
