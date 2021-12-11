<?php

namespace App\Http\Controllers;
use App\Models\Albums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AlbumsController extends Controller
{
    public function addAlbum(Request $request){
        
        $validator = Validator::make($request->all(),[
            'albumName' => 'required',
            'artistName' => 'required',
            'albumDescription' => 'required|max:255',
            'year' => 'required|integer',
            'genre' => 'required',
            'producers' => 'required|array' 
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json($errors,400);
        }else{
            $album = new Albums;
            $album->albumName = request('albumName');
            $album->artistName = request('artistName');
            $album->albumDescription = request('albumDescription');
            $album->year = request('year');
            $album->genre = request('genre');
            $album->producers = json_encode(request("producers"));
            $album->save();
            return response()->json('Successfully added album',200);
        }

    }
    public function addSongtoAlbum($id){
        
    }
}
