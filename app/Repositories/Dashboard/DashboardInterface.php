<?php

namespace App\Repositories\Dashboard;

interface DashboardInterface
{
    //DASHBOARD
    public function getCurrentYear();
    public function getCurrentYearSucces();
    public function getCurrentYearFailed();
    public function getCurrentYearWaiting();

    public function getYearAll();
    public function getYearSuccesAll();
    public function getYearFailedAll();
    public function getYearWaitingAll();
    public function getConditionAll();
    public function getConditionSuccessAll();
    public function getObservationAll();
    public function getProcedureAll();
    public function getCompositionAll();
    public function getObservationSuccessAll();

    public function getMedicationRequestAll();
    public function getMedicationRequestSuccessAll();

    public function getMedicationDispenseAll();
    public function getMedicationDispenseSuccessAll();
    public function getServiceRequestAll();
    public function getSprecimenAll();
    public function getObservationLabAll();

    public function getDiagnosticReportAll();
    public function getAllergyAll();
    public function getPrognosisAll();
    public function getRencanaTindakLanjutAll();



    public function getObservationLabSuccessAll();


    //LAPORAN
    public function getLaporanEncounter($param = []);
    public function getLaporanCondition($param = []);
    public function getLaporanObservation($param = []);
    public function getLaporanMedicationRequest($param = []);
    public function getLaporanMedicationDispense($param = []);
    public function getLaporanLaboratorium($param = []);
}
