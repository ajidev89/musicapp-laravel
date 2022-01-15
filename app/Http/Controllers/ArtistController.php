<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ArtistController extends Controller
{
    public function addArtist(Request $request){

        $validator = Validator::make($request->all(),[ 
            'artistName' =>'required|unique:artists',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json($errors,422);
        }


        //Save User
        $user = new User;
        $user->email = $request->email;
        $user->role = 'artist';
        $user->password = Hash::make(Str::random(16)); 
        $user->save();

        //Add as an Artist
        $artist = new Artist;
        $artist->artistName = $request->artistName;
        $artist->uuid = $user->id;
        $artist->save();

        return response()->json('Successfully added artist',200);
    }

    public function getallArtist(){
        $allartist = Artist::with('user')->get();
        return response()->json($allartist,200);

    }
}
