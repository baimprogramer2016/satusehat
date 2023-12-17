<?php

namespace App\Traits;

trait JsonTrait
{
    public function bodyOrganization($data_organization, $data_parameter)
    {
        $bodyOrganization = [
            "resourceType" => "Organization",
            "active" => true,
            "identifier" => [
                [
                    "use" => "official",
                    "system" => "http://sys-ids.kemkes.go.id/organization/" . $data_parameter['organization_id'],
                    "value" => $data_organization['name']
                ]
            ],
            "type" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/organization-type",
                            "code" => $data_organization['type_code'],
                            "display" => $data_organization['type_display']
                        ]
                    ]
                ]
            ],
            "name" => $data_organization['name'],
            "telecom" => [
                [
                    "system" => "phone",
                    "value" => $data_organization['phone'],
                    "use" => "work"
                ],
                [
                    "system" => "email",
                    "value" => $data_organization['email'],
                    "use" => "work"
                ],
                [
                    "system" => "url",
                    "value" => $data_organization['url'],
                    "use" => "work"
                ]
            ],
            "address" => [
                [
                    "use" => "work",
                    "type" => "both",
                    "line" => [
                        $data_organization['address']
                    ],
                    "city" => $data_organization['city'],
                    "postalCode" => $data_organization['postal_code'],
                    "country" => $data_organization['country_code'],
                    "extension" => [
                        [
                            "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
                            "extension" => [
                                [
                                    "url" => "province",
                                    "valueCode" => $data_organization['province_code']
                                ],
                                [
                                    "url" => "city",
                                    "valueCode" => $data_organization['city_code']
                                ],
                                [
                                    "url" => "district",
                                    "valueCode" => $data_organization['district_code']
                                ],
                                [
                                    "url" => "village",
                                    "valueCode" => $data_organization['village_code']
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "partOf" => [
                "reference" => "Organization/" . $data_parameter['organization_id']
            ]
        ];

        return $bodyOrganization;
    }

    public function bodyLocation($data_location, $data_parameter)
    {
        $bodyLocation = [
            "resourceType" => "Location",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/location/" . $data_parameter['organization_id'],
                    "value" => $data_location['identifier_value']
                ]
            ],
            "status" => $data_location['status'],
            "name" => $data_location['name'],
            "description" => $data_location['description'],
            "mode" => "instance",
            "telecom" => [
                [
                    "system" => "phone",
                    "value" => $data_location['telecom_phone'] ?? '',
                    "use" => "work"
                ],
                [
                    "system" => "fax",
                    "value" =>  $data_location['telecom_fax'] ?? '',
                    "use" => "work"
                ],
                [
                    "system" => "email",
                    "value" => $data_location['telecom_email'] ?? ''
                ],
                [
                    "system" => "url",
                    "value" => $data_location['telecom_url'] ?? '',
                    "use" => "work"
                ]
            ],
            "address" => [
                "use" => "work",
                "line" => [
                    $data_location['address']
                ],
                "city" => $data_location['city'],
                "postalCode" => $data_location['postal_code'],
                "country" => $data_location['country'],
                "extension" => [
                    [
                        "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
                        "extension" => [
                            [
                                "url" => "province",
                                "valueCode" => $data_location['extension_province']
                            ],
                            [
                                "url" => "city",
                                "valueCode" => $data_location['extension_city']
                            ],
                            [
                                "url" => "district",
                                "valueCode" => $data_location['extension_district']
                            ],
                            [
                                "url" => "village",
                                "valueCode" => $data_location['extension_village']
                            ],
                            [
                                "url" => "rt",
                                "valueCode" => $data_location['extension_rt']
                            ],
                            [
                                "url" => "rw",
                                "valueCode" => $data_location['extension_rw']
                            ]
                        ]
                    ]
                ]
            ],
            "physicalType" => [
                "coding" => [
                    [
                        "system" => "http://terminology.hl7.org/CodeSystem/location-physical-type",
                        "code" => $data_location['physical_type_code'],
                        "display" => $data_location['physical_type_display']
                    ]
                ]
            ],
            "position" => [
                "longitude" => floatval($data_location['position_longitude']),
                "latitude" => floatval($data_location['position_latitude']),
                "altitude" => floatval($data_location['position_altitude'])
            ],
            "managingOrganization" => [
                "reference" => "Organization/" . $data_location['managing_organization']
            ]
        ];

        return $bodyLocation;
    }

    public function bodyBundle()
    {
        $bodyBundle = [
            "resourceType" => "Bundle",
            "type" => "transaction",
            "entry" => [
                [
                    "fullUrl" => "urn=>uuid=>bb2cb53b-a91d-4f6f-939b-a9a14240d634",
                    "resource" => [
                        "resourceType" => "Encounter",
                        "status" => "finished",
                        "class" => [
                            "system" => "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                            "code" => "AMB",
                            "display" => "ambulatory"
                        ],
                        "subject" => [
                            "reference" => "Patient/P03647103112",
                            "display" => "Janu"
                        ],
                        "participant" => [
                            [
                                "type" => [
                                    [
                                        "coding" => [
                                            [
                                                "system" => "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                                "code" => "ATND",
                                                "display" => "attender"
                                            ]
                                        ]
                                    ]
                                ],
                                "individual" => [
                                    "reference" => "Practitioner/10001354453",
                                    "display" => "Dokter Yanuar"
                                ]
                            ]
                        ],
                        "period" => [
                            "start" => "2021-01-14T07=>00=>00+07=>00",
                            "end" => "2021-01-14T09=>00=>00+07=>00"
                        ],
                        "location" => [
                            [
                                "location" => [
                                    "reference" => "Location/e54e5fc7-7804-43f0-b06d-66386431c5d1",
                                    "display" => "Ruang 1A, Bedah Mulut"
                                ]
                            ]
                        ],
                        "diagnosis" => [
                            [
                                "condition" => [
                                    "reference" => "urn=>uuid=>6e4de2d4-b804-4da4-bb44-75c1c160006b",
                                    "display" => "Acute appendicitis, other and unspecified"
                                ],
                                "use" => [
                                    "coding" => [
                                        [
                                            "system" => "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                                            "code" => "DD",
                                            "display" => "Discharge diagnosis"
                                        ]
                                    ]
                                ],
                                "rank" => 1
                            ],
                            [
                                "condition" => [
                                    "reference" => "urn=>uuid=>bd3b03bb-ee84-4ff0-aada-8401d1c46a67",
                                    "display" => "Dengue haemorrhagic fever"
                                ],
                                "use" => [
                                    "coding" => [
                                        [
                                            "system" => "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                                            "code" => "DD",
                                            "display" => "Discharge diagnosis"
                                        ]
                                    ]
                                ],
                                "rank" => 2
                            ]
                        ],
                        "statusHistory" => [
                            [
                                "status" => "arrived",
                                "period" => [
                                    "start" => "2021-01-14T07=>00=>00+07=>00",
                                    "end" => "2021-01-14T08=>00=>00+07=>00"
                                ]
                            ],
                            [
                                "status" => "in-progress",
                                "period" => [
                                    "start" => "2021-01-14T08=>00=>00+07=>00",
                                    "end" => "2021-01-14T09=>00=>00+07=>00"
                                ]
                            ],
                            [
                                "status" => "finished",
                                "period" => [
                                    "start" => "2021-01-14T09=>00=>00+07=>00",
                                    "end" => "2021-01-14T09=>00=>00+07=>00"
                                ]
                            ]
                        ],
                        "serviceProvider" => [
                            "reference" => "Organization/a2146e3c-26c0-448e-b593-82e5edb915c6"
                        ],
                        "identifier" => [
                            [
                                "system" => "http://sys-ids.kemkes.go.id/encounter/a2146e3c-26c0-448e-b593-82e5edb915c6",
                                "value" => "ANTROL-01"
                            ]
                        ]
                    ],
                    "request" => [
                        "method" => "POST",
                        "url" => "Encounter"
                    ]
                ],
                [
                    "fullUrl" => "urn=>uuid=>6e4de2d4-b804-4da4-bb44-75c1c160006b",
                    "resource" => [
                        "resourceType" => "Condition",
                        "clinicalStatus" => [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/condition-clinical",
                                    "code" => "active",
                                    "display" => "Active"
                                ]
                            ]
                        ],
                        "category" => [
                            [
                                "coding" => [
                                    [
                                        "system" => "http://terminology.hl7.org/CodeSystem/condition-category",
                                        "code" => "encounter-diagnosis",
                                        "display" => "Encounter Diagnosis"
                                    ]
                                ]
                            ]
                        ],
                        "code" => [
                            "coding" => [
                                [
                                    "system" => "http://hl7.org/fhir/sid/icd-10",
                                    "code" => "K35.8",
                                    "display" => "Acute appendicitis, other and unspecified"
                                ]
                            ]
                        ],
                        "subject" => [
                            "reference" => "Patient/P03647103112",
                            "display" => "Janu"
                        ],
                        "encounter" => [
                            "reference" => "urn=>uuid=>bb2cb53b-a91d-4f6f-939b-a9a14240d634",
                            "display" => "Kunjungan Budi Santoso di hari Selasa, 14 Juni 2022"
                        ]
                    ],
                    "request" => [
                        "method" => "POST",
                        "url" => "Condition"
                    ]
                ],
                [
                    "fullUrl" => "urn=>uuid=>bd3b03bb-ee84-4ff0-aada-8401d1c46a67",
                    "resource" => [
                        "resourceType" => "Condition",
                        "clinicalStatus" => [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/condition-clinical",
                                    "code" => "active",
                                    "display" => "Active"
                                ]
                            ]
                        ],
                        "category" => [
                            [
                                "coding" => [
                                    [
                                        "system" => "http://terminology.hl7.org/CodeSystem/condition-category",
                                        "code" => "encounter-diagnosis",
                                        "display" => "Encounter Diagnosis"
                                    ]
                                ]
                            ]
                        ],
                        "code" => [
                            "coding" => [
                                [
                                    "system" => "http://hl7.org/fhir/sid/icd-10",
                                    "code" => "A91",
                                    "display" => "Dengue haemorrhagic fever"
                                ]
                            ]
                        ],
                        "subject" => [
                            "reference" => "Patient/P03647103112",
                            "display" => "Janu"
                        ],
                        "encounter" => [
                            "reference" => "urn=>uuid=>bb2cb53b-a91d-4f6f-939b-a9a14240d634",
                            "display" => "Kunjungan Budi Santoso di hari Selasa, 14 Juni 2022"
                        ]
                    ],
                    "request" => [
                        "method" => "POST",
                        "url" => "Condition"
                    ]
                ]
            ]
        ];

        return $bodyBundle;
    }
}
