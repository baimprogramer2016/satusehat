<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescResource extends Model
{
    use HasFactory;

    protected $table = 'ss_desc_resource';
    protected $guarded = ['id'];
}
