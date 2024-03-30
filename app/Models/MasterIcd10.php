<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterIcd10 extends Model
{
    use HasFactory;

    protected $table = 'ss_master_icd_10';

    protected $guarded = ['id'];
}
