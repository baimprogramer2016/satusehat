<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sinkronisasi extends Model
{
    use HasFactory;

    protected $table = 'ss_sinkronisasi';
    protected $guarded = ['id'];
}
