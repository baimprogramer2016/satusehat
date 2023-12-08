<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalType extends Model
{
    use HasFactory;

    protected $table = 'ss_physical_type';

    protected $guarded = ['id'];
}
