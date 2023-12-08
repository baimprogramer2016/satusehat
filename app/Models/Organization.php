<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $table = 'ss_organization';
    protected $guarded = ['id'];


    public function r_status()
    {
        return $this->hasOne(Status::class, 'status', 'satusehat_send');
    }

    public function r_partof()
    {
        return $this->hasOne(Organization::class, 'satusehat_id', 'partof_id');
    }
}
