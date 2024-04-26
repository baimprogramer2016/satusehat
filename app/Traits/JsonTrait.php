<?php

namespace App\Traits;

use App\Models\BundleSet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Repositories\Jadwal\JadwalInterface;


trait JsonTrait
{
    public $atur_bundle_repo;

    public function __construct(JadwalInterface $jadwalInterface)
    {
        $this->atur_bundle_repo = $jadwalInterface;
    }

    public function getJadwalSet()
    {
        $data_jadwal_set = BundleSet::orderBy('resource', 'asc')->get();
        $tampung = [];
        foreach ($data_jadwal_set as $item) {
            array_push($tampung, $item);
        }

        return collect($tampung);
    }


    public function convertTimeStamp($date)
    {
        $carbonDate = Carbon::parse($date, 'Asia/Jakarta');
        return $carbonDate->format('Y-m-d\TH:i:sP');
    }
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
                "reference" => "Organization/" . $data_organization['partof_id']
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

    public function bodyBundle($param)
    {
        $bodyBundle = [
            "resourceType" => "Bundle",
            "type" => "transaction",
            "entry" => []
        ];

        # settingan Organization ID dll
        $data_parameter = $param['parameter'];
        $data_encounter = $param['bundle'];
        $data_observation = $param['observation'];
        $data_procedure = $param['procedure'];
        $data_composition = $param['composition'];
        $data_medication_request = $param['medication_request'];
        $data_medication_dispense = $param['medication_dispense'];
        $data_service_request = $param['service_request'];
        $data_specimen = $param['specimen'];
        $data_observation_lab = $param['observation_lab'];
        $data_diagnostic_report = $param['diagnostic_report'];


        // return $this->getJadwalSet();
        # push ke entry
        $bodyEncounter = $this->bodyBundleEncounter($data_parameter, $data_encounter);
        array_push($bodyBundle['entry'], $bodyEncounter);
        #condition
        //cek dahulu aktif atau tidak
        if ($this->getJadwalSet()->where('resource', 'condition')->first()->status == 1) {

            # ambiJika ada ,Ambil dari relasion dari model encounter r_condition dengan parameter encounter
            if (count($data_encounter['r_condition']) > 0) {
                foreach ($data_encounter['r_condition'] as $data_diagnosa) {
                    $bodyCondition = $this->bodyBundleCondition($data_diagnosa, $data_encounter['uuid']);
                    array_push($bodyBundle['entry'], $bodyCondition);
                }
            }
        }

        # observation
        //cek dahulu aktif atau tidak

        if ($this->getJadwalSet()->where('resource', 'observation_ttv')->first()->status == 1) {
            if (count($data_observation) > 0) {
                foreach ($data_observation as $item_observation) {
                    $bodyObservation = $this->bodyBundleObservation($item_observation, $data_encounter['uuid']);
                    array_push($bodyBundle['entry'], $bodyObservation);
                }
            }
        }

        # procedure
        if ($this->getJadwalSet()->where('resource', 'procedure')->first()->status == 1) {
            if (count($data_procedure) > 0) {
                $bodyProcedure = $this->bodyBundleProcedure($data_encounter, $data_encounter['r_condition'], $data_procedure);
                array_push($bodyBundle['entry'], $bodyProcedure);
            }
        }


        # composition
        if ($this->getJadwalSet()->where('resource', 'composition')->first()->status == 1) {
            if (count($data_composition) > 0) {
                foreach ($data_composition as $item_composition) {
                    $bodyComposition = $this->bodyBundlecomposition($item_composition, $data_parameter, $data_encounter['uuid']);
                    array_push($bodyBundle['entry'], $bodyComposition);
                }
            }
        }

        # medication , Medication Request
        if ($this->getJadwalSet()->where('resource', 'medication')->first()->status == 1) {
            if (count($data_medication_request) > 0) {
                foreach ($data_medication_request as $item_medication_request) {

                    # cek kfa lagi
                    if (!empty($item_medication_request['r_medication']['r_kfa'])) {

                        #medication
                        $bodyMedication = $this->bodyBundleMedication($item_medication_request, $data_parameter, $data_encounter['uuid']);
                        array_push($bodyBundle['entry'], $bodyMedication);

                        #medication request
                        $bodyMedicationRequest = $this->bodyBundleMedicationRequest($item_medication_request, $data_parameter, $data_encounter['uuid']);
                        array_push($bodyBundle['entry'], $bodyMedicationRequest);
                    }
                }
            }


            # medication dispense
            if (count($data_medication_dispense) > 0) {
                foreach ($data_medication_dispense as $item_medication_dispense) {
                    if (!empty($item_medication_dispense)) {
                        foreach ($item_medication_dispense['r_medication_request'] as $item_request) {
                            # yang request dan dispense sesuai dengan yang keluar saja
                            if (($item_medication_dispense['identifier_2'] == $item_request['identifier_2'])
                                && $item_medication_dispense['identifier_1'] == $item_request['identifier_1']
                            ) {
                                # cek terlebih dahulu kfa ya ada tidak
                                if (!empty($item_request['r_medication']['r_kfa'])) {
                                    #medication Dispense ,
                                    $bodyMedicationDispense = $this->bodyBundleMedicationDispense($item_medication_dispense, $item_request, $data_parameter, $data_encounter['uuid']);
                                    array_push($bodyBundle['entry'], $bodyMedicationDispense);
                                }
                            }
                        }
                    }
                }
            }
        }

        # service
        if ($this->getJadwalSet()->where('resource', 'service_request')->first()->status == 1) {
            if (count($data_service_request) > 0) {
                foreach ($data_service_request as $item_service_request) {
                    $bodyServiceRequest = $this->bodyBundleServiceRequest($data_encounter['uuid'],  $item_service_request, $data_parameter);
                    array_push($bodyBundle['entry'], $bodyServiceRequest);
                }
            }

            # specimen
            if (count($data_specimen) > 0) {
                foreach ($data_specimen as $item_specimen) {
                    $bodySpecimen = $this->bodyBundleSpecimen($data_encounter['uuid'],  $item_specimen, $data_parameter);
                    array_push($bodyBundle['entry'], $bodySpecimen);
                }
            }
            # observation lab
            if (count($data_observation_lab) > 0) {
                foreach ($data_observation_lab as $item_observation_lab) {
                    $bodyObservationLab = $this->bodyBundleObservationLab($data_encounter['uuid'],  $item_observation_lab, $data_parameter);
                    array_push($bodyBundle['entry'], $bodyObservationLab);
                }
            }
            # diagnostic report
            if (count($data_diagnostic_report) > 0) {
                foreach ($data_diagnostic_report as $item_diagnostic_report) {
                    $bodyDiagnosticReport = $this->bodyBundleDiagnosticReport($data_encounter['uuid'],  $item_diagnostic_report, $data_parameter);
                    array_push($bodyBundle['entry'], $bodyDiagnosticReport);
                }
            }
        }

        #end push entry
        return $bodyBundle;
    }

    public function bodyBundleEncounter($data_parameter, $data_encounter)
    {
        $bodyBundleEncounter = [
            "fullUrl" => "urn:uuid:" . $data_encounter['uuid'],
            "resource" => [
                "resourceType" => "Encounter",
                "status" => "finished",
                "class" => [
                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                    "code" => $data_encounter['class_code'],
                    "display" => $data_encounter['class_display']
                ],
                "subject" => [
                    "reference" => "Patient/" . $data_encounter['subject_reference'],
                    "display" => $data_encounter['subject_display']
                ],
                "participant" => [
                    [
                        "type" => [
                            [
                                "coding" => [
                                    [
                                        "system" => "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                        "code" => $data_encounter['participant_coding_code'],
                                        "display" => $data_encounter['participant_coding_display']
                                    ]
                                ]
                            ]
                        ],
                        "individual" => [
                            "reference" => "Practitioner/" . $data_encounter['participant_individual_reference'],
                            "display" => $data_encounter['participant_individual_display']
                        ]
                    ]
                ],
                "period" => [
                    "start" => $this->convertTimeStamp($data_encounter['period_start']),
                    "end" => $this->convertTimeStamp($data_encounter['period_end'])
                ],
                "location" => [
                    [
                        "location" => [
                            "reference" => "Location/" . $data_encounter['location_reference'],
                            "display" => $data_encounter['location_display']
                        ]
                    ]
                ],
                //disini ada diagnosa
                "statusHistory" => [
                    [
                        "status" => "arrived",
                        "period" => [
                            "start" => $this->convertTimeStamp($data_encounter['status_history_arrived_start']),
                            "end" => $this->convertTimeStamp($data_encounter['status_history_arrived_end'])
                        ]
                    ],
                    [
                        "status" => "in-progress",
                        "period" => [
                            "start" => $this->convertTimeStamp($data_encounter['status_history_inprogress_start']),
                            "end" =>  $this->convertTimeStamp($data_encounter['status_history_inprogress_end'])
                        ]
                    ],
                    [
                        "status" => "finished",
                        "period" => [
                            "start" => $this->convertTimeStamp($data_encounter['status_history_finished_start']),
                            "end" =>  $this->convertTimeStamp($data_encounter['status_history_finished_end'])
                        ]
                    ]
                ],
                "serviceProvider" => [
                    "reference" => "Organization/" . $data_parameter['organization_id']
                ],
                "identifier" => [
                    [
                        "system" => "http://sys-ids.kemkes.go.id/encounter/" . $data_parameter['organization_id'],
                        "value" => $data_encounter['identifier_value']
                    ]
                ]
            ],
            "request" => [
                "method" => "POST",
                "url" => "Encounter"
            ]
        ];

        # Jika condition ada , menambahkan diagnosa pada encounter
        if (count($data_encounter['r_condition']) > 0) {

            $bodyBundleEncounterDiagnosis = [];

            foreach ($data_encounter['r_condition'] as $data_diagnosa) {
                array_push($bodyBundleEncounterDiagnosis,  $this->bodyBundleEncounterDiagnosis($data_diagnosa));
            }

            $bodyBundleEncounter['resource']['diagnosis'] =  $bodyBundleEncounterDiagnosis;
        }
        return $bodyBundleEncounter;
    }

