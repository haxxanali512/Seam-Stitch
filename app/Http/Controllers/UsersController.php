<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

class UsersController extends Controller
{

    public function registeration(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 400, 
                'message'=>'Bad_Request']);
        }
        $user = New User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json([
            'message' => 'User added',
            'status' =>200
        ]);
    }


    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json([
                'status_code' => 400, 
                'message'=>'Bad_Request']);
        }
        $credentisals = request(['email','password']);
        if(!Auth::attempt($credentisals)){
            return response()->json([
                'message'=>'Unauthorized user',
                'Status Code' => 500
            ]);
        }
 
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('seamstitch')->plainTextToken;
        return response()->json([
            'message' => 200,
            'token'=>$token
        ]);
    }
    public function logout(Request $request){
        $request->$user->currentAccessToken()->delete();
        return response()->json([
            'Status_code'=>200,
            'message'=>'Token Deleted'
        ]);
    }
  
}
