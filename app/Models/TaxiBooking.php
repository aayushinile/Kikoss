<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxiBooking extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_time',
        'booking_id',
        'user_id',
        'pickup_location',
        'pickup_lat_long',
        'drop_location',
        'drop_lat_long',
        'mobile',
        'email',
        'hotel_name',
        'book_taxicol',
        'distance',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $table = 'book_taxis';
    function Username()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }
}