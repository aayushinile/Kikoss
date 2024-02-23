<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StopDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_id',
        'stop_name',
        'stop_number',
        'stop_image',
        'stop_audio',
        'created_at',
        'updated_at',
    ];
    protected $table = 'virtual_stops_details';
}