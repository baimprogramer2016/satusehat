<?php

return [
    "message" => [
        "validasi" => [
            'required' => ':attribute tidak boleh kosong',
            'kombinasi_auth' => "Kombinasi Username dan Password Salah"
        ],
        "form" => [
            'success_updated' => 'Proses Update Berhasil',
            'success_saved' => 'Proses Simpan Berhasil',
            'success_delete' => 'Proses Hapus Berhasil',
            'error_type' => 'Tipe File Harus Sesuai',
        ],
    ],
    'txt' => [
        "organization_name" => env('corporate'),
        "search" => 'Search',
        "no_status" => 'Tidak Ada Status',
        "no_data" => 'Tidak Ada Data',
    ],
    'default_organization' => [
        "type_code" => env('type_code'),
        "type_display" => env('type_display'),
        "province_code" => env('province_code'),
        "city_code" => env('city_code'),
        "district_code" => env('district_code'),
        "village_code" => env('village_code'),
        "address" => env('address'),
        "postal_code" => env('postal_code'),
        "country_code" => env('country_code'),
        "city" => env('city'),
        "url" => env('url'),
        "email" => env('email'),
        "phone" => env('phone'),
        "fax" => env('fax'),
        "rt" => env('rt'),
        "rw" => env('rw'),
        "position_longitude" => env('position_longitude'),
        "position_latitude" => env('position_latitude'),
        "position_altitude" => env('position_altitude')
    ],
    "limit" => [
        "row_table" => 20,
    ],
    "error_message" => [
        "failed_catch" => "Terjadi Kesalahan",
        "failed_success" => "Proses Sukses",
        "failed_ss_1" => "TIdak bisa dihapus, data sudah dikirm ke satu sehat",
        "message_waiting" => "Please Wait....",
        "id_ihs_error" => "ID IHS Tidak Ditemukan ",
        "toast_job_already" => "Gagal - Job Sudah ada dalam Proses Antrian",
        "toast_job_running" => "Job dijalankan",
        "toast_job_data_update" => "Data Sudah Update Semua",
        "toast_job_data_no" => "Tidak Ada Jadwal Sinkronisasi",
        "error_encounter_no" => "Encounter Harus di kirim Terlebih Dahulu",
        "error_medication_request_no" => "Medication Request Harus di kirim Terlebih Dahulu",
    ],
    "fhir" => [
        "patient" => "Patient?identifier=https://fhir.kemkes.go.id/id/nik",
        "practitioner" => "Practitioner?identifier=https://fhir.kemkes.go.id/id/nik",
    ],
    "sinkronisasi" => [
        "run" => "App\Http\Controllers\SinkronisasiController@runJob"
    ],
    "status" => [
        "terkirim" => "<span class='text-success'>Terkirim ke Satu Sehat</span>",
        "menunggu" => "<span class='text-warning'>Menunggu</span>",
    ],
    "job_name" => [
        "patient" => "patient_job",
        "practitioner" => "practitioner_job",
        "bundle" => "bundle_job",
        "sync" => "sync_job",
        "job_manual" => "manual",
        "job_scheduler" => "scheduler",
    ],
];
