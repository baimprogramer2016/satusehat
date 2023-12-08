<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snomed extends Model
{
    use HasFactory;
    protected $table = 'ss_snomed';

    protected $fillable = ['snomed_code', 'snomed_display', 'original_code', 'original_display', 'category'];
}
