<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;
    protected $table = 'payment_details';

    function Users()
    {
        return $this->hasOne('App\Models\TourBooking', 'transaction_id', 'transaction_id');
    }

    function Tours()
    {
        return $this->hasOne('App\Models\Tour', 'transaction_id', 'transaction_id');
    }
}