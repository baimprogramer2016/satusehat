<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practitioner extends Model
{
    use HasFactory;
    protected $table = 'ss_practitioner';

    protected $fillable = ['name', 'nik', 'satusehat_id', 'original_code', 'satusehat_process'];
}
