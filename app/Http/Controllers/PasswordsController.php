<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Validator;

class PasswordsController extends Controller
{
    public function forgot(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json([
                'status_code' => 400, 
                'message'=>'Bad_Request']);
        }

        Password::sendResetLink($credentials);
        return response()->json(["msg" => 'Reset password link sent on your email id.']);
    }
}
