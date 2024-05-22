<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllergyMaster extends Model
{
    use HasFactory;
    protected $table = 'ss_master_allergy';

    protected $guarded = ['id'];

    public function r_allergy_code()
    {
        return $this->hasOne(AllergyCode::class,  'code', 'code_satusehat');
    }

    public $timestamps  = false;
}
