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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
