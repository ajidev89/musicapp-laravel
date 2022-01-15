<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlbumProducer extends Model
{
    use HasFactory;

    protected $hidden = [
        'albumID','status','created_at','updated_at','id'
    ];
}
