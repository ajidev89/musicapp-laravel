<?php

namespace App\Http\Controllers;

use App\Models\AlbumProducer;
use App\Models\Albums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AlbumsController extends Controller
{
    public function addAlbum(Request $request){
        
        $validator = Validator::make($request->all(),[
            'albumName' => 'required',
            'artistID' => 'required|exists:artists,id',
            'albumDescription' => 'required|max:255',
            'year' => 'required|integer',
            'genre' => 'required',
            'producers' => 'required|array' 
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json($errors,400);
        }

        $album = new Albums;
        $album->albumName = request('albumName');
        $album->artistID = request('artistID');
        $album->albumDescription = request('albumDescription');
        $album->year = request('year');
        $album->genre = request('genre');
        $album->save();
        
        foreach ($request->producers as $producer) {
            $producer = new AlbumProducer;
            $producer->name = $producer;
            $producer->albumID = $album->id;
            $producer->save();
        }

        return response()->json('Successfully added album',200);
        

    }
    public function getAllAlbums(){
        $orders = Albums::with('artist')->with('producers')->get();
    }


    public function addSongtoAlbum($id){
        
    }
}
