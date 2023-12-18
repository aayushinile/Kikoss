<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoBooth extends Model
{
    use HasFactory;
    function TourNameBooth()
    {
        return $this->hasOne('App\Models\Tour','id','tour_id');
    }
}