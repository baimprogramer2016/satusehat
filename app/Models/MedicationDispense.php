<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationDispense extends Model
{
    use HasFactory;
    protected $table = 'ss_medication_dispense';

    protected $guarded = ['id'];

    public function r_status()
    {
        return $this->hasOne(Status::class, 'status', 'satusehat_send');
    }

    public function r_medication()
    {
        return $this->hasOne(Medication::class,  'original_code', 'identifier_2');
    }
    public function r_medication_request()
    {
        return $this->hasMany(MedicationRequest::class,  'encounter_original_code', 'encounter_original_code');
    }

    public function r_encounter()
    {
        return $this->hasOne(Encounter::class,  'original_code', 'encounter_original_code');
    }
}
