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
        "province_code" => 32,
        "city_code" => 3201,
        "district_code" => 320107,
        "village_code" => 3201072008,
        "address" => "Jl. Raya Cileungsi - Jonggol No.KM, 10, Cipeucang, Kec. Cileungsi, Kabupaten Bogor, Jawa Barat 16820",
        "postal_code" => 16820,
        "country_code" => "ID",
        "city" => "Kabupaten Bogor",
        "url" => "https://rsudcileungsi.bogorkab.go.id/",
        "email" => "rsudcileungsi@bogorkab.go.id",
        "phone" => "021-89934667",
        "fax" => "021-89934666",
        "rt" => "1",
        "rw" => "1",
        "position_longitude" => -6.4328904,
        "position_latitude" => 107.0485798,
        "position_altitude" => 0
    ],
    "limit" => [
        "row_table" => 20,
    ],
    "error_message" => [
        "failed_catch" => "Terjadi Kesalahan",
        "failed_success" => "Proses Sukses",
        "failed_ss_1" => "TIdak bisa dihapus, data sudah dikirm ke satu sehat",
        "message_waiting" => "Please Wait...."
    ],
    "fhir" => [
        "patient" => "Patient?identifier=https://fhir.kemkes.go.id/id/nik",
        "practitioner" => "Practitioner?identifier=https://fhir.kemkes.go.id/id/nik",
    ]
];
