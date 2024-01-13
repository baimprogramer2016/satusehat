<?php

use App\Http\Controllers\BundleController;
use App\Http\Controllers\BundleDevController;
use App\Http\Controllers\CompositionControlller;
use App\Http\Controllers\ConditionControlller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EncounterController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JobLogsController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\KfaController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\MedicationRequestController;
use App\Http\Controllers\ObservationControlller;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PractitionerController;
use App\Http\Controllers\ProcedureControlller;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\SinkronisasiController;
use App\Http\Controllers\UploadController;
use App\Models\Condition;
use Illuminate\Support\Facades\Route;



/*
Route Application
*/

# MANUAL - DEBUG
// Route::get('/queue', [QueueController::class, 'index'])->name('queue');
// Route::get('/query', [SinkronisasiController::class, 'query'])->name('query');

# KFA
// Route::get('/get-kfa', [KfaController::class, 'getKfa'])->name('get-kfa')->middleware('auth');

# BUNDLE
Route::get('/bundle', [BundleDevController::class, 'runJob'])->name('bundle')->middleware('auth');

#Sinkronisasi
// Route::get('/sinkronisasi-tes', [SinkronisasiController::class, 'tes'])->name('sinkronisasi-tes')->middleware('auth');


/*
Route Application
*/

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware('auth');

