<?php

namespace App\Http\Controllers;

use App\Models\AlbumProducer;
use App\Models\Albums;
use App\Models\Music;
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
            'producers' => 'required|array', 
            'image'=>'required|url'
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
        $album->image_url = request("image");
        $album->save();
        
        foreach ($request->producers as $producerName) {
            $producer = new AlbumProducer;
            $producer->name = $producerName;
            $producer->albumID = $album->id;
            $producer->save();
        }
        return response()->json('Successfully added album',200);

    }
    public function getAllAlbums(){
        $albums = Albums::with('artist')->with('producers')->get();
        return response()->json($albums,200);   
    }

    public function addSongtoAlbum(Request $request){

        $validator = Validator::make($request->all(),[
            'artistID' => 'required|exists:artists,id',
            'featuredArtists' => "array",
            'description' => "required",
            'songTitle' => "required",
            'url' => 'required|url',
            "albumID" => 'required|exists:albums,id',
            'status' =>'required|boolean'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json($errors,422);
        }

        $album = Albums::where('id',$request->albumID)->first();

        //New MUsic
        $newMusic = new Music;
        $newMusic->artistID = $request->artistID;
        $newMusic->year = $album->year;
        $newMusic->albumId = $album->id;
        $newMusic->url = $request->url;
        $newMusic->image_url = $album->image_url;
        $newMusic->songTitle = $request->songTitle;
        $newMusic->status = $request->status;
        $newMusic->description = $request->description;
        $newMusic->save();


              //New Music
              $newMusic = new Music;
              $newMusic->songTitle = $request->songTitle;
              $newMusic->year = $request->year;
              $newMusic->description = $request->description;
              $newMusic->url = $request->url;
              $newMusic->isSingle = true;
              $newMusic->artistID = $request->artistID;
              $newMusic->year = $request->year;
              $newMusic->image_url = $request->image_url;
              $newMusic->status = $request->status;
              $newMusic->save();

        
    }
}
