<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait JsonTrait
{
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


        # push ke entry
        $bodyEncounter = $this->bodyBundleEncounter($data_parameter, $data_encounter);
        array_push($bodyBundle['entry'], $bodyEncounter);

        #condition
        # ambiJika ada ,Ambil dari relasion dari model encounter r_condition dengan parameter encounter
        if (count($data_encounter['r_condition']) > 0) {
            foreach ($data_encounter['r_condition'] as $data_diagnosa) {
                $bodyCondition = $this->bodyBundleCondition($data_diagnosa, $data_encounter['uuid']);
                array_push($bodyBundle['entry'], $bodyCondition);
            }
        }

        # observation
        if (count($data_observation) > 0) {
            foreach ($data_observation as $item_observation) {
                $bodyObservation = $this->bodyBundleObservation($item_observation, $data_encounter['uuid']);
                array_push($bodyBundle['entry'], $bodyObservation);
            }
        }
        # procedure
        if (count($data_procedure) > 0) {
            $bodyProcedure = $this->bodyBundleProcedure($data_encounter, $data_encounter['r_condition'], $data_procedure);
            array_push($bodyBundle['entry'], $bodyProcedure);
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
                        "text" => "Rontgen thorax melihat perluasan infiltrat dan kavitas."
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


    #############################  MANUAL ###################################
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
