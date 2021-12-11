<?php

namespace App\Http\Controllers;
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
    public function postMusic(Request $request){
        $validator = Validator::make($request->all(),[
            'artist'=>"required|max:64",
            'featuredArtists' => "array",
            'description' => "required",
            'songTitle' => "required",
            'year' => "required|digits:4",
            'url' => 'required|url',
            "image_url" =>'required_if:isSingle,true|url',
            'isSingle' => "required|boolean",
            'albumId' =>"required_if:isSingle,false|integer",
            'status' =>'required|boolean'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json($errors,400);
        }else{
            $newMusic = new Music;
            $newMusic->songTitle = $request->songTitle;
            $newMusic->year = $request->year;
            $newMusic->description = $request->description;
            $newMusic->url = $request->url;
            $newMusic->isSingle = $request->isSingle;
            $newMusic->albumId = $request->albumId;
            $newMusic->artist = $request->artist;
            $newMusic->featuredArtists = json_encode($request->featuredArtists);
            $newMusic->year = $request->year;
            $newMusic->image_url = $request->image_url;
            $newMusic->status = $request->status;
            $newMusic->save();
            return response()->json($newMusic);
        }
    }
    public function putMusic(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'artist'=>"required|max:64",
            'featuredArtists' => "array",
            'description' => "required",
            'songTitle' => "required",
            'year' => "required|digits:4",
            'url' => 'required|url',
            'isSingle' => "required|boolean",
            "image_url" =>'required_if:isSingle,true|url',
            'albumId' =>"required_if:isSingle,false|integer",
            'status' =>'required|boolern'
        ]);
        $putMusic = Music::where('id',$id)->first();  
        $putMusic->songTitle = $request->songTitle;
        $putMusic->year = $request->year;
        $putMusic->description = $request->description;
        $putMusic->url = $request->url;
        $putMusic->isSingle = $request->isSingle;
        $putMusic->albumId = $request->albumId;
        $putMusic->artist = $request->artist;
        $putMusic->image_url = $request->image_url;
        $putMusic->featuredArtists = json_encode($request->featuredArtists);
        $putMusic->year = $request->year;
        $putMusic->status = $request->status;
        $putMusic->save();
        return response()->json($putMusic);
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
        $lastest = Music::orderBy('created_at', 'desc')->take(6)->get();
        return response()->json($lastest);
    }
}
