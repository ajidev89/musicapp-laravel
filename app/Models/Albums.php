<?php

namespace App\Models;

use App\Models\AlbumProducer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Albums extends Model
{
    use HasFactory;

    public function producers()
    {
        return $this->hasMany(AlbumProducer::class,'albumID','id');
    }

    public function artist()
    {
        return $this->hasOne(Artist::class,'id','artistID');
    }

    // protected $hidden = [
    //     'userId',
    // ];
}
