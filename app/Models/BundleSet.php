<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleSet extends Model
{
    use HasFactory;

    protected $table = 'ss_bundle_set';

    protected $guarded = ['id'];

    public $timestamps = false;
}
