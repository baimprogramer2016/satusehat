<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'ss_patient';

    protected $fillable = ['name', 'nik', 'satusehat_id', 'satusehat_process', 'original_code'];
}
