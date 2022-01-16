<?php

namespace App\Http\Controllers;

use App\Models\Music;
use App\Models\Albums;
use Illuminate\Http\Request;
use App\Models\AlbumProducer;
use App\Models\FeaturedArtist;
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
            'image'=>'required|url',
            'status'=>'required|boolean'
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
        $album->status = request("status");
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
        $albums = Albums::with('artist')->with('producers')->with("songs")->get();
        return response()->json($albums,200);   
    }

    public function addSongtoAlbum(Request $request){

        $validator = Validator::make($request->all(),[
            'artistID' => 'required|exists:artists,id',
            'featuredArtists' => "array",
            'description' => "required",
            'songTitle' => "required",
            'url' => 'required|url',
            'tracklist' => 'required',
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

        //featured Artist

        if (count($request->featuredArtists) >= 1) {
            
            foreach ($request->featuredArtists as $key => $featuredArtistName) {
                $featuredArtist = new FeaturedArtist;
                $featuredArtist->artistName = $featuredArtistName;
                $featuredArtist->musicId = $newMusic->id;
                $featuredArtist->save();
            }
        }

        return response()->json("Sucessfully added song to album",200);        
    }

    public function lastestAlbums(){
        $lastest = Albums::orderBy('created_at', 'desc')->with("artist")->with("producers")->with("songs")->take(6)->get();
        return response()->json($lastest);
    }
}
