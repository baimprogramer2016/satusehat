<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Models\MedicationDispense;
use App\Repositories\CategoryRequest\CategoryRequestInterface;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\Encounter\EncounterInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\GeneralTrait;
use Throwable;

class DashboardController extends Controller
{

    use GeneralTrait;
    private $data_dashboard_repo;
    public function __construct(
        DashboardInterface $dashboardInterface,
    ) {
        $this->data_dashboard_repo = $dashboardInterface;
    }

    public function index()
    {
        try {
            // $data_result = [
            //     "data_current_year" => $this->data_dashboard_repo->getCurrentYear(),
            //     "data_current_year_success" => $this->data_dashboard_repo->getCurrentYearSucces(),
            //     "data_current_year_failed" => $this->data_dashboard_repo->getCurrentYearFailed(),
            //     "data_current_year_waiting" => $this->data_dashboard_repo->getCurrentYearWaiting(),
            //     "data_year_all" => $this->data_dashboard_repo->getYearAll(),
            //     "data_year_success_all" => $this->data_dashboard_repo->getYearSuccesAll(),
            //     "data_year_failed_all" => $this->data_dashboard_repo->getYearFailedAll(),
            //     "data_year_waiting_all" => $this->data_dashboard_repo->getYearWaitingAll()
            // ];

            // , [
            //     "data_dashboard" => $data_result,
            //     "data_condition" => $this->data_dashboard_repo->getConditionAll(),
            //     "data_observation" => $this->data_dashboard_repo->getObservationAll(),
            //     "data_procedure" => $this->data_dashboard_repo->getProcedureAll(),
            //     "data_composition" => $this->data_dashboard_repo->getCompositionAll(),
            //     "data_medication_request" => $this->data_dashboard_repo->getMedicationRequestAll(),
            //     "data_medication_dispense" => $this->data_dashboard_repo->getMedicationDispenseAll(),
            //     "data_service_request" => $this->data_dashboard_repo->getServiceRequestAll(),
            //     "data_specimen" => $this->data_dashboard_repo->getSprecimenAll(),
            //     "data_observation_lab" => $this->data_dashboard_repo->getObservationLabAll(),
            //     "data_diagnostic_report" => $this->data_dashboard_repo->getDiagnosticReportAll(),
            //     "data_allergy" => $this->data_dashboard_repo->getAllergyAll(),
            //     "data_prognosis" => $this->data_dashboard_repo->getPrognosisAll(),
            // ]
            return view('pages.dashboard.dashboard');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function laporan()
    {
        return view("pages.dashboard.dashboard-laporan");
    }

    public function laporanDownload(Request $request)
    {
        // return date_create_from_format('m/d/Y', $request->tanggal_awal)->format('Y-m-d');
        try {
            $data_export = new LaporanExport(
                $request->all(),
                $this->data_dashboard_repo
            );
            return ($data_export)->download('download_' . $request->resource . '.xlsx');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function runService()
    {
        // Artisan::call('schedule:work');
        Artisan::call('queue:work');

        return response()->json(['message' => 'Schedule Worker started']);
    }

    public function refreshDashboard()
    {
        $data_result = [
            "data_current_year" => $this->data_dashboard_repo->getCurrentYear(),
            "data_current_year_success" => $this->data_dashboard_repo->getCurrentYearSucces(),
            "data_current_year_failed" => $this->data_dashboard_repo->getCurrentYearFailed(),
            "data_current_year_waiting" => $this->data_dashboard_repo->getCurrentYearWaiting(),
            "data_year_all" => $this->data_dashboard_repo->getYearAll(),
            "data_year_success_all" => $this->data_dashboard_repo->getYearSuccesAll(),
            "data_year_failed_all" => $this->data_dashboard_repo->getYearFailedAll(),
            "data_year_waiting_all" => $this->data_dashboard_repo->getYearWaitingAll()
        ];
        $data_json = [
            "data_encounter" => $data_result,
            "data_condition" => $this->data_dashboard_repo->getConditionAll(),
            "data_observation" => $this->data_dashboard_repo->getObservationAll(),
            "data_procedure" => $this->data_dashboard_repo->getProcedureAll(),
            "data_composition" => $this->data_dashboard_repo->getCompositionAll(),
            "data_medication_request" => $this->data_dashboard_repo->getMedicationRequestAll(),
            "data_medication_dispense" => $this->data_dashboard_repo->getMedicationDispenseAll(),
            "data_service_request" => $this->data_dashboard_repo->getServiceRequestAll(),
            "data_specimen" => $this->data_dashboard_repo->getSprecimenAll(),
            "data_observation_lab" => $this->data_dashboard_repo->getObservationLabAll(),
            "data_diagnostic_report" => $this->data_dashboard_repo->getDiagnosticReportAll(),
            "data_allergy" => $this->data_dashboard_repo->getAllergyAll(),
            "data_prognosis" => $this->data_dashboard_repo->getPrognosisAll(),
            "data_rencana_tindak_lanjut" => $this->data_dashboard_repo->getRencanaTindakLanjutAll(),
            "data_catatan_pengobatan" => $this->data_dashboard_repo->getCatatanPengobatanAll(),
        ];

        return response()->json($data_json);
    }
}
