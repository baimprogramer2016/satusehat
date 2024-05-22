<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllergyCode extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'ss_allergy_code';

    protected $guarded = ['id'];
}
