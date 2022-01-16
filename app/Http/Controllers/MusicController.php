<?php

namespace App\Http\Controllers;

use App\Models\FeaturedArtist;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MusicController extends Controller
{
    public function uploadMusic(Request $request) {
        if(!empty($request->file)){
            $validator = Validator::make($request->all(),[
                'file' => 'file|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav,jpeg,png,jpg'
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->getMessages();
                return response()->json($errors,400);
            }else{
                $uploadedFileUrl = Cloudinary::uploadFile($request->file('file')->getRealPath())->getSecurePath();
                return response()->json(["msg" => "Successfully Uploaded", "url" => $uploadedFileUrl ],201);
            }
        }else{
            return response()->json(["error" =>"No file found"],404);
        }
       

    }
    public function postSingleMusic(Request $request){

        $validator = Validator::make($request->all(),[
            'artistID' => 'required|exists:artists,id',
            'featuredArtists' => "array",
            'description' => "required",
            'songTitle' => "required",
            'year' => "required|digits:4",
            'url' => 'required|url',
            "image_url" =>'required|url',
            'status' =>'required|boolean'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json($errors,422);
        }

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

        //featured Artist

        if (count($request->featuredArtists) >= 1) {
            
            foreach ($request->featuredArtists as $key => $featuredArtistName) {
                $featuredArtist = new FeaturedArtist;
                $featuredArtist->artistName = $featuredArtistName;
                $featuredArtist->musicId = $newMusic->id;
                $featuredArtist->save();
            }
        }

        return response()->json('Sucessfully added Music',200);
        
    }
    public function putSingleMusic(Request $request,$id){

        $validator = Validator::make($request->all(),[
            'featuredArtists' => "array",
            'description' => "required",
            'songTitle' => "required",
            'year' => "required|digits:4",
            'url' => 'required|url',
            "image_url" =>'required|url',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json($errors,422);
        }

        $putMusic = Music::where('id',$id)->firstOrFail(); 
        $putMusic->songTitle = $request->songTitle;
        $putMusic->year = $request->year;
        $putMusic->description = $request->description;
        $putMusic->url = $request->url;
        $putMusic->isSingle = true;
        $putMusic->image_url = $request->image_url;
        $putMusic->year = $request->year;
        $putMusic->save();


        return response()->json("Successfully updated music",200);
    }

    public function changeStatusMusic($id){
        $changeMusic = Music::where('id',$id)->first(); 
        if(!empty($changeMusic)){
            $changeMusic->status =($changeMusic->status = 1) ? (0) : (1);
            $changeMusic->save();
            return response()->json("Successfully changed status",201);
        }else{
            return response()->json("Not found",404);
        }
    }



    public function lastestMusic(){
        $lastest = Music::orderBy('created_at', 'desc')->with("artist")->with("featuredArtists")->take(40)->get();
        return response()->json($lastest);
    }
}
