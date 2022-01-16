<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedArtist extends Model
{
    use HasFactory;

    protected $hidden = [
        'musicId','id'
    ];
}
