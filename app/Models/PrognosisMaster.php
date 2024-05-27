<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrognosisMaster extends Model
{
    use HasFactory;
    protected $table = 'ss_master_prognosis';

    protected $guarded = ['id'];

    public function r_prognosis_code()
    {
        return $this->hasOne(PrognosisCode::class,  'code', 'code_satusehat');
    }

    public $timestamps  = false;
}
