<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    use HasFactory;
    protected $table = 'ss_encounter';

    protected $guarded = ["id"];

    public function r_status()
    {
        return $this->hasOne(Status::class, 'status', 'satusehat_send');
    }

    public function r_condition()
    {
        return $this->hasMany(Condition::class, 'encounter_original_code', 'original_code')->orderBy('ss_condition.rank', 'asc');;
    }
    public function r_patient()
    {
        return $this->hasOne(Patient::class, 'satusehat_id', 'subject_reference');
    }
    public function r_practitioner()
    {
        return $this->hasOne(Practitioner::class, 'satusehat_id', 'participant_individual_reference');
    }
}