Route::get('/', [LoginController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/proses-login', [LoginController::class, 'authenticate'])->name('proses-login');
Route::get('/proses-logout', [LoginController::class, 'signOut'])->name('proses-logout');

Route::get('/parameter', [ParameterController::class, 'index'])->name('parameter')->middleware('auth');
Route::get('/parameter-ubah', [ParameterController::class, 'ubah'])->name('parameter-ubah')->middleware('auth');
Route::post('/parameter-update', [ParameterController::class, 'update'])->name('parameter-update')->middleware('auth');
Route::get('/parameter-lihat', [ParameterController::class, 'lihat'])->name('parameter-lihat')->middleware('auth');

Route::get('/organisasi', [OrganizationController::class, 'index'])->name('organisasi')->middleware('auth');
Route::get('/organisasi-tambah', [OrganizationController::class, 'tambah'])->name('organisasi-tambah')->middleware('auth');
Route::get('/organisasi-struktur', [OrganizationController::class, 'struktur'])->name('organisasi-struktur')->middleware('auth');
Route::post('/organisasi-simpan', [OrganizationController::class, 'simpan'])->name('organisasi-simpan')->middleware('auth');
Route::get('/organisasi-struktur', [OrganizationController::class, 'struktur'])->name('organisasi-struktur')->middleware('auth');
Route::get('/organisasi-ubah/{id}', [OrganizationController::class, 'ubah'])->name('organisasi-ubah')->middleware('auth');
Route::post('/organisasi-update', [OrganizationController::class, 'update'])->name('organisasi-update')->middleware('auth');
Route::get('/organisasi-hapus/{id}', [OrganizationController::class, 'hapus'])->name('organisasi-hapus')->middleware('auth');
Route::post('/organisasi-hapus-data', [OrganizationController::class, 'hapusData'])->name('organisasi-hapus-data')->middleware('auth');
Route::get('/organisasi-response-ss/{id}', [OrganizationController::class, 'responseSS'])->name('organisasi-response-ss')->middleware('auth');
Route::get('/organisasi-modal-kirim-ss/{id}', [OrganizationController::class, 'modalKirimSS'])->name('organisasi-modal-kirim-ss')->middleware('auth');
Route::post('/organisasi-kirim-ss/{id}', [OrganizationController::class, 'kirimSS'])->name('organisasi-kirim-ss')->middleware('auth');


Route::get('/lokasi', [LocationController::class, 'index'])->name('lokasi')->middleware('auth');
Route::get('/lokasi-tambah', [LocationController::class, 'tambah'])->name('lokasi-tambah')->middleware('auth');
Route::post('/lokasi-simpan', [LocationController::class, 'simpan'])->name('lokasi-simpan')->middleware('auth');
Route::get('/lokasi-struktur', [LocationController::class, 'struktur'])->name('lokasi-struktur')->middleware('auth');
Route::get('/lokasi-ubah/{id}', [LocationController::class, 'ubah'])->name('lokasi-ubah')->middleware('auth');
Route::post('/lokasi-update', [LocationController::class, 'update'])->name('lokasi-update')->middleware('auth');
Route::get('/lokasi-hapus/{id}', [LocationController::class, 'hapus'])->name('lokasi-hapus')->middleware('auth');
Route::post('/lokasi-hapus-data', [LocationController::class, 'hapusData'])->name('lokasi-hapus-data')->middleware('auth');
Route::get('/lokasi-response-ss/{id}', [LocationController::class, 'responseSS'])->name('lokasi-response-ss')->middleware('auth');
Route::get('/lokasi-modal-kirim-ss/{id}', [LocationController::class, 'modalKirimSS'])->name('lokasi-modal-kirim-ss')->middleware('auth');
Route::post('/lokasi-kirim-ss/{id}', [LocationController::class, 'kirimSS'])->name('lokasi-kirim-ss')->middleware('auth');


Route::get('/pasien', [PatientController::class, 'index'])->name('pasien')->middleware('auth');
Route::get('/pasien-response-ss/{id}', [PatientController::class, 'responseSS'])->name('pasien-response-ss')->middleware('auth');
Route::get('/pasien-ubah-ihs/{id}', [PatientController::class, 'ubahIHS'])->name('pasien-ubah-ihs')->middleware('auth');
Route::post('/pasien-update-ihs', [PatientController::class, 'updateIHS'])->name('pasien-update-ihs')->middleware('auth');
Route::get('/pasien-ubah/{id}', [PatientController::class, 'ubah'])->name('pasien-ubah')->middleware('auth');
Route::post('/pasien-update', [PatientController::class, 'update'])->name('pasien-update')->middleware('auth');
Route::post('/pasien-run-job', [PatientController::class, 'runJob'])->name('pasien-run-job')->middleware('auth');

Route::get('/praktisi', [PractitionerController::class, 'index'])->name('praktisi')->middleware('auth');
Route::get('/praktisi-response-ss/{id}', [PractitionerController::class, 'responseSS'])->name('praktisi-response-ss')->middleware('auth');
Route::get('/praktisi-ubah-ihs/{id}', [PractitionerController::class, 'ubahIHS'])->name('praktisi-ubah-ihs')->middleware('auth');
Route::post('/praktisi-update-ihs', [PractitionerController::class, 'updateIHS'])->name('praktisi-update-ihs')->middleware('auth');
Route::get('/praktisi-ubah/{id}', [PractitionerController::class, 'ubah'])->name('praktisi-ubah')->middleware('auth');
Route::post('/praktisi-update', [PractitionerController::class, 'update'])->name('praktisi-update')->middleware('auth');
Route::post('/praktisi-run-job', [PractitionerController::class, 'runJob'])->name('praktisi-run-job')->middleware('auth');

Route::get('/kfa', [KfaController::class, 'index'])->name('kfa')->middleware('auth');
Route::get('/medication', [MedicationController::class, 'index'])->name('medication')->middleware('auth');
Route::get('/medication-kfa/{id}', [MedicationController::class, 'modalKfa'])->name('medication-kfa')->middleware('auth');
Route::get('/medication-kfa-data/{id}', [MedicationController::class, 'getDataKFa'])->name('medication-kfa-data')->middleware('auth');
Route::post('/medication-kfa-update', [MedicationController::class, 'updateKfa'])->name('medication-kfa-update')->middleware('auth');

Route::get('/medication-request', [MedicationRequestController::class, 'index'])->name('medication-request')->middleware('auth');
Route::get('/medication-request-response-ss/{id}', [MedicationRequestController::class, 'responseSS'])->name('medication-request-response-ss')->middleware('auth');


Route::get('/encounter', [EncounterController::class, 'index'])->name('encounter')->middleware('auth');
Route::get('/encounter-detail/{original_code}', [EncounterController::class, 'detail'])->name('encounter-detail')->middleware('auth');
Route::get('/encounter-response-ss/{id}', [EncounterController::class, 'responseSS'])->name('encounter-response-ss')->middleware('auth');
Route::get('/encounter-modal-kirim-ss/{id}', [EncounterController::class, 'modalKirimSS'])->name('encounter-modal-kirim-ss')->middleware('auth');
Route::post('/encounter-kirim-ss/{id}', [EncounterController::class, 'kirimSS'])->name('encounter-kirim-ss')->middleware('auth');
Route::get('/encounter-modal-update-ss/{id}', [EncounterController::class, 'modalUpdateSS'])->name('encounter-modal-update-ss')->middleware('auth');
Route::post('/encounter-update-ss/{id}', [EncounterController::class, 'updateSS'])->name('encounter-update-ss')->middleware('auth');

Route::get('/condition', [ConditionControlller::class, 'index'])->name('condition')->middleware('auth');
Route::get('/condition-response-ss/{id}', [ConditionControlller::class, 'responseSS'])->name('condition-response-ss')->middleware('auth');
Route::get('/condition-modal-kirim-ss/{id}', [ConditionControlller::class, 'modalKirimSS'])->name('condition-modal-kirim-ss')->middleware('auth');
Route::post('/condition-kirim-ss/{id}', [ConditionControlller::class, 'kirimSS'])->name('condition-kirim-ss')->middleware('auth');

Route::get('/observation', [ObservationControlller::class, 'index'])->name('observation')->middleware('auth');
Route::get('/observation-response-ss/{id}', [ObservationControlller::class, 'responseSS'])->name('observation-response-ss')->middleware('auth');
Route::get('/observation-modal-kirim-ss/{id}', [ObservationControlller::class, 'modalKirimSS'])->name('observation-modal-kirim-ss')->middleware('auth');
Route::post('/observation-kirim-ss/{id}', [ObservationControlller::class, 'kirimSS'])->name('observation-kirim-ss')->middleware('auth');

Route::get('/procedure', [ProcedureControlller::class, 'index'])->name('procedure')->middleware('auth');
Route::get('/procedure-response-ss/{id}', [ProcedureControlller::class, 'responseSS'])->name('procedure-response-ss')->middleware('auth');
Route::get('/procedure-modal-kirim-ss/{id}', [ProcedureControlller::class, 'modalKirimSS'])->name('procedure-modal-kirim-ss')->middleware('auth');
Route::post('/procedure-kirim-ss/{id}', [ProcedureControlller::class, 'kirimSS'])->name('procedure-kirim-ss')->middleware('auth');

Route::get('/composition', [CompositionControlller::class, 'index'])->name('composition')->middleware('auth');
Route::get('/composition-response-ss/{id}', [CompositionControlller::class, 'responseSS'])->name('composition-response-ss')->middleware('auth');
Route::get('/composition-detail/{id}', [CompositionControlller::class, 'detail'])->name('composition-detail')->middleware('auth');
Route::get('/composition-modal-kirim-ss/{id}', [CompositionControlller::class, 'modalKirimSS'])->name('composition-modal-kirim-ss')->middleware('auth');
Route::post('/composition-kirim-ss/{id}', [CompositionControlller::class, 'kirimSS'])->name('composition-kirim-ss')->middleware('auth');


Route::get('/sinkronisasi', [SinkronisasiController::class, 'index'])->name('sinkronisasi')->middleware('auth');
Route::get('/sinkronisasi-tambah', [SinkronisasiController::class, 'tambah'])->name('sinkronisasi-tambah')->middleware('auth');
Route::post('/sinkronisasi-simpan', [SinkronisasiController::class, 'simpan'])->name('sinkronisasi-simpan')->middleware('auth');
Route::get('/sinkronisasi-ubah/{id}', [SinkronisasiController::class, 'ubah'])->name('sinkronisasi-ubah')->middleware('auth');
Route::post('/sinkronisasi-update', [SinkronisasiController::class, 'update'])->name('sinkronisasi-update')->middleware('auth');
Route::post('/sinkronisasi-query', [SinkronisasiController::class, 'query'])->name('sinkronisasi-query')->middleware('auth');
Route::get('/sinkronisasi-hapus/{id}', [SinkronisasiController::class, 'hapus'])->name('sinkronisasi-hapus')->middleware('auth');
Route::post('/sinkronisasi-hapus-data', [SinkronisasiController::class, 'hapusData'])->name('sinkronisasi-hapus-data')->middleware('auth');
Route::post('/sinkronisasi-run/{param_id_sinkronisasi}', [SinkronisasiController::class, 'runJob'])->name('sinkronisasi-run')->middleware('auth');

Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal')->middleware('auth');
Route::get('/jadwal-ubah/{id}', [JadwalController::class, 'ubah'])->name('jadwal-ubah')->middleware('auth');
Route::post('/jadwal-update', [JadwalController::class, 'update'])->name('jadwal-update')->middleware('auth');

Route::get('/jobs', [JobsController::class, 'index'])->name('jobs')->middleware('auth');
Route::get('/job-logs', [JobLogsController::class, 'index'])->name('job-logs')->middleware('auth');

Route::get('/akun/{username}', [LoginController::class, 'ubahAkun'])->name('akun')->middleware('auth');
Route::post('/akun-simpan', [LoginController::class, 'simpanAkun'])->name('akun-simpan')->middleware('auth');

Route::get('/upload', [UploadController::class, 'index'])->name('upload')->middleware('auth');
Route::get('/upload-ubah/{id}', [UploadController::class, 'ubah'])->name('upload-ubah')->middleware('auth');
Route::post('/upload-update', [UploadController::class, 'update'])->name('upload-update')->middleware('auth');
