<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosticReport extends Model
{
    use HasFactory;
    protected $table = 'ss_service_request';

    protected $guarded = ['id'];

    public function r_status()
    {
        return $this->hasOne(Status::class, 'status', 'satusehat_send_diagnostic_report');
    }
    public function r_encounter()
    {
        return $this->hasOne(Encounter::class,  'original_code', 'encounter_original_code');
    }
    public function r_master_procedure()
    {
        return $this->hasOne(MasterProcedure::class,  'original_code', 'procedure_code_original');
    }
    public function r_observation()
    {
        return $this->hasOne(Observation::class,  'uuid', 'uuid_observation');
    }
}
