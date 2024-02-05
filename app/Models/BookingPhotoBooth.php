<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPhotoBooth extends Model
{
    use HasFactory;
    protected $table = 'photo_booth_booking';
    function Users()
    {
        return $this->hasOne('App\Models\User', 'id', 'userid');
    }
    function booth()
    {
        return $this->hasOne(PhotoBooth::class, 'id', 'booth_id');
    }
}