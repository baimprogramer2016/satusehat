<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProcedure extends Model
{
    use HasFactory;

    protected $table = 'ss_master_procedure';
    protected $guarded = ['id'];

    public function r_category_request()
    {
        return $this->hasOne(CategoryRequest::class,  'display', 'category_request');
    }
}
