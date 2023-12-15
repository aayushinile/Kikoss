<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourAttribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'tour_id',
        'attribute_type',
        'attribute_name'
    ];
    protected $table = 'tour_attributes';
    protected $key = 'id';
}