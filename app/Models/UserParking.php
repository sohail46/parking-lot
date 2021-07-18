<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserParking extends Model
{
    protected $fillable = ['user_id', 'parking_id', 'license_plate_no'];
}
