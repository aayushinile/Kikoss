<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourBooking extends Model
{
    use HasFactory;
    function Users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    function Payments()
    {
        return $this->hasOne('App\Models\PaymentDetail', 'booking_id', 'tour_id');
    }

    function Tour()
    {
        return $this->hasOne('App\Models\Tour', 'id', 'tour_id');
    }
    
    function VirtualTour()
    {
        return $this->hasOne('App\Models\VirtualTour', 'id', 'tour_id');
    }
    
    function booth()
    {
        return $this->hasOne(PhotoBooth::class, 'id', 'tour_id');
    }
    

    function images()
    {
        return $this->belongsTo('App\Models\TourAttribute','tour_id', 'id' );
    }
}