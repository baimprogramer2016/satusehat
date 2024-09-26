<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = 'ss_location';
    protected $guarded = ['id'];

    public function r_status()
    {
        return $this->hasOne(Status::class, 'status', 'satusehat_send');
    }

    public function r_managing_organization()
    {
        return $this->hasOne(Organization::class, 'satusehat_id', 'managing_organization');
    }
    public function r_partof_name()
    {
        return $this->hasOne(Location::class, 'satusehat_id', 'partof_id');
    }
}
