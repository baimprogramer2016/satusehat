<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(\App\Repositories\Parameter\ParameterInterface::class, \App\Repositories\Parameter\ParameterRepository::class);
        $this->app->bind(\App\Repositories\Organization\OrganizationInterface::class, \App\Repositories\Organization\OrganizationRepository::class);
        $this->app->bind(\App\Repositories\Location\LocationInterface::class, \App\Repositories\Location\LocationRepository::class);
        $this->app->bind(\App\Repositories\Status\StatusInterface::class, \App\Repositories\Status\StatusRepository::class);
        $this->app->bind(\App\Repositories\PhysicalType\PhysicalTypeInterface::class, \App\Repositories\PhysicalType\PhysicalTypeRepository::class);
        $this->app->bind(\App\Repositories\Patient\PatientInterface::class, \App\Repositories\Patient\PatientRepository::class);
        $this->app->bind(\App\Repositories\Practitioner\PractitionerInterface::class, \App\Repositories\Practitioner\PractitionerRepository::class);
        $this->app->bind(\App\Repositories\Kfa\KfaInterface::class, \App\Repositories\Kfa\KfaRepository::class);
        $this->app->bind(\App\Repositories\Medication\MedicationInterface::class, \App\Repositories\Medication\MedicationRepository::class);
        $this->app->bind(\App\Repositories\Encounter\EncounterInterface::class, \App\Repositories\Encounter\EncounterRepository::class);
        $this->app->bind(\App\Repositories\Condition\ConditionInterface::class, \App\Repositories\Condition\ConditionRepository::class);
        $this->app->bind(\App\Repositories\Observation\ObservationInterface::class, \App\Repositories\Observation\ObservationRepository::class);
        $this->app->bind(\App\Repositories\Procedure\ProcedureInterface::class, \App\Repositories\Procedure\ProcedureRepository::class);
        $this->app->bind(\App\Repositories\Composition\CompositionInterface::class, \App\Repositories\Composition\CompositionRepository::class);
        $this->app->bind(\App\Repositories\Sinkronisasi\SinkronisasiInterface::class, \App\Repositories\Sinkronisasi\SinkronisasiRepository::class);
        $this->app->bind(\App\Repositories\Jadwal\JadwalInterface::class, \App\Repositories\Jadwal\JadwalRepository::class);
        $this->app->bind(\App\Repositories\Jobs\JobsInterface::class, \App\Repositories\Jobs\JobsRepository::class);
        $this->app->bind(\App\Repositories\JobLogs\JobLogsInterface::class, \App\Repositories\JobLogs\JobLogsRepository::class);
        $this->app->bind(\App\Repositories\MedicationRequest\MedicationRequestInterface::class, \App\Repositories\MedicationRequest\MedicationRequestRepository::class);
        $this->app->bind(\App\Repositories\MedicationDispense\MedicationDispenseInterface::class, \App\Repositories\MedicationDispense\MedicationDispenseRepository::class);
        $this->app->bind(\App\Repositories\Account\AccountInterface::class, \App\Repositories\Account\AccountRepository::class);
        $this->app->bind(\App\Repositories\Upload\UploadInterface::class, \App\Repositories\Upload\UploadRepository::class);
        $this->app->bind(\App\Repositories\Snomed\SnomedInterface::class, \App\Repositories\Snomed\SnomedRepository::class);
        $this->app->bind(\App\Repositories\Loinc\LoincInterface::class, \App\Repositories\Loinc\LoincRepository::class);
        $this->app->bind(\App\Repositories\MasterProcedure\MasterProcedureInterface::class, \App\Repositories\MasterProcedure\MasterProcedureRepository::class);
        $this->app->bind(\App\Repositories\ServiceRequest\ServiceRequestInterface::class, \App\Repositories\ServiceRequest\ServiceRequestRepository::class);
        $this->app->bind(\App\Repositories\Specimen\SpecimenInterface::class, \App\Repositories\Specimen\SpecimenRepository::class);
        $this->app->bind(\App\Repositories\ObservationLab\ObservationLabInterface::class, \App\Repositories\ObservationLab\ObservationLabRepository::class);
        $this->app->bind(\App\Repositories\CategoryRequest\CategoryRequestInterface::class, \App\Repositories\CategoryRequest\CategoryRequestRepository::class);
        $this->app->bind(\App\Repositories\DiagnosticReport\DiagnosticReportInterface::class, \App\Repositories\DiagnosticReport\DiagnosticReportRepository::class);
        $this->app->bind(\App\Repositories\Dashboard\DashboardInterface::class, \App\Repositories\Dashboard\DashboardRepository::class);
        $this->app->bind(\App\Repositories\LogError\LogErrorInterface::class, \App\Repositories\LogError\LogErrorRepository::class);
        $this->app->bind(\App\Repositories\MasterIcd10\MasterIcd10Interface::class, \App\Repositories\MasterIcd10\MasterIcd10Repository::class);
        $this->app->bind(\App\Repositories\ServiceRequestRadiology\ServiceRequestRadiologyInterface::class, \App\Repositories\ServiceRequestRadiology\ServiceRequestRadiologyRepository::class);
        $this->app->bind(\App\Repositories\AllergyCode\AllergyCodeInterface::class, \App\Repositories\AllergyCode\AllergyCodeRepository::class);
        $this->app->bind(\App\Repositories\AllergyMaster\AllergyMasterInterface::class, \App\Repositories\AllergyMaster\AllergyMasterRepository::class);
        $this->app->bind(\App\Repositories\Allergy\AllergyInterface::class, \App\Repositories\Allergy\AllergyRepository::class);
        $this->app->bind(\App\Repositories\PrognosisCode\PrognosisCodeInterface::class, \App\Repositories\PrognosisCode\PrognosisCodeRepository::class);
        $this->app->bind(\App\Repositories\PrognosisMaster\PrognosisMasterInterface::class, \App\Repositories\PrognosisMaster\PrognosisMasterRepository::class);
        $this->app->bind(\App\Repositories\Prognosis\PrognosisInterface::class, \App\Repositories\Prognosis\PrognosisRepository::class);
        $this->app->bind(\App\Repositories\RencanaTindakLanjut\RencanaTindakLanjutInterface::class, \App\Repositories\RencanaTindakLanjut\RencanaTindakLanjutRepository::class);
        $this->app->bind(\App\Repositories\RencanaTindakLanjutCode\RencanaTindakLanjutCodeInterface::class, \App\Repositories\RencanaTindakLanjutCode\RencanaTindakLanjutCodeRepository::class);
        $this->app->bind(\App\Repositories\RencanaTindakLanjutMaster\RencanaTindakLanjutMasterInterface::class, \App\Repositories\RencanaTindakLanjutMaster\RencanaTindakLanjutMasterRepository::class);
        $this->app->bind(\App\Repositories\CatatanPengobatan\CatatanPengobatanInterface::class, \App\Repositories\CatatanPengobatan\CatatanPengobatanRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
