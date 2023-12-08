<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcedureMc extends Model
{
    use HasFactory;
    protected $table = 'ss_procedure_mc';

    protected $guarded = ['id'];
}
