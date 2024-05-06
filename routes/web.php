<?php

use App\Http\Controllers\BundleController;
use App\Http\Controllers\BundleDevController;
use App\Http\Controllers\CategoryRequestController;
use App\Http\Controllers\CompositionController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosticReportController;
use App\Http\Controllers\EncounterController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JobLogsController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\KfaController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LogErrorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LoincController;
use App\Http\Controllers\MasterProcedureController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\MedicationDispenseController;
use App\Http\Controllers\MedicationRequestController;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\ObservationLabController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PractitionerController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\ServiceRequestRadiologyController;
use App\Http\Controllers\SinkronisasiController;
use App\Http\Controllers\SnomedController;
use App\Http\Controllers\SpecimenController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;
use App\Models\Encounter;



/*
Route Application
*/

# MANUAL - DEBUG
// Route::get('/queue', [QueueController::class, 'index'])->name('queue');
// Route::get('/query', [SinkronisasiController::class, 'query'])->name('query');
Route::get('/tes-sinkronisasi', [SinkronisasiController::class, 'tesRunJob'])->name('tesRunJob');

# KFA
// Route::get('/get-kfa', [KfaController::class, 'getKfa'])->name('get-kfa')->middleware('auth');

# BUNDLE
Route::get('/bundle', [BundleDevController::class, 'runJob'])->name('bundle')->middleware('auth');

Route::get('/aloneJob/{param_id_jadwal}', [MedicationRequestController::class, 'runJob']);

#Sinkronisasi
// Route::get('/sinkronisasi-tes', [SinkronisasiController::class, 'tes'])->name('sinkronisasi-tes')->middleware('auth');


/*
Route Application
*/

