<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallbackRequest extends Model
{
    use HasFactory;
    function TourName()
    {
        return $this->hasOne('App\Models\Tour','id','tour_id');
    }
}