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
        // $validator = Validator::make($request->all(),[
        //     'name' => 'required|string',
        //     'email' => 'required|string|unique:users,email',
        //     'password' => 'required|string|confirmed'
        // ]);
        // if($validator->fails()){
        //     return response()->json([
        //         'status' => 400, 
        //         'message'=>'Bad_Request']);
        // }
        // $user = New User();
        // $user->name = $request->name;
        // $user->email = $request->email;
        // $user->password = bcrypt($request->password);
        // $user->save();
        // return response()->json([
        //     'message' => 'User added',
        //     'status' =>200
        // ]);
        $input_values = $request->validate([
            'name'=> 'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string'
        ]);
        $user = User::create([
            'name'=>$input_values['name'],
            'email'=>$input_values['email'],
            'password'=>bcrypt($input_values['password'])
        ]);
        $token = $user->createToken('seamstitch')->plainTextToken;
        return response()->json([
            'User'=>$user,
            'Token'=>$token,
            'message' => 'User added',
            'status' =>200
        ]);
    }


    public function login(Request $request){
        $input_values = $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($input_values['password'],$user->password)){
            return response([
                'message' => 'Wrong Email or Password',

            ], 401);
        }
        $token = $user->createToken('seamstitch')->plainTextToken;
        return response()->json([
            'message' => 200,
            'token'=>$token
        ]);
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'Status_code'=>200,
            'message'=>'Token Deleted'
        ]);
    }
  
}
