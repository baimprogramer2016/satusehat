<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{
    use HasFactory;
    protected $table = 'ss_composition';

    protected $guarded = ['id'];

    public function r_status()
    {
        return $this->hasOne(Status::class, 'status', 'satusehat_send');
    }
    public function r_encounter()
    {
        return $this->hasOne(Encounter::class,  'original_code', 'encounter_original_code');
    }
}
