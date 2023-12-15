<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourBooking extends Model
{
    use HasFactory;
    function Users()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }
    
    function Tour()
    {
        return $this->hasOne('App\Models\Tour','id','tour_id');
    }
}