Route::get('/', [LoginController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/proses-login', [LoginController::class, 'authenticate'])->name('proses-login');
Route::get('/proses-logout', [LoginController::class, 'signOut'])->name('proses-logout');
Route::get('/dashboard-laporan', [DashboardController::class, 'laporan'])->name('dashboard-laporan')->middleware('auth');
Route::get('/dashboard-service', [DashboardController::class, 'runService'])->name('dashboard-service')->middleware('auth');
Route::get('/dashboard-laporan-download', [DashboardController::class, 'laporanDownload'])->name('dashboard-laporan-download')->middleware('auth');

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

Route::get('/snomed', [SnomedController::class, 'index'])->name('snomed')->middleware('auth');
Route::get('/snomed-tambah', [SnomedController::class, 'tambah'])->name('snomed-tambah')->middleware('auth');
Route::post('/snomed-simpan', [SnomedController::class, 'simpan'])->name('snomed-simpan')->middleware('auth');
Route::get('/snomed-hapus/{id}', [SnomedController::class, 'hapus'])->name('snomed-hapus')->middleware('auth');
Route::post('/snomed-hapus-data', [SnomedController::class, 'hapusData'])->name('snomed-hapus-data')->middleware('auth');
Route::get('/snomed-ubah/{id}', [SnomedController::class, 'ubah'])->name('snomed-ubah')->middleware('auth');
Route::post('/snomed-update', [SnomedController::class, 'update'])->name('snomed-update')->middleware('auth');

Route::get('/loinc', [LoincController::class, 'index'])->name('loinc')->middleware('auth');
Route::get('/loinc-tambah', [LoincController::class, 'tambah'])->name('loinc-tambah')->middleware('auth');
Route::post('/loinc-simpan', [LoincController::class, 'simpan'])->name('loinc-simpan')->middleware('auth');
Route::get('/loinc-hapus/{id}', [LoincController::class, 'hapus'])->name('loinc-hapus')->middleware('auth');
Route::post('/loinc-hapus-data', [LoincController::class, 'hapusData'])->name('loinc-hapus-data')->middleware('auth');
Route::get('/loinc-ubah/{id}', [LoincController::class, 'ubah'])->name('loinc-ubah')->middleware('auth');
Route::post('/loinc-update', [LoincController::class, 'update'])->name('loinc-update')->middleware('auth');

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
Route::post('/pasien-run-job/{param_id_jadwal}', [PatientController::class, 'runJob'])->name('pasien-run-job')->middleware('auth');
Route::get('/pasien-tambah', [PatientController::class, 'tambah'])->name('pasien-tambah')->middleware('auth');
Route::get('/pasien-check-nik', [PatientController::class, 'checkNik'])->name('pasien-check-nik')->middleware('auth');
Route::post('/pasien-simpan', [PatientController::class, 'storePatient'])->name('pasien-simpan')->middleware('auth');

Route::get('/praktisi', [PractitionerController::class, 'index'])->name('praktisi')->middleware('auth');
Route::get('/praktisi-response-ss/{id}', [PractitionerController::class, 'responseSS'])->name('praktisi-response-ss')->middleware('auth');
Route::get('/praktisi-ubah-ihs/{id}', [PractitionerController::class, 'ubahIHS'])->name('praktisi-ubah-ihs')->middleware('auth');
Route::post('/praktisi-update-ihs', [PractitionerController::class, 'updateIHS'])->name('praktisi-update-ihs')->middleware('auth');
Route::get('/praktisi-ubah/{id}', [PractitionerController::class, 'ubah'])->name('praktisi-ubah')->middleware('auth');
Route::post('/praktisi-update', [PractitionerController::class, 'update'])->name('praktisi-update')->middleware('auth');
Route::post('/praktisi-run-job/{param_id_jadwal}', [PractitionerController::class, 'runJob'])->name('praktisi-run-job')->middleware('auth');
Route::get('/praktisi-tambah', [PractitionerController::class, 'tambah'])->name('praktisi-tambah')->middleware('auth');
Route::get('/praktisi-check-nik', [PractitionerController::class, 'checkNik'])->name('praktisi-check-nik')->middleware('auth');
Route::post('/praktisi-simpan', [PractitionerController::class, 'storePractitioner'])->name('praktisi-simpan')->middleware('auth');

Route::get('/kfa', [KfaController::class, 'index'])->name('kfa')->middleware('auth');
Route::get('/medication', [MedicationController::class, 'index'])->name('medication')->middleware('auth');
Route::get('/medication-kfa/{id}', [MedicationController::class, 'modalKfa'])->name('medication-kfa')->middleware('auth');
Route::get('/medication-kfa-data/{id}', [MedicationController::class, 'getDataKFa'])->name('medication-kfa-data')->middleware('auth');
Route::post('/medication-kfa-update', [MedicationController::class, 'updateKfa'])->name('medication-kfa-update')->middleware('auth');

Route::get('/medication-request', [MedicationRequestController::class, 'index'])->name('medication-request')->middleware('auth');
Route::get('/medication-request-response-ss/{id}', [MedicationRequestController::class, 'responseSS'])->name('medication-request-response-ss')->middleware('auth');
Route::get('/medication-request-modal-kirim-ss/{id}', [MedicationRequestController::class, 'modalKirimSS'])->name('medication-request-modal-kirim-ss')->middleware('auth');
Route::post('/medication-request-kirim-ss/{id}', [MedicationRequestController::class, 'kirimSS'])->name('medication-request-kirim-ss')->middleware('auth');

Route::get('/medication-dispense', [MedicationDispenseController::class, 'index'])->name('medication-dispense')->middleware('auth');
Route::get('/medication-dispense-response-ss/{id}', [MedicationDispenseController::class, 'responseSS'])->name('medication-dispense-response-ss')->middleware('auth');
Route::get('/medication-dispense-modal-kirim-ss/{id}', [MedicationDispenseController::class, 'modalKirimSS'])->name('medication-dispense-modal-kirim-ss')->middleware('auth');
Route::post('/medication-dispense-kirim-ss/{id}', [MedicationDispenseController::class, 'kirimSS'])->name('medication-dispense-kirim-ss')->middleware('auth');

Route::get('/encounter', [EncounterController::class, 'index'])->name('encounter')->middleware('auth');
Route::get('/encounter-detail/{original_code}', [EncounterController::class, 'detail'])->name('encounter-detail')->middleware('auth');
Route::get('/encounter-response-ss/{id}', [EncounterController::class, 'responseSS'])->name('encounter-response-ss')->middleware('auth');
Route::get('/encounter-modal-kirim-ss/{id}', [EncounterController::class, 'modalKirimSS'])->name('encounter-modal-kirim-ss')->middleware('auth');
Route::post('/encounter-kirim-ss/{id}', [EncounterController::class, 'kirimSS'])->name('encounter-kirim-ss')->middleware('auth');
Route::get('/encounter-modal-update-ss/{id}', [EncounterController::class, 'modalUpdateSS'])->name('encounter-modal-update-ss')->middleware('auth');
Route::post('/encounter-update-ss/{id}', [EncounterController::class, 'updateSS'])->name('encounter-update-ss')->middleware('auth');
Route::get('/encounter-tambah', [EncounterController::class, 'formTambah'])->name('encounter-tambah')->middleware('auth');
Route::get('/encounter-check-nik', [EncounterController::class, 'checkNik'])->name('encounter-check-nik')->middleware('auth');
Route::post('/encounter-simpan', [EncounterController::class, 'saveEncounter'])->name('encounter-simpan')->middleware('auth');
Route::get('/encounter-edit/{id}', [EncounterController::class, 'formEdit'])->name('encounter-edit')->middleware('auth');
Route::post('/encounter-update/{id}', [EncounterController::class, 'updateEncounter'])->name('encounter-update')->middleware('auth');


Route::get('/condition', [ConditionController::class, 'index'])->name('condition')->middleware('auth');
Route::get('/condition-response-ss/{id}', [ConditionController::class, 'responseSS'])->name('condition-response-ss')->middleware('auth');
Route::get('/condition-modal-kirim-ss/{id}', [ConditionController::class, 'modalKirimSS'])->name('condition-modal-kirim-ss')->middleware('auth');
Route::post('/condition-kirim-ss/{id}', [ConditionController::class, 'kirimSS'])->name('condition-kirim-ss')->middleware('auth');
Route::get('/condition-tambah', [ConditionController::class, 'formTambah'])->name('condition-tambah')->middleware('auth');
Route::post('/condition-simpan', [ConditionController::class, 'saveCondition'])->name('condition-simpan')->middleware('auth');
Route::get('/condition-search-icd-10/{id}', [ConditionController::class, 'searchIcd10'])->name('condition-search-icd-10')->middleware('auth');
Route::get('/condition-edit/{id}', [ConditionController::class, 'formEdit'])->name('condition-edit')->middleware('auth');
Route::post('/condition-update/{id}', [ConditionController::class, 'updateCondition'])->name('condition-update')->middleware('auth');

Route::get('/observation', [ObservationController::class, 'index'])->name('observation')->middleware('auth');
Route::get('/observation-response-ss/{id}', [ObservationController::class, 'responseSS'])->name('observation-response-ss')->middleware('auth');
Route::get('/observation-modal-kirim-ss/{id}', [ObservationController::class, 'modalKirimSS'])->name('observation-modal-kirim-ss')->middleware('auth');
Route::post('/observation-kirim-ss/{id}', [ObservationController::class, 'kirimSS'])->name('observation-kirim-ss')->middleware('auth');
Route::get('/observation-tambah', [ObservationController::class, 'formTambah'])->name('observation-tambah')->middleware('auth');
Route::post('/observation-simpan', [ObservationController::class, 'saveObservation'])->name('observation-simpan')->middleware('auth');
Route::get('/observation-edit/{id}', [ObservationController::class, 'formEdit'])->name('observation-edit')->middleware('auth');
Route::post('/observation-update/{id}', [ObservationController::class, 'updateObservation'])->name('observation-update')->middleware('auth');

Route::get('/procedure', [ProcedureController::class, 'index'])->name('procedure')->middleware('auth');
Route::get('/procedure-response-ss/{id}', [ProcedureController::class, 'responseSS'])->name('procedure-response-ss')->middleware('auth');
Route::get('/procedure-modal-kirim-ss/{id}', [ProcedureController::class, 'modalKirimSS'])->name('procedure-modal-kirim-ss')->middleware('auth');
Route::post('/procedure-kirim-ss/{id}', [ProcedureController::class, 'kirimSS'])->name('procedure-kirim-ss')->middleware('auth');

Route::get('/composition', [CompositionController::class, 'index'])->name('composition')->middleware('auth');
Route::get('/composition-response-ss/{id}', [CompositionController::class, 'responseSS'])->name('composition-response-ss')->middleware('auth');
Route::get('/composition-detail/{id}', [CompositionController::class, 'detail'])->name('composition-detail')->middleware('auth');
Route::get('/composition-modal-kirim-ss/{id}', [CompositionController::class, 'modalKirimSS'])->name('composition-modal-kirim-ss')->middleware('auth');
Route::post('/composition-kirim-ss/{id}', [CompositionController::class, 'kirimSS'])->name('composition-kirim-ss')->middleware('auth');


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
Route::get('/atur-bundle', [JadwalController::class, 'aturBundle'])->name('atur-bundle')->middleware('auth');
Route::post('/atur-bundle-update/{resource}', [JadwalController::class, 'aturBundleUpdate'])->name('atur-bundle-update')->middleware('auth');


Route::get('/jobs', [JobsController::class, 'index'])->name('jobs')->middleware('auth');
Route::get('/job-logs', [JobLogsController::class, 'index'])->name('job-logs')->middleware('auth');

Route::get('/akun/{username}', [LoginController::class, 'ubahAkun'])->name('akun')->middleware('auth');
Route::post('/akun-simpan', [LoginController::class, 'simpanAkun'])->name('akun-simpan')->middleware('auth');

Route::get('/upload', [UploadController::class, 'index'])->name('upload')->middleware('auth');
Route::get('/upload-ubah/{id}', [UploadController::class, 'ubah'])->name('upload-ubah')->middleware('auth');
Route::post('/upload-update', [UploadController::class, 'update'])->name('upload-update')->middleware('auth');


Route::get('/master-procedure', [MasterProcedureController::class, 'index'])->name('master-procedure')->middleware('auth');
Route::get('/master-procedure-snomed/{id}', [MasterProcedureController::class, 'modalSnomed'])->name('master-procedure-snomed')->middleware('auth');
Route::get('/master-procedure-snomed-data/{id}', [MasterProcedureController::class, 'getDataSnomed'])->name('master-procedure-snomed-data')->middleware('auth');
Route::post('/master-procedure-snomed-update', [MasterProcedureController::class, 'updateSnomed'])->name('master-procedure-snomed-update')->middleware('auth');
Route::get('/master-procedure-loinc/{id}', [MasterProcedureController::class, 'modalLoinc'])->name('master-procedure-loinc')->middleware('auth');
Route::get('/master-procedure-loinc-data/{id}', [MasterProcedureController::class, 'getDataLoinc'])->name('master-procedure-loinc-data')->middleware('auth');
Route::post('/master-procedure-loinc-update', [MasterProcedureController::class, 'updateLoinc'])->name('master-procedure-loinc-update')->middleware('auth');
Route::get('/master-procedure-category/{id}', [MasterProcedureController::class, 'modalCategory'])->name('master-procedure-category')->middleware('auth');
Route::get('/master-procedure-category-data/{id}', [MasterProcedureController::class, 'getDataCategory'])->name('master-procedure-category-data')->middleware('auth');
Route::post('/master-procedure-category-update', [MasterProcedureController::class, 'updateCategory'])->name('master-procedure-category-update')->middleware('auth');

Route::get('/category-request', [CategoryRequestController::class, 'index'])->name('category-request')->middleware('auth');
Route::get('/category-request-ubah/{display}', [CategoryRequestController::class, 'ubah'])->name('category-request-ubah')->middleware('auth');
Route::post('/category-request-update', [CategoryRequestController::class, 'update'])->name('category-request-update')->middleware('auth');

Route::get('/service-request', [ServiceRequestController::class, 'index'])->name('service-request')->middleware('auth');
Route::get('/service-request-response-ss/{id}', [ServiceRequestController::class, 'responseSS'])->name('service-request-response-ss')->middleware('auth');
Route::get('/service-request-modal-kirim-ss/{id}', [ServiceRequestController::class, 'modalKirimSS'])->name('service-request-modal-kirim-ss')->middleware('auth');
Route::post('/service-request-kirim-ss/{id}', [ServiceRequestController::class, 'kirimSS'])->name('service-request-kirim-ss')->middleware('auth');


Route::get('/specimen', [SpecimenController::class, 'index'])->name('specimen')->middleware('auth');
Route::get('/specimen-response-ss/{id}', [SpecimenController::class, 'responseSS'])->name('specimen-response-ss')->middleware('auth');
Route::get('/specimen-modal-kirim-ss/{id}', [SpecimenController::class, 'modalKirimSS'])->name('specimen-modal-kirim-ss')->middleware('auth');
Route::post('/specimen-kirim-ss/{id}', [SpecimenController::class, 'kirimSS'])->name('specimen-kirim-ss')->middleware('auth');


Route::get('/observation-lab', [ObservationLabController::class, 'index'])->name('observation-lab')->middleware('auth');
Route::get('/observation-lab-response-ss/{id}', [ObservationLabController::class, 'responseSS'])->name('observation-lab-response-ss')->middleware('auth');
Route::get('/observation-lab-modal-kirim-ss/{uuid}', [ObservationLabController::class, 'modalKirimSS'])->name('observation-lab-modal-kirim-ss')->middleware('auth');
Route::post('/observation-lab-kirim-ss/{uuid}', [ObservationLabController::class, 'kirimSS'])->name('observation-lab-kirim-ss')->middleware('auth');


Route::get('/diagnostic-report', [DiagnosticReportController::class, 'index'])->name('diagnostic-report')->middleware('auth');
Route::get('/diagnostic-report-ss/{id}', [DiagnosticReportController::class, 'responseSS'])->name('diagnostic-report-response-ss')->middleware('auth');
Route::get('/diagnostic-report-modal-kirim-ss/{id}', [DiagnosticReportController::class, 'modalKirimSS'])->name('diagnostic-report-modal-kirim-ss')->middleware('auth');
Route::post('/diagnostic-report-kirim-ss/{id}', [DiagnosticReportController::class, 'kirimSS'])->name('diagnostic-report-kirim-ss')->middleware('auth');

Route::get('/log-error', [LogErrorController::class, 'index'])->name('log-error')->middleware('auth');

Route::get('/service-request-radiology', [ServiceRequestRadiologyController::class, 'index'])->name('service-request-radiology')->middleware('auth');
Route::get('/service-request-radiology-modal-kirim-ss/{id}', [ServiceRequestRadiologyController::class, 'modalKirimSS'])->name('service-request-radiology-modal-kirim-ss')->middleware('auth');
Route::post('/service-request-radiology-kirim-ss/{id}', [ServiceRequestRadiologyController::class, 'kirimSS'])->name('service-request-radiology-kirim-ss')->middleware('auth');
Route::get('/service-request-radiology-response-ss/{id}', [ServiceRequestRadiologyController::class, 'responseSS'])->name('service-request-radiology-response-ss')->middleware('auth');
