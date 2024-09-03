<?php

namespace App\Repositories\Dashboard;

use App\Models\Allergy;
use App\Models\Composition;
use App\Models\Condition;
use App\Models\Dashboard;
use App\Models\Encounter;
use App\Models\MedicationDispense;
use App\Models\MedicationRequest;
use App\Models\Observation;
use App\Models\Procedure;
use App\Models\Prognosis;
use App\Models\RencanaTindakLanjut;
use App\Models\ServiceRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardRepository implements DashboardInterface
{
    private $model, $condition_model, $observation_model, $medication_request_model, $medication_dispense_model;
    private $procedure_model, $composition_model, $service_request_model, $allergy_model, $prognosis_model, $rencana_tindak_lanjut_model;

    public function __construct(
        Encounter $encounter_model,
        Condition $condition_model,
        Observation $observation_model,
        MedicationRequest $medication_request_model,
        MedicationDispense $medication_dispense_model,
        Procedure $procedure_model,
        Composition $composition_model,
        ServiceRequest $service_request_model,
        Allergy $allergy_model,
        Prognosis $prognosis_model,
        RencanaTindakLanjut $rencana_tindak_lanjut_model

    ) {
        $this->model = $encounter_model;
        $this->condition_model = $condition_model;
        $this->observation_model = $observation_model;
        $this->medication_request_model = $medication_request_model;
        $this->medication_dispense_model = $medication_dispense_model;
        $this->procedure_model = $procedure_model;
        $this->composition_model = $composition_model;
        $this->service_request_model = $service_request_model;
        $this->allergy_model = $allergy_model;
        $this->prognosis_model = $prognosis_model;
        $this->rencana_tindak_lanjut_model = $rencana_tindak_lanjut_model;
    }


    //DASHBOARD
    public function getCurrentYear()
    {
        return $this->model->whereYear('period_start', Carbon::now()->year)->count();
    }
    public function getCurrentYearSucces()
    {
        return $this->model->whereYear('period_start', Carbon::now()->year)->where('satusehat_statuscode', 200)->count();
    }
    public function getCurrentYearFailed()
    {
        return $this->model->whereYear('period_start', Carbon::now()->year)->where('satusehat_statuscode', 500)->count();
    }
    public function getCurrentYearWaiting()
    {
        return $this->model->whereYear('period_start', Carbon::now()->year)->where('satusehat_send', '!=', '1')->whereNull('satusehat_statuscode')->count();
    }

    public function getYearAll()
    {
        return $this->model->count();
    }
    public function getYearSuccesAll()
    {
        return $this->model->where('satusehat_statuscode', 200)->count();
    }
    public function getYearFailedAll()
    {
        return $this->model->where('satusehat_statuscode', 500)->count();
    }
    public function getYearWaitingAll()
    {
        return $this->model->where('satusehat_send', '!=', '1')->whereNull('satusehat_statuscode')->count();
    }

    public function getConditionAll()
    {
        $result['condition_success'] = $this->condition_model->where('satusehat_statuscode', 200)->count();
        $result['condition_failed'] = $this->condition_model->where('satusehat_statuscode', 500)->count();
        $result['condition_waiting'] = $this->condition_model->whereNull('satusehat_statuscode')->count();
        return $result;
    }
    public function getProcedureAll()
    {
        $result['procedure_success'] = $this->procedure_model->where('satusehat_statuscode', 200)->count();
        $result['procedure_failed'] = $this->procedure_model->where('satusehat_statuscode', 500)->count();
        $result['procedure_waiting'] = $this->procedure_model->whereNull('satusehat_statuscode')->count();
        return $result;
    }
    public function getCompositionAll()
    {
        $result['composition_success'] = $this->composition_model->where('satusehat_statuscode', 200)->count();
        $result['composition_failed'] = $this->composition_model->where('satusehat_statuscode', 500)->count();
        $result['composition_waiting'] = $this->composition_model->whereNull('satusehat_statuscode')->count();
        return $result;
    }

    public function getConditionSuccessAll() {}

    public function getObservationAll()
    {
        $result['observation_success'] = $this->observation_model->where('satusehat_statuscode', 200)->whereIn('type_observation', ['suhu', 'diastole', 'sistol', 'nadi', 'pernapasan'])->count();
        $result['observation_failed'] = $this->observation_model->where('satusehat_statuscode', 500)->whereIn('type_observation', ['suhu', 'diastole', 'sistol', 'nadi', 'pernapasan'])->count();
        $result['observation_waiting'] = $this->observation_model->whereNull('satusehat_statuscode')->whereIn('type_observation', ['suhu', 'diastole', 'sistol', 'nadi', 'pernapasan'])->count();
        return $result;
    }

    public function getObservationSuccessAll() {}

    public function getMedicationRequestAll()
    {
        $result['medication_request_success'] = $this->medication_request_model->where('satusehat_statuscode', 200)->count();
        $result['medication_request_failed'] = $this->medication_request_model->where('satusehat_statuscode', 500)->count();
        $result['medication_request_waiting'] = $this->medication_request_model->whereNull('satusehat_statuscode')->count();
        return $result;
    }
    public function getMedicationRequestSuccessAll() {}
    public function getMedicationDispenseAll()
    {
        $result['medication_dispense_success'] = $this->medication_dispense_model->where('satusehat_statuscode', 200)->count();
        $result['medication_dispense_failed'] = $this->medication_dispense_model->where('satusehat_statuscode', 500)->count();
        $result['medication_dispense_waiting'] = $this->medication_dispense_model->whereNull('satusehat_statuscode')->count();
        return $result;
    }
    public function getServiceRequestAll()
    {
        $result['service_request_success'] = $this->service_request_model->where('satusehat_statuscode', 200)->count();
        $result['service_request_failed'] = $this->service_request_model->where('satusehat_statuscode', 500)->count();
        $result['service_request_waiting'] = $this->service_request_model->whereNull('satusehat_statuscode')->count();
        return $result;
    }
    public function getSprecimenAll()
    {
        $result['specimen_success'] = $this->service_request_model->where('satusehat_statuscode_specimen', 200)->count();
        $result['specimen_failed'] = $this->service_request_model->where('satusehat_statuscode_specimen', 500)->count();
        $result['specimen_waiting'] = $this->service_request_model->whereNull('satusehat_statuscode_specimen')->count();
        return $result;
    }
    public function getObservationLabAll()
    {
        $result['observation_lab_success'] = $this->observation_model->where('satusehat_statuscode', 200)->whereIn('type_observation', ['Laboratory'])->count();
        $result['observation_lab_failed'] = $this->observation_model->where('satusehat_statuscode', 500)->whereIn('type_observation', ['Laboratory'])->count();
        $result['observation_lab_waiting'] = $this->observation_model->whereNull('satusehat_statuscode')->whereIn('type_observation', ['Laboratory'])->count();
        return $result;
    }
    public function getDiagnosticReportAll()
    {
        $result['diagnostic_report_success'] = $this->service_request_model->where('satusehat_statuscode_diagnostic_report', 200)->count();
        $result['diagnostic_report_failed'] = $this->service_request_model->where('satusehat_statuscode_diagnostic_report', 500)->count();
        $result['diagnostic_report_waiting'] = $this->service_request_model->whereNull('satusehat_statuscode_diagnostic_report')->count();
        return $result;
    }
    public function getAllergyAll()
    {
        $result['allergy_success'] = $this->allergy_model->where('satusehat_statuscode', 200)->count();
        $result['allergy_failed'] = $this->allergy_model->where('satusehat_statuscode', 500)->count();
        $result['allergy_waiting'] = $this->allergy_model->whereNull('satusehat_statuscode')->count();
        return $result;
    }
    public function getPrognosisAll()
    {
        $result['prognosis_success'] = $this->prognosis_model->where('satusehat_statuscode', 200)->count();
        $result['prognosis_failed'] = $this->prognosis_model->where('satusehat_statuscode', 500)->count();
        $result['prognosis_waiting'] = $this->prognosis_model->whereNull('satusehat_statuscode')->count();
        return $result;
    }
    public function getRencanaTindakLanjutAll()
    {
        $result['rencana_tindak_lanjut_success'] = $this->rencana_tindak_lanjut_model->where('satusehat_statuscode', 200)->count();
        $result['rencana_tindak_lanjut_failed'] = $this->rencana_tindak_lanjut_model->where('satusehat_statuscode', 500)->count();
        $result['rencana_tindak_lanjut_waiting'] = $this->rencana_tindak_lanjut_model->whereNull('satusehat_statuscode')->count();
        return $result;
    }
    public function getMedicationDispenseSuccessAll() {}

    public function getObservationLabSuccessAll() {}

    // public function getConditionAll()
    // {
    //     return $this->condition_model->count();
    // }
    // public function getConditionSuccessAll()
    // {
    //     return $this->condition_model->where('satusehat_statuscode', 200)->count();
    // }

    // public function getObservationAll()
    // {
    //     return $this->observation_model->whereIn('type_observation', ['suhu', 'diastole', 'sistol', 'nadi', 'pernapasan'])->count();
    // }
    // public function getObservationSuccessAll()
    // {
    //     return $this->observation_model->where('satusehat_statuscode', 200)->whereIn('type_observation', ['suhu', 'diastole', 'sistol', 'nadi', 'pernapasan'])->count();
    // }
    // public function getMedicationRequestAll()
    // {
    //     return $this->medication_request_model->count();
    // }
    // public function getMedicationRequestSuccessAll()
    // {
    //     return $this->medication_request_model->where('satusehat_statuscode', 200)->count();
    // }
    // public function getMedicationDispenseAll()
    // {
    //     return $this->medication_dispense_model->count();
    // }
    // public function getMedicationDispenseSuccessAll()
    // {
    //     return $this->medication_dispense_model->where('satusehat_statuscode', 200)->count();
    // }
    // public function getObservationLabAll()
    // {
    //     return $this->observation_model->whereIn('type_observation', ['Laboratory'])->count();
    // }
    // public function getObservationLabSuccessAll()
    // {
    //     return $this->observation_model->where('satusehat_statuscode', 200)->whereIn('type_observation', ['Laboratory'])->count();
    // }

    //LAPORAN
    public function getLaporanEncounter($param = [])
    {
        $status = $param['status'];
        $tanggal_awal = date_create_from_format('m/d/Y', $param['tanggal_awal'])->format('Y-m-d');
        $tanggal_akhir = date_create_from_format('m/d/Y', $param['tanggal_akhir'])->format('Y-m-d');

        $query = $this->model->query()
            ->when($status == 'success', function ($q) {
                return $q->where('satusehat_send', 1)->where('satusehat_statuscode', '200');
            })
            ->when($status == 'failed', function ($q) {
                return $q->where('satusehat_statuscode', '500');
            })
            ->when($status == 'waiting', function ($q) {
                return $q->whereNull('satusehat_statuscode')->where('satusehat_send', '!=', 1);
            })
            ->whereBetween(DB::raw('period_start::date'), [$tanggal_awal, $tanggal_akhir])
            ->get();
        return $query;
    }

    public function getLaporanCondition($param = [])
    {
        $status = $param['status'];
        $tanggal_awal = date_create_from_format('m/d/Y', $param['tanggal_awal'])->format('Y-m-d');
        $tanggal_akhir = date_create_from_format('m/d/Y', $param['tanggal_akhir'])->format('Y-m-d');

        $query = $this->condition_model->query()
            ->when($status == 'success', function ($q) {
                return $q->where('satusehat_send', 1)->where('satusehat_statuscode', '200');
            })
            ->when($status == 'failed', function ($q) {
                return $q->where('satusehat_statuscode', '500');
            })
            ->when($status == 'waiting', function ($q) {
                return $q->whereNull('satusehat_statuscode')->where('satusehat_send', '!=', 1);
            })
            ->whereBetween(DB::raw('onset_datetime::date'), [$tanggal_awal, $tanggal_akhir])
            ->get();
        return $query;
    }
    public function getLaporanObservation($param = [])
    {
        $status = $param['status'];
        $tanggal_awal = date_create_from_format('m/d/Y', $param['tanggal_awal'])->format('Y-m-d');
        $tanggal_akhir = date_create_from_format('m/d/Y', $param['tanggal_akhir'])->format('Y-m-d');

        $query = $this->observation_model->query()
            ->when($status == 'success', function ($q) {
                return $q->where('satusehat_send', 1)->where('satusehat_statuscode', '200');
            })
            ->when($status == 'failed', function ($q) {
                return $q->where('satusehat_statuscode', '500');
            })
            ->when($status == 'waiting', function ($q) {
                return $q->whereNull('satusehat_statuscode')->where('satusehat_send', '!=', 1);
            })
            ->whereIn('type_observation', ['suhu', 'diastole', 'sistol', 'nadi', 'pernapasan'])
            ->whereBetween(DB::raw('effective_datetime::date'), [$tanggal_awal, $tanggal_akhir])
            ->get();
        return $query;
    }
    public function getLaporanMedicationRequest($param = [])
    {
        $status = $param['status'];
        $tanggal_awal = date_create_from_format('m/d/Y', $param['tanggal_awal'])->format('Y-m-d');
        $tanggal_akhir = date_create_from_format('m/d/Y', $param['tanggal_akhir'])->format('Y-m-d');

        $query = $this->medication_request_model->query()
            ->when($status == 'success', function ($q) {
                return $q->where('satusehat_send', 1)->where('satusehat_statuscode', '200');
            })
            ->when($status == 'failed', function ($q) {
                return $q->where('satusehat_statuscode', '500');
            })
            ->when($status == 'waiting', function ($q) {
                return $q->whereNull('satusehat_statuscode')->where('satusehat_send', '!=', 1);
            })
            ->whereBetween(DB::raw('validity_period_start::date'), [$tanggal_awal, $tanggal_akhir])
            ->get();
        return $query;
    }
    public function getLaporanMedicationDispense($param = [])
    {
        $status = $param['status'];
        $tanggal_awal = date_create_from_format('m/d/Y', $param['tanggal_awal'])->format('Y-m-d');
        $tanggal_akhir = date_create_from_format('m/d/Y', $param['tanggal_akhir'])->format('Y-m-d');

        $query = $this->medication_dispense_model->query();
        $query = $query->join('ss_medication_request', function ($join) use ($tanggal_awal, $tanggal_akhir, $status) {
            $join->on('ss_medication_request.encounter_original_code', '=', 'ss_medication_dispense.encounter_original_code')
                ->on('ss_medication_request.identifier_1', '=', 'ss_medication_dispense.identifier_1')
                ->on('ss_medication_request.identifier_2', '=', 'ss_medication_dispense.identifier_2')
                ->when($status == 'success', function ($q) {
                    return $q->where('ss_medication_dispense.satusehat_send', 1)->where('ss_medication_dispense.satusehat_statuscode', '200');
                })
                ->when($status == 'failed', function ($q) {
                    return $q->where('ss_medication_dispense.satusehat_statuscode', '500');
                })
                ->when($status == 'waiting', function ($q) {
                    return $q->whereNull('ss_medication_dispense.satusehat_statuscode')->where('ss_medication_dispense.satusehat_send', '!=', 1);
                });
        })
            ->whereBetween(DB::raw('ss_medication_request.validity_period_start::date'), [$tanggal_awal, $tanggal_akhir])
            ->select('ss_medication_dispense.*', 'ss_medication_request.validity_period_start')
            ->get();
        // return dd($query);
        return $query;
    }
    public function getLaporanLaboratorium($param = [])
    {
        $status = $param['status'];
        $tanggal_awal = date_create_from_format('m/d/Y', $param['tanggal_awal'])->format('Y-m-d');
        $tanggal_akhir = date_create_from_format('m/d/Y', $param['tanggal_akhir'])->format('Y-m-d');

        $query = $this->observation_model->query()
            ->when($status == 'success', function ($q) {
                return $q->where('satusehat_send', 1)->where('satusehat_statuscode', '200');
            })
            ->when($status == 'failed', function ($q) {
                return $q->where('satusehat_statuscode', '500');
            })
            ->when($status == 'waiting', function ($q) {
                return $q->whereNull('satusehat_statuscode')->where('satusehat_send', '!=', 1);
            })
            ->whereIn('type_observation', ['Laboratory'])
            ->whereBetween(DB::raw('effective_datetime::date'), [$tanggal_awal, $tanggal_akhir])
            ->get();
        return $query;
    }
}
