<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryRequest extends Model
{
    use HasFactory;
    protected $table = 'ss_category_request';

    protected $fillable = ['display', 'payload'];


    public $timestamps  = false;
}
