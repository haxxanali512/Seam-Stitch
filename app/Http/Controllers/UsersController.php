<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Traits\ApiResponser;

class UsersController extends Controller
{
    use ApiResponser;

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
        $request->validate([
            'email'=>'required|email|exists:users',
            'password'=>'required|string'
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            // Authentication failed...
            return $this->error('Invalid email or password');
        }

        $token = Auth::user()->createToken('seamstitch')->plainTextToken;

        $response = [
            'id' => Auth::id(),
            'user' => Auth::user(),
            'token' => $token,
        ];

        return $this->success('Login successfully', $response);
    }

    public function logout(Request $request){

        Auth::user()->currentAccessToken()->delete();
        return $this->success('Token Deleted');

    }
  
}
