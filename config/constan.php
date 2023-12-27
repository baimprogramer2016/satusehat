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
        ],
    ],
    'txt' => [
        "organization_name" => "RSUD Cileungsi",
        "search" => 'Search',
        "no_status" => 'Tidak Ada Status',
        "no_data" => 'Tidak Ada Data',
    ],
    'default_organization' => [
        "type_code" => "dept",
        "type_display" => "Hospital Departement",
        "province_code" => 64,
        "city_code" => 6474,
        "district_code" => 647402,
        "village_code" => 6474021004,
        "address" => "RS LNG BADAK Kel. Satimpo, Kec. Bontang Selatan, Bontang - 75324, Kalimantan Timur",
        "postal_code" => 75324,
        "country_code" => "ID",
        "city" => "Kota Bontang",
        "url" => "https://www.rslngbadak.co.id/",
        "email" => "humasmarketing.rslngbadak@gmail.com",
        "phone" => "0548-552049",
        "fax" => "0548-552128",
        "rt" => "1",
        "rw" => "1",
        "position_longitude" => 0.11768,
        "position_latitude" => 117.47088,
        "position_altitude" => 0
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
    ]
];
