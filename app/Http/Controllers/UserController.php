<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function Register(Request $request,$role){
        $validator = Validator::make($request->all(),[ 
            'email' => 'required|string|email|max:255|unique:users',
            'password' =>'required|string|min:8',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json($errors,422);
        }

        //Save User
        $user = new User;
        $user->email = $request->email;
        $user->role = strtolower($role);
        $user->password = Hash::make($request->password); 
        $user->save();
        $token = $user->createToken($user->email)->plainTextToken;

        return response()->json(['user'=>$user,'token'=>$token],200);
    }

    public function loginUser(Request $request){

        $validator = Validator::make($request->all(),[ 
            'email' => 'required|string|email|max:255|exists:users',
            'password' =>'required|string|min:8',
        ]); 

        // Check validation failure
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json($errors,422);
        }

        $email = $request->email;
        $password = $request->password;

        //Get user
        $user = User::where('email',$email)->first();
        $login = Hash::check($password, $user->password);
        if ($login) {
            $token = $user->createToken($email)->plainTextToken;
            return response()->json(['user'=>$user,'token'=>$token],200);
        }else{
            return response()->json('These credentials do not match our records',404);
        }
    }
    
    public function checkToken(){
        if(auth('sanctum')->check()){
            return response()->json('Token is vaild',200);
        }else{
            return response()->json('Token has expired',403);
        }
    }
}