    public function bodyBundleEncounterDiagnosis($data_diagnosa)
    {
        $bodyBundleEncounterDiagnosis =
            [
                "condition" => [
                    "reference" => "urn:uuid:" . $data_diagnosa['uuid'],
                    "display" => $data_diagnosa['code_icd_display']
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
                "rank" => (int)$data_diagnosa['rank']
            ];
        return $bodyBundleEncounterDiagnosis;
    }

    public function bodyBundleCondition($data_condition, $encounter_uuid)
    {
        $bodyBundleCondition = [
            "fullUrl" => "urn:uuid:" . $data_condition['uuid'],
            "resource" => [
                "resourceType" => "Condition",
                "clinicalStatus" => [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/condition-clinical",
                            "code" =>  $data_condition['clinical_code'],
                            "display" =>  $data_condition['clinical_display']
                        ]
                    ]
                ],
                "category" => [
                    [
                        "coding" => [
                            [
                                "system" => "http://terminology.hl7.org/CodeSystem/condition-category",
                                "code" =>  $data_condition['category_code'],
                                "display" =>  $data_condition['category_display']
                            ]
                        ]
                    ]
                ],
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://hl7.org/fhir/sid/icd-10",
                            "code" =>  $data_condition['code_icd'],
                            "display" =>  $data_condition['code_icd_display']
                        ]
                    ]
                ],
                "subject" => [
                    "reference" => "Patient/" . $data_condition['subject_reference'],
                    "display" =>  $data_condition['subject_display']
                ],
                "encounter" => [
                    "reference" => "urn:uuid:" . $encounter_uuid,
                    "display" =>  $data_condition['encounter_display']
                ]
            ],
            "request" => [
                "method" => "POST",
                "url" => "Condition"
            ]
        ];
        return $bodyBundleCondition;
    }

    public function bodyBundleObservation($data_observation, $encounter_uuid)
    {
        $bodyBundleObservation =  [
            "fullUrl" => "urn:uuid:" . $data_observation['uuid'],
            "resource" => [
                "resourceType" => "Observation",
                "status" => $data_observation['status'],
                "category" => [
                    [
                        "coding" => [
                            [
                                "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                                "code" => $data_observation['category_code'],
                                "display" => $data_observation['category_display']
                            ]
                        ]
                    ]
                ],
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => $data_observation['code_observation'],
                            "display" => $data_observation['code_display']
                        ]
                    ]
                ],
                "subject" => [
                    "reference" => "Patient/" . $data_observation['subject_reference'],
                    "display" => $data_observation['subject_display']
                ],
                "performer" => [
                    [
                        "reference" => "Practitioner/" . $data_observation['performer_reference']
                    ]
                ],
                "encounter" => [
                    "reference" => "urn:uuid:" . $encounter_uuid,
                    "display" => "-"
                ],
                "effectiveDateTime" => $this->convertTimeStamp($data_observation['effective_datetime']),
                "issued" => $this->convertTimeStamp($data_observation['issued']),
                // "bodySite" => [
                //     "coding" => [
                //         [
                //             "system" => "http://snomed.info/sct",
                //             "code" => "368209003",
                //             "display" => "Right arm"
                //         ]
                //     ]
                // ],
                "valueQuantity" => [
                    "value" => (int)$data_observation['quantity_value'],
                    "unit" => $data_observation['quantity_unit'],
                    "system" => "http://unitsofmeasure.org",
                    "code" => $data_observation['quantity_code']
                ],
            ],
            "request" => [
                "method" => "POST",
                "url" => "Observation"
            ]
        ];
        return $bodyBundleObservation;
    }

    public function bodyBundleProcedure($data_encounter, $data_condition, $data_procedure)
    {
        $bodyBundleProcedure = [
            "fullUrl" => "urn:uuid:" . $data_encounter['uuid_procedure'],
            "resource" => [
                "resourceType" => "Procedure",
                "status" => "completed",
                "category" => [
                    "coding" => [
                        [
                            "system" => "http://snomed.info/sct",
                            "code" => "103693007",
                            "display" => "Diagnostic procedure"
                        ]
                    ],
                    "text" => "Diagnostic procedure"
                ],
                "subject" => [
                    "reference" => "Patient/" . $data_encounter['subject_reference'],
                    "display" => $data_encounter['subject_display']
                ],
                "encounter" => [
                    "reference" => "urn:uuid:" . $data_encounter['uuid'],
                    "display" => "-"
                ],
                "performedPeriod" => [
                    "start" => $this->convertTimeStamp($data_encounter['status_history_inprogress_start']),
                    "end" => $this->convertTimeStamp($data_encounter['status_history_inprogress_end'])
                ],
                "performer" => [
                    [
                        "actor" => [
                            "reference" => "Practitioner/" . $data_encounter['participant_individual_reference'],
                            "display" => $data_encounter['participant_individual_display']
                        ]
                    ]
                ],
                // "code"=> [
                //     "coding"=> [
                //         [
                //             "system"=> "http://hl7.org/fhir/sid/icd-9-cm",
                //             "code"=> "87.44",
                //             "display"=> "Routine chest x-ray, so described"
                //         ]
                //     ]
                // ],
                // "reasonCode" => [
                //     [
                //         "coding" => [
                //             [
                //                 "system" => "http://hl7.org/fhir/sid/icd-10",
                //                 "code" => "A15.0",
                //                 "display" => "Tuberculosis of lung, confirmed by sputum microscopy with or without culture"
                //             ]
                //         ]
                //     ]
                // ],
                // "bodySite" => [
                //     [
                //         "coding" => [
                //             [
                //                 "system" => "http://snomed.info/sct",
                //                 "code" => "302551006",
                //                 "display" => "Entire Thorax"
                //             ]
                //         ]
                //     ]
                // ],
                "note" => [
                    [
                        "text" => "-"
                    ]
                ]
            ],
            "request" => [
                "method" => "POST",
                "url" => "Procedure"
            ]
        ];

        # Jika procedure ada , menambahkan tindakan pada encounter
        if (count($data_procedure) > 0) {

            $bodyBundleItemProcedure = [];

            foreach ($data_procedure as $item_procedure) {
                array_push($bodyBundleItemProcedure,  $this->bodyBundleProcedureIcd9($item_procedure));
            }

            $bodyBundleProcedure['resource']['code']['coding'] = $bodyBundleItemProcedure;
        }
        # Jika condition ada , menambahkan diagnosa pada encounter
        if (count($data_encounter['r_condition']) > 0) {

            $bodyBundleConditionCoding = [];
            foreach ($data_encounter['r_condition'] as $item_diagnosa) {
                array_push($bodyBundleConditionCoding,  $this->bodyBundleProcedureIcd10($item_diagnosa));
            }

            $varProcedure = ["coding" => $bodyBundleConditionCoding];

            $bodyBundleProcedureFinal = [];
            array_push($bodyBundleProcedureFinal,  $varProcedure);

            $bodyBundleProcedure['resource']['reasonCode'] = $bodyBundleProcedureFinal;
        }
        return $bodyBundleProcedure;
    }
    public function bodyBundleProcedureIcd9($data_procedure)
    {
        $bodyBundleProcedureIcd9 =  [
            "system" => "http://hl7.org/fhir/sid/icd-9-cm",
            "code" => $data_procedure['code_icd'],
            "display" => $data_procedure['code_icd_display']
        ];
        return $bodyBundleProcedureIcd9;
    }
    public function bodyBundleProcedureIcd10($data_condition)
    {
        $bodyBundleProcedureIcd10 =   [
            "system" => "http://hl7.org/fhir/sid/icd-10",
            "code" => $data_condition['code_icd'],
            "display" => $data_condition['code_icd_display']
        ];
        return $bodyBundleProcedureIcd10;
    }

    public function bodyBundlecomposition($data_composition, $data_parameter, $encounter_uuid)
    {
        $bodyBundlecomposition =  [
            "fullUrl" => "urn:uuid:" . $data_composition['uuid'],
            "resource" => [
                "resourceType" => "Composition",
                "identifier" => [
                    "system" => "http://sys-ids.kemkes.go.id/composition/" . $data_parameter['organization_id'],
                    "value" => $data_composition['encounter_original_code']
                ],
                "status" => "final",
                "type" => [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => "18842-5",
                            "display" => "Discharge summary"
                        ]
                    ]
                ],
                "category" => [
                    [
                        "coding" => [
                            [
                                "system" => "http://loinc.org",
                                "code" => "LP173421-1",
                                "display" => "Report"
                            ]
                        ]
                    ]
                ],
                "subject" => [
                    "reference" => "Patient/" . $data_composition['subject_reference'],
                    "display" => $data_composition['subject_display']
                ],
                "encounter" => [
                    "reference" => "urn:uuid:" . $encounter_uuid,
                    "display" => "-"
                ],
                "date" =>  $this->convertTimeStamp($data_composition['date']),
                "author" => [
                    [
                        "reference" => "Practitioner/" . $data_composition['author_reference'],
                        "display" => $data_composition['author_display']
                    ]
                ],
                "title" => $data_composition['title'],
                "custodian" => [
                    "reference" => "Organization/" . $data_parameter['organization_id']
                ],
                "section" => [
                    [
                        "code" => [
                            "coding" => [
                                [
                                    "system" => "http://loinc.org",
                                    "code" => $data_composition['section_code'],
                                    "display" => $data_composition['section_code_display']
                                ]
                            ]
                        ],
                        "text" => [
                            "status" => $data_composition['text_status'],
                            "div" => $data_composition['text_div']
                        ]
                    ]
                ]
            ],
            "request" => [
                "method" => "POST",
                "url" => "Composition"
            ]
        ];
        return $bodyBundlecomposition;
    }

    public function bodyBundleMedication($data_medication_request, $data_parameter, $encounter_uuid)
    {
        $bodyBundleMedication = [
            "fullUrl" => "urn:uuid:" . $data_medication_request['uuid_medication'],
            "resource" => [
                "resourceType" => "Medication",
                "meta" => [
                    "profile" => [
                        "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
                    ]
                ],
                "identifier" => [
                    [
                        "system" => "http://sys-ids.kemkes.go.id/medication/" . $data_parameter['organization_id'],
                        "use" => "official",
                        "value" => $data_medication_request['identifier_2'] . '-' . $data_medication_request['identifier_1']
                    ]
                ],
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://sys-ids.kemkes.go.id/kfa",
                            "code" => $data_medication_request['r_medication']['r_kfa'][0]['kode_kfa'],
                            "display" => $data_medication_request['r_medication']['r_kfa'][0]['nama_kfa']
                        ]
                    ]
                ],
                "status" => "active",
                "manufacturer" => [
                    "reference" => "Organization/" . $data_parameter['farmasi_id']
                ],
                "form" => [
                    "coding" => [
                        [
                            "system" => "http://terminology.kemkes.go.id/CodeSystem/medication-form",
                            "code" => $data_medication_request['r_medication']['r_kfa'][0]['kode_sediaan'],
                            "display" => $data_medication_request['r_medication']['r_kfa'][0]['nama_sediaan']
                        ]
                    ]
                ],

                "extension" => [
                    [
                        "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
                        "valueCodeableConcept" => [
                            "coding" => [
                                [
                                    "system" => "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                                    "code" => "NC",
                                    "display" => "Non-compound"
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "request" => [
                "method" => "POST",
                "url" => "Medication"
            ]
        ];

        # Jika ada KFA masukan ke ingredient
        if (!empty($data_medication_request['r_medication']['r_kfa'])) {

            $bodyBundleIngredient = [];
            foreach ($data_medication_request['r_medication']['r_kfa'] as $item_kfa) {
                array_push($bodyBundleIngredient,  $this->bodyBundleMedicationIngredient($item_kfa));
            }

            $bodyBundleMedication['resource']['ingredient'] =  $bodyBundleIngredient;
        }
        return $bodyBundleMedication;
    }

    public function bodyBundleMedicationIngredient($item_kfa)
    {
        $bodyBundleMedicationIngredient =
            [
                "itemCodeableConcept" => [
                    "coding" => [
                        [
                            "system" => "http://sys-ids.kemkes.go.id/kfa",
                            "code" => $item_kfa['kode_pa'],
                            "display" => $item_kfa['nama_pa']
                        ]
                    ]
                ],
                "isActive" => true,
                "strength" => [
                    "numerator" => [
                        "value" => (int)$item_kfa['numerator'],
                        "system" => "http://unitsofmeasure.org",
                        "code" => $item_kfa['numerator_satuan']
                    ],
                    "denominator" => [
                        "value" => (int)$item_kfa['denominator'],
                        "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                        "code" => $item_kfa['satuan_disesuaikan'],
                    ]
                ]
            ];

        return $bodyBundleMedicationIngredient;
    }
    public function bodyBundleMedicationRequest($data_medication_request, $data_parameter, $encounter_uuid)
    {
        $bodyBundleMedicationRequest =   [
            "fullUrl" => "urn:uuid:" . $data_medication_request['uuid'],
            "resource" => [
                "resourceType" => "MedicationRequest",
                "identifier" => [
                    [
                        "system" => "http://sys-ids.kemkes.go.id/prescription/" . $data_parameter['organization_id'],
                        "use" => "official",
                        "value" => $data_medication_request['identifier_1']
                    ],
                    [
                        "system" => "http://sys-ids.kemkes.go.id/prescription-item/" . $data_parameter['organization_id'],
                        "use" => "official",
                        "value" => $data_medication_request['identifier_1'] . '-' . $data_medication_request['identifier_2']
                    ]
                ],
                "status" => "completed",
                "intent" => "order",
                "category" => [
                    [
                        "coding" => [
                            [
                                "system" => "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
                                "code" => "outpatient",
                                "display" => "Outpatient"
                            ]
                        ]
                    ]
                ],
                "priority" => "routine",
                "medicationReference" => [
                    "reference" => "urn:uuid:" . $data_medication_request['uuid_medication'],
                    "display" => $data_medication_request['r_medication']['r_kfa'][0]['nama_kfa']
                ],
                "subject" => [
                    "reference" => "Patient/" . $data_medication_request['subject_reference'],
                    "display" => $data_medication_request['subject_display']
                ],
                "encounter" => [
                    "reference" => "urn:uuid:" . $encounter_uuid
                ],
                "authoredOn" => $this->convertTimeStamp($data_medication_request['authored_on']),
                "requester" => [
                    "reference" => "Practitioner/" . $data_medication_request['requester_reference'],
                    "display" => $data_medication_request['requester_display']
                ],
                // "reasonCode" => [
                //     [
                //         "coding" => [
                //             [
                //                 "system" => "http://hl7.org/fhir/sid/icd-10",
                //                 "code" => "A15.0",
                //                 "display" => "Tuberculosis of lung, confirmed by sputum microscopy with or without culture"
                //             ]
                //         ]
                //     ]
                // ],
                // "courseOfTherapyType" => [
                //     "coding" => [
                //         [
                //             "system" => "http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy",
                //             "code" => "continuous",
                //             "display" => "Continuing long term therapy"
                //         ]
                //     ]
                // ],
                "dosageInstruction" => [
                    [
                        "sequence" => 1,
                        "text" => $data_medication_request['dose_quantity_value'],
                        "additionalInstruction" => [
                            [
                                "text" =>  $data_medication_request['ins_additional']
                            ]
                        ],
                        "patientInstruction" => $data_medication_request['ins_patient'],
                        "timing" => [
                            "repeat" => [
                                "frequency" => (int)$data_medication_request['int_timing_frequency'],
                                "period" => (int)$data_medication_request['int_timing_period'],
                                "periodUnit" => $data_medication_request['int_timing_period_unit']
                            ]
                        ],
                        "route" => [
                            "coding" => [
                                [
                                    "system" => "http://www.whocc.no/atc",
                                    "code" => $data_medication_request['route_code'],
                                    "display" => $data_medication_request['route_display']
                                ]
                            ]
                        ],
                        "doseAndRate" => [
                            [
                                "type" => [
                                    "coding" => [
                                        [
                                            "system" => "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                                            "code" => "ordered",
                                            "display" => "Ordered"
                                        ]
                                    ]
                                ],
                                "doseQuantity" => [
                                    "value" => (int)$data_medication_request['dose_quantity_value'],
                                    "unit" => $data_medication_request['dose_quantity_unit'],
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                    "code" => $data_medication_request['dose_quantity_code'],
                                ]
                            ]
                        ]
                    ]
                ],
                "dispenseRequest" => [
                    "dispenseInterval" => [
                        "value" => (int)$data_medication_request['dispense_interval_value'],
                        "unit" => $data_medication_request['dispense_interval_unit'],
                        "system" => "http://unitsofmeasure.org",
                        "code" => $data_medication_request['dispense_interval_code'],
                    ],
                    "validityPeriod" => [
                        "start" => $this->convertTimeStamp($data_medication_request['validity_period_start']),
                        "end" => $this->convertTimeStamp($data_medication_request['validity_period_end']),
                    ],
                    // "numberOfRepeatsAllowed" => (int)$data_medication_request['number_of_repeats'],
                    "quantity" => [
                        "value" => (int)$data_medication_request['quantity_value'],
                        "unit" => $data_medication_request['quantity_unit'],
                        "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                        "code" => $data_medication_request['quantity_code'],
                    ],
                    "expectedSupplyDuration" => [
                        "value" => (int)$data_medication_request['expected_value'],
                        "unit" => $data_medication_request['expected_unit'],
                        "system" => "http://unitsofmeasure.org",
                        "code" => $data_medication_request['expected_code'],
                    ],
                    "performer" => [
                        "reference" => "Organization/" . $data_parameter['organization_id']
                    ]
                ]
            ],
            "request" => [
                "method" => "POST",
                "url" => "MedicationRequest"
            ]
        ];
        return $bodyBundleMedicationRequest;
    }
    public function bodyBundleMedicationDispense($data_medication_dispense, $data_medication_request, $data_parameter, $encounter_uuid)
    {
        $bodyBundleMedicationDispense =   [
            "fullUrl" => "urn:uuid:" . $data_medication_dispense['uuid'],
            "resource" => [
                "resourceType" => "MedicationDispense",
                "identifier" => [
                    [
                        "system" => "http://sys-ids.kemkes.go.id/prescription/" . $data_parameter['organization_id'],
                        "use" => "official",
                        "value" => $data_medication_dispense['identifier_1']
                    ],
                    [
                        "system" => "http://sys-ids.kemkes.go.id/prescription-item/" . $data_parameter['organization_id'],
                        "use" => "official",
                        "value" => $data_medication_dispense['identifier_1'] . '-' . $data_medication_dispense['identifier_2']
                    ]
                ],
                "status" => "completed",
                "category" => [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
                            "code" => "outpatient",
                            "display" => "Outpatient"
                        ]
                    ]
                ],
                "medicationReference" => [
                    "reference" => "urn:uuid:" . $data_medication_request['uuid_medication'],
                    "display" =>   $data_medication_request['r_medication']['r_kfa'][0]['nama_kfa'] ?? ''
                ],
                "subject" => [
                    "reference" => "Patient/" . $data_medication_request['subject_reference'],
                    "display" => $data_medication_request['subject_display']
                ],
                "context" => [
                    "reference" => "urn:uuid:" . $encounter_uuid
                ],
                "performer" => [
                    [
                        "actor" => [
                            "reference" => "Practitioner/" . $data_medication_request['requester_reference'],
                            "display" => $data_medication_request['requester_display']
                        ]
                    ]
                ],
                "location" => [
                    "reference" => "Location/" . $data_medication_dispense['farmasi_id'],
                    "display" => $data_medication_dispense['farmasi_name']
                ],
                "authorizingPrescription" => [
                    [
                        "reference" => "urn:uuid:" . $data_medication_request['uuid']
                    ]
                ],
                "quantity" => [
                    "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                    "code" => $data_medication_request['quantity_code'],
                    "value" => (int)$data_medication_request['quantity_value'],
                ],
                "daysSupply" => [
                    "value" => (int)$data_medication_request['expected_value'],
                    "unit" => $data_medication_request['expected_unit'],
                    "system" => "http://unitsofmeasure.org",
                    "code" => $data_medication_request['expected_code'],
                ],
                "whenPrepared" => $this->convertTimeStamp($data_medication_request['validity_period_start']),
                "whenHandedOver" => $this->convertTimeStamp($data_medication_request['validity_period_end']),
                "dosageInstruction" => [
                    [
                        "sequence" => 1,
                        "text" => $data_medication_request['dose_quantity_value'],
                        "timing" => [
                            "repeat" => [
                                "frequency" => (int)$data_medication_request['int_timing_frequency'],
                                "period" =>  (int)$data_medication_request['int_timing_period'],
                                "periodUnit" => $data_medication_request['int_timing_period_unit']
                            ]
                        ],
                        "doseAndRate" => [
                            [
                                "type" => [
                                    "coding" => [
                                        [
                                            "system" => "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                                            "code" => "ordered",
                                            "display" => "Ordered"
                                        ]
                                    ]
                                ],
                                "doseQuantity" => [
                                    "value" => (int)$data_medication_request['dose_quantity_value'],
                                    "unit" =>  $data_medication_request['dose_quantity_unit'],
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                    "code" => $data_medication_request['dose_quantity_code'],
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "request" => [
                "method" => "POST",
                "url" => "MedicationDispense"
            ]
        ];
        return $bodyBundleMedicationDispense;
    }

    public function bodyBundleServiceRequest($encounter_uuid, $data_service_request, $data_parameter)
    {
        $bodyBundleServiceRequest = [
            "fullUrl" => "urn:uuid:" . $data_service_request['uuid'],
            "resource" => [
                "resourceType" => "ServiceRequest",
                "identifier" => [
                    [
                        "system" => "http://sys-ids.kemkes.go.id/servicerequest/" . $data_parameter['organization_id'],
                        "value" => $data_service_request['identifier_1'] . '|' . $data_service_request['procedure_code_original']
                    ]
                ],
                "status" => "active",
                "intent" => "original-order",
                "priority" => "routine",
                "category" => [
                    [
                        "coding" => [
                            [
                                "system" => "http://snomed.info/sct",
                                "code" => $data_service_request['category_code'],
                                "display" => $data_service_request['category_display']
                            ]
                        ]
                    ]
                ],
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => $data_service_request['coding_code'],
                            "display" => $data_service_request['coding_display']
                        ]
                    ],
                    "text" => $data_service_request['reason_text']
                ],
                "subject" => [
                    "reference" => "Patient/" . $data_service_request['subject_reference']
                ],
                "encounter" => [
                    "reference" => "urn:uuid:" . $encounter_uuid,
                    "display" => "-"
                ],
                "occurrenceDateTime" => $this->convertTimeStamp($data_service_request['occurrence_datetime']),
                "authoredOn" => $this->convertTimeStamp($data_service_request['authored_on']),
                "requester" => [
                    "reference" => "Practitioner/" . $data_service_request['participant_individual_reference'],
                    "display" =>  $data_service_request['participant_individual_display']
                ],
                // "performer" => [
                //     [
                //         "reference" => "Practitioner/N10000005",
                //         "display" => "Fatma"
                //     ]
                // ],
                "performer" => [
                    [
                        "reference" => "Organization/" . $data_parameter['laboratory_id'],
                        "display" => "Laboratorium"
                    ]
                ],
                "reasonCode" => [
                    [
                        "text" => $data_service_request['reason_text']
                    ]
                ]
            ],
            "request" => [
                "method" => "POST",
                "url" => "ServiceRequest"
            ]
        ];
        return $bodyBundleServiceRequest;
    }
    public function bodyBundleSpecimen($encounter_uuid, $data_specimen, $data_parameter)
    {
        $bodyBundleSpecimen =  [
            "fullUrl" => "urn:uuid:" . $data_specimen['uuid_specimen'],
            "resource" => [
                "resourceType" => "Specimen",
                "identifier" => [
                    [
                        "system" => "http://sys-ids.kemkes.go.id/specimen/" . $data_parameter['organization_id'],
                        "value" =>  $data_specimen['identifier_1'] . '|' . $data_specimen['procedure_code_original'],
                        "assigner" => [
                            "reference" => "Organization/" . $data_parameter['organization_id'],
                        ]
                    ]
                ],
                "status" => "available",
                "type" => [
                    "coding" => [
                        [
                            "system" => "http://snomed.info/sct",
                            "code" => $data_specimen['snomed_code'],
                            "display" =>  $data_specimen['snomed_display'],
                        ]
                    ]
                ],
                // "collection" => [
                //     "method" => [
                //         "coding" => [
                //             [
                //                 "system" => "http://snomed.info/sct",
                //                 "code" => "386089008",
                //                 "display" => "Collection of coughed sputum"
                //             ]
                //         ]
                //     ],
                //     "collectedDateTime" => "2022-06-14T08=>15=>00+07=>00"
                // ],
                "subject" => [
                    "reference" => "Patient/" . $data_specimen['subject_reference'],
                    "display" =>  $data_specimen['subject_display'],
                ],
                "request" => [
                    [
                        "reference" => "urn:uuid:" . $data_specimen['uuid']
                    ]
                ],
                "receivedTime" => $this->convertTimeStamp($data_specimen['authored_on']),
            ],
            "request" => [
                "method" => "POST",
                "url" => "Specimen"
            ]
        ];
        return $bodyBundleSpecimen;
    }

    public function bodyBundleObservationLab($encounter_uuid, $data_observation_lab, $data_parameter)
    {
        $bodyBundleObservationLab =  [
            "fullUrl" => "urn:uuid:" . $data_observation_lab['uuid_observation'],
            "resource" => [
                "resourceType" => "Observation",
                "identifier" => [
                    [
                        "system" => "http://sys-ids.kemkes.go.id/observation/" . $data_parameter['organization_id'],
                        "value" => $data_observation_lab['identifier_1'] . '|' . $data_observation_lab['procedure_code_original'],
                    ]
                ],
                "status" => "final",
                "category" => [
                    [
                        "coding" => [
                            [
                                "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                                "code" => $data_observation_lab['obs_category_code'],
                                "display" => $data_observation_lab['obs_category_display']
                            ]
                        ]
                    ]
                ],
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => $data_observation_lab['loinc_code'],
                            "display" => $data_observation_lab['loinc_display']
                        ]
                    ]
                ],
                "subject" => [
                    "reference" => "Patient/" . $data_observation_lab['subject_reference']
                ],
                "encounter" => [
                    "reference" => "urn:uuid:" . $encounter_uuid
                ],
                "effectiveDateTime" =>  $this->convertTimeStamp($data_observation_lab['occurrence_datetime']),
                "issued" =>  $this->convertTimeStamp($data_observation_lab['authored_on']),
                "performer" => [
                    [
                        "reference" => "Organization/" . $data_parameter['laboratory_id'],
                        "display" => "Laboratorium"
                    ]
                ],
                "specimen" => [
                    "reference" => "urn:uuid:" . $data_observation_lab['uuid_specimen']
                ],
                "basedOn" => [
                    [
                        "reference" => "urn:uuid:" . $data_observation_lab['uuid']
                    ]
                ],
            ],
            "request" => [
                "method" => "POST",
                "url" => "Observation"
            ]
        ];

        # tambahan json untuk lab
        if (!empty($data_observation_lab['r_master_procedure']['r_category_request']['payload'])) {
            $additionalFunction = $data_observation_lab['r_master_procedure']['r_category_request']['payload'];
            $param = $data_observation_lab; #$param itu ada di payload tabel ss_category_request->payload
            $functionAdditional = str_replace("'", '"', $additionalFunction);
            $field = $data_observation_lab['r_master_procedure']['r_category_request']['field'];
            $result_addtional = eval("return {$functionAdditional};");
            $bodyBundleObservationLab['resource'][$field] = $result_addtional;
        }
        return $bodyBundleObservationLab;
    }

    public function bodyBundleObservationLabGolonganDarah($param)
    {
        return  [
            "coding" => [
                [
                    "system" => "http://loinc.org",
                    "code" => $param['procedure_code'],
                    "display" => $param['procedure_code_display']
                ]
            ]
        ];
    }
    public function bodyBundleObservationLabBta($param)
    {
        return  [
            [
                "system" => "http://snomed.info/sct",
                "code" => "260347006",
                "display" => "+"
            ]
        ];
    }
    public function bodyBundleObservationLabCekDarah($param)
    {
        return [
            "value" =>  (int)$param['procedure_result'],
            "unit" =>  $param['procedure_unit'],
            "system" => "http://unitsofmeasure.org",
            "code" =>  $param['procedure_unit'],
        ];
    }

    public function bodyBundleDiagnosticReport($encounter_uuid, $data_diagnostic_report, $data_parameter)
    {
        $bodyBundleDiagnosticReport =  [
            "fullUrl" => "urn:uuid:" . $data_diagnostic_report['uuid_diagnostic_report'],
            "resource" => [
                "resourceType" => "DiagnosticReport",
                "identifier" => [
                    [
                        "system" => "http://sys-ids.kemkes.go.id/diagnostic/" . $data_parameter['organization_id'] . "/lab",
                        "use" => "official",
                        "value" =>  $data_diagnostic_report['identifier_1'] . '|' . $data_diagnostic_report['procedure_code_original'],
                    ]
                ],
                "status" => "final",
                "category" => [
                    [
                        "coding" => [
                            [
                                "system" => "http://terminology.hl7.org/CodeSystem/v2-0074",
                                "code" => $data_diagnostic_report['r_master_procedure']['r_category_request']['diagnostic_report_code'],
                                "display" => $data_diagnostic_report['r_master_procedure']['r_category_request']['diagnostic_report_display']
                            ]
                        ]
                    ]
                ],
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => $data_diagnostic_report['loinc_code'],
                            "display" => $data_diagnostic_report['loinc_display']
                        ]
                    ]
                ],
                "subject" => [
                    "reference" => "Patient/" . $data_diagnostic_report['subject_reference']
                ],
                "encounter" => [
                    "reference" => "urn:uuid:" . $encounter_uuid
                ],
                "effectiveDateTime" =>  $this->convertTimeStamp($data_diagnostic_report['occurrence_datetime']),
                "issued" =>  $this->convertTimeStamp($data_diagnostic_report['authored_on']),
                "performer" => [
                    // [
                    //     "reference" => "Practitioner/N10000001"
                    // ],
                    [
                        "reference" => "Organization/" . $data_parameter['laboratory_id'],
                    ]
                ],
                "result" => [
                    [
                        "reference" => "urn:uuid:" . $data_diagnostic_report['uuid_observation']
                    ]
                ],
                "specimen" => [
                    [
                        "reference" => "urn:uuid:" . $data_diagnostic_report['uuid_specimen']
                    ]
                ],
                "basedOn" => [
                    [
                        "reference" => "urn:uuid:" . $data_diagnostic_report['uuid']
                    ]
                ],
                // "conclusionCode" => [
                //     [
                //         "coding" => [
                //             [
                //                 "system" => "http://snomed.info/sct",
                //                 "code" => "260347006",
                //                 "display" => "+"
                //             ]
                //         ]
                //     ]
                // ]
            ],
            "request" => [
                "method" => "POST",
                "url" => "DiagnosticReport"
            ]
        ];
        return $bodyBundleDiagnosticReport;
    }



    #############################  MANUAL ###################################
    public function bodyManualDiagnosticReport($data_diagnostic_report, $data_parameter)
    {
        $bodyManualDiagnosticReport = [
            "resourceType" => "DiagnosticReport",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/diagnostic/" . $data_parameter['organization_id'] . "/lab",
                    "use" => "official",
                    "value" =>  $data_diagnostic_report['identifier_1'] . '|' . $data_diagnostic_report['procedure_code_original'],
                ]
            ],
            "status" => "final",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/v2-0074",
                            "code" => $data_diagnostic_report['r_master_procedure']['r_category_request']['diagnostic_report_code'],
                            "display" => $data_diagnostic_report['r_master_procedure']['r_category_request']['diagnostic_report_display']
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => $data_diagnostic_report['loinc_code'],
                        "display" => $data_diagnostic_report['loinc_display']
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $data_diagnostic_report['subject_reference']
            ],
            "encounter" => [
                "reference" => "Encounter/" . $data_diagnostic_report['r_encounter']['satusehat_id']
            ],
            "effectiveDateTime" =>  $this->convertTimeStamp($data_diagnostic_report['occurrence_datetime']),
            "issued" =>  $this->convertTimeStamp($data_diagnostic_report['authored_on']),
            "performer" => [
                // [
                //     "reference" => "Practitioner/N10000001"
                // ],
                [
                    "reference" => "Organization/" . $data_parameter['laboratory_id'],
                ]
            ],
            "result" => [
                [
                    "reference" => "Observation/" . $data_diagnostic_report['r_observation']['satusehat_id']
                ]
            ],
            "specimen" => [
                [
                    "reference" => "Specimen/" . $data_diagnostic_report['satusehat_id_specimen']
                ]
            ],
            "basedOn" => [
                [
                    "reference" => "ServiceRequest/" . $data_diagnostic_report['satusehat_id']
                ]
            ],
            // "conclusionCode" => [
            //     [
            //         "coding" => [
            //             [
            //                 "system" => "http://loinc.org",
            //                 "code" => "LA19710-5",
            //                 "display" => "Group A"
            //             ]
            //         ]
            //     ]
            // ]
        ];
        return $bodyManualDiagnosticReport;
    }
    public function bodyManualObservationLab($data_observation_lab, $data_parameter)
    {
        $bodyManualObservationLab = [
            "resourceType" => "Observation",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/observation/" . $data_parameter['organization_id'],
                    "value" =>  $data_observation_lab['identifier_1'] . '|' . $data_observation_lab['procedure_code_original'],
                ]
            ],
            "status" => "final",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                            "code" => $data_observation_lab['obs_category_code'],
                            "display" => $data_observation_lab['obs_category_display']
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => $data_observation_lab['loinc_code'],
                        "display" => $data_observation_lab['loinc_display']
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $data_observation_lab['subject_reference']
            ],
            "encounter" => [
                "reference" => "Encounter/" . $data_observation_lab['r_encounter']['satusehat_id']
            ],
            "effectiveDateTime" =>  $this->convertTimeStamp($data_observation_lab['occurrence_datetime']),
            "issued" =>  $this->convertTimeStamp($data_observation_lab['authored_on']),
            "performer" => [
                // [
                //     "reference" => "Practitioner/N10000001"
                // ],
                [
                    "reference" => "Organization/" . $data_parameter['laboratory_id'],
                    "display" => "Laboratorium"
                ]
            ],
            "specimen" => [
                "reference" => "Specimen/" . $data_observation_lab['satusehat_id_specimen']
            ],
            "basedOn" => [
                [
                    "reference" => "ServiceRequest/" . $data_observation_lab['satusehat_id']
                ]
            ]
        ];
        if (!empty($data_observation_lab['r_master_procedure']['r_category_request']['payload'])) {
            $additionalFunction = $data_observation_lab['r_master_procedure']['r_category_request']['payload'];
            $param = $data_observation_lab; #$param itu ada di payload tabel ss_category_request->payload
            $functionAdditional = str_replace("'", '"', $additionalFunction);
            $field = $data_observation_lab['r_master_procedure']['r_category_request']['field'];
            $result_addtional = eval("return {$functionAdditional};");
            $bodyManualObservationLab[$field] = $result_addtional;
        }

        return $bodyManualObservationLab;
    }
    public function bodyManualSpecimen($data_specimen, $data_parameter)
    {
        $bodyManualSpecimen = [
            "resourceType" => "Specimen",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/specimen/" . $data_parameter['organization_id'],
                    "value" => $data_specimen['identifier_1'] . '|' . $data_specimen['procedure_code_original'],
                    "assigner" => [
                        "reference" => "Organization/" . $data_parameter['organization_id'],
                    ]
                ]
            ],
            "status" => "available",
            "type" => [
                "coding" => [
                    [
                        "system" => "http://snomed.info/sct",
                        "code" => $data_specimen['snomed_code'],
                        "display" =>  $data_specimen['snomed_display'],
                    ]
                ]
            ],
            // "collection" => [
            //     "method" => [
            //         "coding" => [
            //             [
            //                 "system" => "http://snomed.info/sct",
            //                 "code" => "82078001",
            //                 "display" => "Collection of blood specimen for laboratory"
            //             ]
            //         ]
            //     ],
            //     "collectedDateTime" => "2022-06-14T08=>15=>00+07=>00"
            // ],
            "subject" => [
                "reference" => "Patient/" . $data_specimen['subject_reference'],
                "display" =>  $data_specimen['subject_display'],
            ],
            "request" => [
                [
                    "reference" => "ServiceRequest/" . $data_specimen['satusehat_id']
                ]
            ],
            "receivedTime" => $this->convertTimeStamp($data_specimen['authored_on']),
        ];
        return $bodyManualSpecimen;
    }
    public function bodyManualServiceRequest($data_service_request, $data_parameter)
    {
        $bodyManualServiceRequest = [
            "resourceType" => "ServiceRequest",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/servicerequest/" . $data_parameter['organization_id'],
                    "value" => $data_service_request['identifier_1'] . '|' . $data_service_request['procedure_code_original']
                ]
            ],
            "status" => "active",
            "intent" => "original-order",
            "priority" => "routine",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://snomed.info/sct",
                            "code" => $data_service_request['category_code'],
                            "display" => $data_service_request['category_display']
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => $data_service_request['coding_code'],
                        "display" => $data_service_request['coding_display']
                    ]
                ],
                "text" => $data_service_request['reason_text']
            ],
            "subject" => [
                "reference" => "Patient/" . $data_service_request['subject_reference']
            ],
            "encounter" => [
                "reference" => "Encounter/" . $data_service_request['r_encounter']['satusehat_id'],
                "display" => "Permintaan Pemeriksaan Golongan Darah Selasa, 14 Juni 2022 pukul 09=>30 WIB"
            ],
            "occurrenceDateTime" => $this->convertTimeStamp($data_service_request['occurrence_datetime']),
            "authoredOn" => $this->convertTimeStamp($data_service_request['authored_on']),
            "requester" => [
                "reference" => "Practitioner/" . $data_service_request['participant_individual_reference'],
                "display" =>  $data_service_request['participant_individual_display']
            ],
            "performer" => [
                [
                    "reference" => "Organization/" . $data_parameter['laboratory_id'],
                    "display" => "Laboratorium"
                ]
            ],
            "reasonCode" => [
                [
                    "text" => $data_service_request['reason_text']
                ]
            ]
        ];

        return $bodyManualServiceRequest;
    }
    public function bodyManualMedication($data_medication_request, $data_parameter)
    {
        $bodyManualMedication = [
            "resourceType" => "Medication",
            "meta" => [
                "profile" => [
                    "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
                ]
            ],
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/medication/" . $data_parameter['organization_id'],
                    "use" => "official",
                    "value" =>  $data_medication_request['identifier_2'] . '-' . $data_medication_request['identifier_1']
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://sys-ids.kemkes.go.id/kfa",
                        "code" => $data_medication_request['r_medication']['r_kfa'][0]['kode_kfa'],
                        "display" => $data_medication_request['r_medication']['r_kfa'][0]['nama_kfa']
                    ]
                ]
            ],
            "status" => "active",
            "manufacturer" => [
                "reference" => "Organization/" . $data_parameter['farmasi_id']
            ],
            "form" => [
                "coding" => [
                    [
                        "system" => "http://terminology.kemkes.go.id/CodeSystem/medication-form",
                        "code" => $data_medication_request['r_medication']['r_kfa'][0]['kode_sediaan'],
                        "display" => $data_medication_request['r_medication']['r_kfa'][0]['nama_sediaan']
                    ]
                ]
            ],
            "extension" => [
                [
                    "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
                    "valueCodeableConcept" => [
                        "coding" => [
                            [
                                "system" => "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                                "code" => "NC",
                                "display" => "Non-compound"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        # Jika ada KFA masukan ke ingredient
        if (!empty($data_medication_request['r_medication']['r_kfa'])) {

            $bodyManualIngredient = [];
            foreach ($data_medication_request['r_medication']['r_kfa'] as $item_kfa) {
                array_push($bodyManualIngredient,  $this->bodyBundleMedicationIngredient($item_kfa));
            }

            $bodyManualMedication['ingredient'] =  $bodyManualIngredient;
        }
        return $bodyManualMedication;
    }
    public function bodyManualMedicationRequest($data_medication_request, $data_parameter, $satusehat_id_medication)
    {
        $bodyManualMedicationRequest = [
            "resourceType" => "MedicationRequest",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/prescription/" . $data_parameter['organization_id'],
                    "use" => "official",
                    "value" => $data_medication_request['identifier_1']
                ],
                [
                    "system" => "http://sys-ids.kemkes.go.id/prescription-item/" . $data_parameter['organization_id'],
                    "use" => "official",
                    "value" => $data_medication_request['identifier_1'] . '-' . $data_medication_request['identifier_2']
                ]
            ],
            "status" => "completed",
            "intent" => "order",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
                            "code" => "outpatient",
                            "display" => "Outpatient"
                        ]
                    ]
                ]
            ],
            "priority" => "routine",
            "medicationReference" => [
                "reference" => "Medication/" . $satusehat_id_medication,
                "display" => $data_medication_request['r_medication']['r_kfa'][0]['nama_kfa']
            ],
            "subject" => [
                "reference" => "Patient/" . $data_medication_request['subject_reference'],
                "display" => $data_medication_request['subject_display']
            ],
            "encounter" => [
                "reference" => "Encounter/" . $data_medication_request['r_encounter']['satusehat_id'],
            ],
            "authoredOn" =>  $this->convertTimeStamp($data_medication_request['authored_on']),
            "requester" => [
                "reference" => "Practitioner/" . $data_medication_request['requester_reference'],
                "display" => $data_medication_request['requester_display']
            ],
            // "reasonCode" => [
            //     [
            //         "coding" => [
            //             [
            //                 "system" => "http://hl7.org/fhir/sid/icd-10",
            //                 "code" => "A15.0",
            //                 "display" => "Tuberculosis of lung, confirmed by sputum microscopy with or without culture"
            //             ]
            //         ]
            //     ]
            // ],
            // "courseOfTherapyType" => [
            //     "coding" => [
            //         [
            //             "system" => "http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy",
            //             "code" => "continuous",
            //             "display" => "Continuing long term therapy"
            //         ]
            //     ]
            // ],
            "dosageInstruction" => [
                [
                    "sequence" => 1,
                    "text" => $data_medication_request['dose_quantity_value'],
                    "additionalInstruction" => [
                        [
                            "text" => $data_medication_request['ins_additional']
                        ]
                    ],
                    "patientInstruction" => $data_medication_request['ins_patient'],
                    "timing" => [
                        "repeat" => [
                            "frequency" => (int)$data_medication_request['int_timing_frequency'],
                            "period" => (int)$data_medication_request['int_timing_period'],
                            "periodUnit" => $data_medication_request['int_timing_period_unit']
                        ]
                    ],
                    "route" => [
                        "coding" => [
                            [
                                "system" => "http://www.whocc.no/atc",
                                "code" => $data_medication_request['route_code'],
                                "display" => $data_medication_request['route_display']
                            ]
                        ]
                    ],
                    "doseAndRate" => [
                        [
                            "type" => [
                                "coding" => [
                                    [
                                        "system" => "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                                        "code" => "ordered",
                                        "display" => "Ordered"
                                    ]
                                ]
                            ],
                            "doseQuantity" => [
                                "value" => (int)$data_medication_request['dose_quantity_value'],
                                "unit" => $data_medication_request['dose_quantity_unit'],
                                "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                "code" => $data_medication_request['dose_quantity_code'],
                            ]
                        ]
                    ]
                ]
            ],
            "dispenseRequest" => [
                "dispenseInterval" => [
                    "value" => 1,
                    "unit" => $data_medication_request['dispense_interval_unit'],
                    "system" => "http://unitsofmeasure.org",
                    "code" => $data_medication_request['dispense_interval_code'],
                ],
                "validityPeriod" => [
                    "start" => $this->convertTimeStamp($data_medication_request['validity_period_start']),
                    "end" => $this->convertTimeStamp($data_medication_request['validity_period_end']),
                ],
                // "numberOfRepeatsAllowed" =>  (int)$data_medication_request['number_of_repeats'],
                "quantity" => [
                    "value" => (int)$data_medication_request['quantity_value'],
                    "unit" => $data_medication_request['quantity_unit'],
                    "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                    "code" => $data_medication_request['quantity_code'],
                ],
                "expectedSupplyDuration" => [
                    "value" => (int)$data_medication_request['expected_value'],
                    "unit" => $data_medication_request['expected_unit'],
                    "system" => "http://unitsofmeasure.org",
                    "code" => $data_medication_request['expected_code'],
                ],
                "performer" => [
                    "reference" => "Organization/" . $data_parameter['organization_id']
                ]
            ]
        ];
        return $bodyManualMedicationRequest;
    }

    public function bodyManualMedicationDispense($data_medication_dispense, $data_medication_request, $data_parameter)
    {
        $bodyManualMedicationDispense = [
            "resourceType" => "MedicationDispense",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/prescription/" . $data_parameter['organization_id'],
                    "use" => "official",
                    "value" =>  $data_medication_request['identifier_1']
                ],
                [
                    "system" => "http://sys-ids.kemkes.go.id/prescription-item/" . $data_parameter['organization_id'],
                    "use" => "official",
                    "value" => $data_medication_request['identifier_1'] . '-' . $data_medication_request['identifier_2']
                ]
            ],
            "status" => "completed",
            "category" => [
                "coding" => [
                    [
                        "system" => "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
                        "code" => "outpatient",
                        "display" => "Outpatient"
                    ]
                ]
            ],
            "medicationReference" => [
                "reference" => "Medication/" . $data_medication_request['satusehat_id_medication'],
                "display" =>  $data_medication_request['r_medication']['r_kfa'][0]['nama_kfa']
            ],
            "subject" => [
                "reference" => "Patient/" . $data_medication_request['subject_reference'],
                "display" =>  $data_medication_request['subject_display']
            ],
            "context" => [
                "reference" => "Encounter/" . $data_medication_request['r_encounter']['satusehat_id'],
            ],
            "performer" => [
                [
                    "actor" => [
                        "reference" => "Practitioner/" . $data_medication_request['requester_reference'],
                        "display" => $data_medication_request['requester_display']
                    ]
                ]
            ],
            "location" => [
                "reference" => "Location/" . $data_medication_dispense['farmasi_id'],
                "display" => $data_medication_dispense['farmasi_name'],
            ],
            "authorizingPrescription" => [
                [
                    "reference" => "MedicationRequest/" . $data_medication_request['satusehat_id']
                ]
            ],
            "quantity" => [
                "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                "code" => $data_medication_request['quantity_code'],
                "value" => (int)$data_medication_request['quantity_value'],
            ],
            "daysSupply" => [
                "value" => (int)$data_medication_request['expected_value'],
                "unit" => $data_medication_request['expected_unit'],
                "system" => "http://unitsofmeasure.org",
                "code" => $data_medication_request['expected_code'],
            ],
            "whenPrepared" => $this->convertTimeStamp($data_medication_request['validity_period_start']),
            "whenHandedOver" => $this->convertTimeStamp($data_medication_request['validity_period_end']),
            "dosageInstruction" => [
                [
                    "sequence" => 1,
                    "text" => $data_medication_request['dose_quantity_value'],
                    "timing" => [
                        "repeat" => [
                            "frequency" => (int)$data_medication_request['int_timing_frequency'],
                            "period" =>  (int)$data_medication_request['int_timing_period'],
                            "periodUnit" => $data_medication_request['int_timing_period_unit']
                        ]
                    ],
                    "doseAndRate" => [
                        [
                            "type" => [
                                "coding" => [
                                    [
                                        "system" => "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                                        "code" => "ordered",
                                        "display" => "Ordered"
                                    ]
                                ]
                            ],
                            "doseQuantity" => [
                                "value" => (int)$data_medication_request['dose_quantity_value'],
                                "unit" =>  $data_medication_request['dose_quantity_unit'],
                                "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                "code" => $data_medication_request['dose_quantity_code'],
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return $bodyManualMedicationDispense;
    }
    public function bodyManualComposition($data_composition, $data_parameter)
    {
        $bodyManualComposition = [
            "resourceType" => "Composition",
            "identifier" => [
                "system" => "http://sys-ids.kemkes.go.id/composition/" . $data_parameter['organization_id'],
                "value" => $data_composition['encounter_original_code']
            ],
            "status" => "final",
            "type" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => "18842-5",
                        "display" => "Discharge summary"
                    ]
                ]
            ],
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => "LP173421-1",
                            "display" => "Report"
                        ]
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $data_composition['subject_reference'],
                "display" => $data_composition['subject_display']
            ],
            "encounter" => [
                "reference" => "Encounter/" . $data_composition['r_encounter']['satusehat_id'],
                "display" => "-"
            ],
            "date" => $this->convertTimeStamp($data_composition['date']),
            "author" => [
                [
                    "reference" => "Practitioner/" . $data_composition['author_reference'],
                    "display" => $data_composition['author_display']
                ]
            ],
            "title" => $data_composition['title'],
            "custodian" => [
                "reference" => "Organization/" . $data_parameter['organization_id']
            ],
            "section" => [
                [
                    "code" => [
                        "coding" => [
                            [
                                "system" => "http://loinc.org",
                                "code" => $data_composition['section_code'],
                                "display" => $data_composition['section_code_display']
                            ]
                        ]
                    ],
                    "text" => [
                        "status" => $data_composition['text_status'],
                        "div" => $data_composition['text_div']
                    ]
                ]
            ]
        ];
        return $bodyManualComposition;
    }

    public function bodyManualProcedure($data_procedure, $data_encounter)
    {
        $bodyManualProcedure = [
            "resourceType" => "Procedure",
            "status" => "completed",
            "category" => [
                "coding" => [
                    [
                        "system" => "http://snomed.info/sct",
                        "code" => "103693007",
                        "display" => "Diagnostic procedure"
                    ]
                ],
                "text" => "Diagnostic procedure"
            ],

            "subject" => [
                "reference" => "Patient/" . $data_encounter['subject_reference'],
                "display" => $data_encounter['subject_display'],
            ],
            "encounter" => [
                "reference" => "Encounter/" . $data_encounter['satusehat_id'],
                "display" => "-"
            ],
            "performedPeriod" => [
                "start" => $this->convertTimeStamp($data_encounter['status_history_inprogress_start']),
                "end" => $this->convertTimeStamp($data_encounter['status_history_inprogress_end'])
            ],
            "performer" => [
                [
                    "actor" => [
                        "reference" => "Practitioner/" . $data_encounter['participant_individual_reference'],
                        "display" => $data_encounter['participant_individual_display']
                    ]
                ]
            ]
        ];

        # Jika procedure ada , menambahkan tindakan pada encounter
        if (count($data_procedure) > 0) {

            $bodyBundleItemProcedure = [];

            foreach ($data_procedure as $item_procedure) {
                array_push($bodyBundleItemProcedure,  $this->bodyBundleProcedureIcd9($item_procedure));
            }

            $bodyManualProcedure['code']['coding'] = $bodyBundleItemProcedure;
        }
        # Jika condition ada , menambahkan diagnosa pada encounter
        if (count($data_encounter['r_condition']) > 0) {

            $bodyBundleConditionCoding = [];
            foreach ($data_encounter['r_condition'] as $item_diagnosa) {
                array_push($bodyBundleConditionCoding,  $this->bodyBundleProcedureIcd10($item_diagnosa));
            }

            $varProcedure = ["coding" => $bodyBundleConditionCoding];

            $bodyBundleProcedureFinal = [];
            array_push($bodyBundleProcedureFinal,  $varProcedure);

            $bodyManualProcedure['reasonCode'] = $bodyBundleProcedureFinal;
        }

        return $bodyManualProcedure;
    }

    public function bodyManualObservation($data_observation)
    {
        $bodyManualObservation = [
            "resourceType" => "Observation",
            "status" => $data_observation['status'],
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                            "code" => $data_observation['category_code'],
                            "display" => $data_observation['category_display']
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => $data_observation['code_observation'],
                        "display" => $data_observation['code_display']
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $data_observation['subject_reference']
            ],
            "performer" => [
                [
                    "reference" => "Practitioner/" . $data_observation['performer_reference']
                ]
            ],
            "encounter" => [
                "reference" => "Encounter/" . $data_observation['r_encounter']['satusehat_id'],
                "display" => "-"
            ],
            "effectiveDateTime" => $this->convertTimeStamp($data_observation['effective_datetime']),
            "issued" => $this->convertTimeStamp($data_observation['issued']),
            "valueQuantity" => [
                "value" => (int)$data_observation['quantity_value'],
                "unit" => $data_observation['quantity_unit'],
                "system" => "http://unitsofmeasure.org",
                "code" => $data_observation['quantity_code']
            ]
        ];

        return $bodyManualObservation;
    }

    public function bodyManualCondition($data_condition)
    {
        $bodyManualCondition =  [
            "resourceType" => "Condition",
            "clinicalStatus" => [
                "coding" => [
                    [
                        "system" => "http://terminology.hl7.org/CodeSystem/condition-clinical",
                        "code" =>  $data_condition['clinical_code'],
                        "display" =>  $data_condition['clinical_display']
                    ]
                ]
            ],
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/condition-category",
                            "code" =>  $data_condition['category_code'],
                            "display" =>  $data_condition['category_display']
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://hl7.org/fhir/sid/icd-10",
                        "code" =>  $data_condition['code_icd'],
                        "display" =>  $data_condition['code_icd_display']
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $data_condition['subject_reference'],
                "display" =>  $data_condition['subject_display']
            ],
            "encounter" => [
                "reference" => "Encounter/" . $data_condition['r_encounter']['satusehat_id'],
                "display" =>  $data_condition['encounter_display']
            ]
        ];

        return $bodyManualCondition;
    }

    public function bodyManualEncounter($data_parameter, $data_encounter)
    {
        $bodyManualEncounter = [
            "resourceType" => "Encounter",
            "status" => "arrived",
            "class" => [
                "system" => "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                "code" => $data_encounter['class_code'],
                "display" => $data_encounter['class_display']
            ],
            "subject" => [
                "reference" => "Patient/" . $data_encounter['subject_reference'],
                "display" => $data_encounter['subject_display']
            ],
            "participant" => [
                [
                    "type" => [
                        [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                    "code" => $data_encounter['participant_coding_code'],
                                    "display" => $data_encounter['participant_coding_display']
                                ]
                            ]
                        ]
                    ],
                    "individual" => [
                        "reference" => "Practitioner/" . $data_encounter['participant_individual_reference'],
                        "display" => $data_encounter['participant_individual_display']
                    ]
                ]
            ],
            "period" => [
                "start" => $this->convertTimeStamp($data_encounter['period_start'])
            ],
            "location" => [
                [
                    "location" => [
                        "reference" => "Location/" . $data_encounter['location_reference'],
                        "display" => $data_encounter['location_display']
                    ]
                ]
            ],
            //disini ada diagnosa
            "statusHistory" => [
                [
                    "status" => "arrived",
                    "period" => [
                        "start" => $this->convertTimeStamp($data_encounter['status_history_arrived_start'])
                    ]
                ]
            ],
            "serviceProvider" => [
                "reference" => "Organization/" . $data_parameter['organization_id']
            ],
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/encounter/" . $data_parameter['organization_id'],
                    "value" => $data_encounter['identifier_value']
                ]
            ]
        ];



        return $bodyManualEncounter;
    }

    public function bodyManualEncounterDiagnosis($data_diagnosa)
    {
        $bodyManualEncounterDiagnosis =
            [
                "condition" => [
                    "reference" => "Condition/" . $data_diagnosa['satusehat_id'],
                    "display" => $data_diagnosa['code_icd_display']
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
                "rank" => (int)$data_diagnosa['rank']
            ];
        return $bodyManualEncounterDiagnosis;
    }

    public function bodyManualEncounterUpdate($data_parameter, $data_encounter)
    {
        $bodyManualEncounterUpdate = [
            "resourceType" => "Encounter",
            "id" => $data_encounter->satusehat_id,
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/encounter/" . $data_parameter['organization_id'],
                    "value" => $data_encounter['identifier_value']
                ]
            ],
            "status" => "finished",
            "class" => [
                "system" => "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                "code" => $data_encounter['class_code'],
                "display" => $data_encounter['class_display']
            ],
            "subject" => [
                "reference" => "Patient/" . $data_encounter['subject_reference'],
                "display" => $data_encounter['subject_display']
            ],
            "participant" => [
                [
                    "type" => [
                        [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                    "code" => $data_encounter['participant_coding_code'],
                                    "display" => $data_encounter['participant_coding_display']
                                ]
                            ]
                        ]
                    ],
                    "individual" => [
                        "reference" => "Practitioner/" . $data_encounter['participant_individual_reference'],
                        "display" => $data_encounter['participant_individual_display']
                    ]
                ]
            ],
            "period" => [
                "start" => $this->convertTimeStamp($data_encounter['period_start']),
                "end" => $this->convertTimeStamp($data_encounter['period_end'])
            ],
            "location" => [
                [
                    "location" => [
                        "reference" => "Location/" . $data_encounter['location_reference'],
                        "display" => $data_encounter['location_display']
                    ]
                ]
            ],
            "statusHistory" => [
                [
                    "status" => "arrived",
                    "period" => [
                        "start" => $this->convertTimeStamp($data_encounter['status_history_arrived_start']),
                        "end" => $this->convertTimeStamp($data_encounter['status_history_arrived_end'])
                    ]
                ],
                [
                    "status" => "in-progress",
                    "period" => [
                        "start" => $this->convertTimeStamp($data_encounter['status_history_inprogress_start']),
                        "end" =>  $this->convertTimeStamp($data_encounter['status_history_inprogress_end'])
                    ]
                ],
                [
                    "status" => "finished",
                    "period" => [
                        "start" => $this->convertTimeStamp($data_encounter['status_history_finished_start']),
                        "end" =>  $this->convertTimeStamp($data_encounter['status_history_finished_end'])
                    ]
                ]
            ],
            "serviceProvider" => [
                "reference" => "Organization/" . $data_parameter['organization_id']
            ]
        ];

        if (count($data_encounter['r_condition']) > 0) {

            $bodyBundleEncounterDiagnosis = [];

            foreach ($data_encounter['r_condition'] as $data_diagnosa) {
                array_push($bodyBundleEncounterDiagnosis,  $this->bodyManualEncounterDiagnosis($data_diagnosa));
            }

            $bodyManualEncounterUpdate['diagnosis'] =  $bodyBundleEncounterDiagnosis;
        }

        return $bodyManualEncounterUpdate;
    }
}
