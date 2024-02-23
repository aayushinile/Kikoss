<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualTour extends Model
{
    use HasFactory;


    function stop_details()
    {
        return $this->hasMany('App\Models\StopDetails','parent_id', 'id' );
    }
}
