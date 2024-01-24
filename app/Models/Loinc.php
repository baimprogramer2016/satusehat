<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loinc extends Model
{

    use HasFactory;
    protected $table = 'ss_loinc';

    protected $fillable = ['loinc_code', 'loinc_display'];
}
