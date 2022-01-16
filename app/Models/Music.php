<?php

namespace App\Models;

use App\Models\Artist;
use App\Models\FeaturedArtist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Music extends Model
{
    use HasFactory;


    public function artist()
    {
        return $this->hasOne(Artist::class,'id','artistID');
    }

    public function featuredArtists()
    {
        return $this->hasMany(FeaturedArtist::class,'musicId','id');
    }



}
