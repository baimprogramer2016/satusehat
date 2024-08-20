<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaTindakLanjutMaster extends Model
{
    use HasFactory;
    protected $table = 'ss_master_rencana_tindak_lanjut';

    protected $guarded = ['id'];

    public function r_rencana_tindak_lanjut_code()
    {
        return $this->hasOne(RencanaTindakLanjutCode::class,  'code', 'code_satusehat');
    }

    public $timestamps  = false;
}
