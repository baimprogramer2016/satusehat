<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kfa extends Model
{
    use HasFactory;
    protected $table = 'ss_kfa';

    protected $guarded = ['id'];
}
