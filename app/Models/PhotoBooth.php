<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoBooth extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'delete_days',
        'users_id',
        'tour_id',
        'title',
        'price',
        'description',
        'cancellation_policy',
        'status',
    ];
    function TourNameBooth()
    {
        return $this->hasOne('App\Models\Tour','id','tour_id');
    }
    
}