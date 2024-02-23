<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoBoothMedia extends Model
{
    use HasFactory;
    protected $fillable = [
        'booth_id',
        'media_type',
        'media',
        'status'
    ];
    protected $table = 'photo_booth_medias';
}