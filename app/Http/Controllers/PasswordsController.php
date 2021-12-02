<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Mail\SeamStitchEmail;
use App\Mail\PasswordGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;
use App\Traits\ApiResponser;
use Validator;

class PasswordsController extends Controller
{
    use ApiResponser;
   public function forgotPassword(Request $request)
    {
        $credentials = request()->validate(['email' => 'required|email']);
        return response()->json([Password::sendResetLink($credentials)]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message'=> 'Password reset successfully'
            ]);
        }

        return response([
            'message'=> __($status)
        ], 500);

    }
    public function updatePassword(Request $request)
        {
            $input = $request->validate([
                'old_password'=> 'string',
                'password'=> 'string'
            ]);

            $temp = Auth::user()->password;
            if(Hash::check($request->old_password, $temp)){
                $user = User::find(Auth::id());
                $user->password = Hash::make($request->password);
                $user->save();
                return $this->success('Password Changed', $user);

            }
           else{
            return $this->error('Current password error');
           }


    }
    public function sendForgetPassword(Request $request){
        $code = rand(100000,999999);
        $code_enter = User::where('email', '=', $request->email)->first();
        $details = [
            'code' =>  $code,
        ];
        Mail::to($request->email)->send(new SeamStitchEmail($details));
        $code_enter->email_code = $code;
        $code_enter->save();
        return response()->json(['email has been sent'], 200);
    }
    public function verifyCode(Request $request){
        $verify = User::where('email','=', $request->email)->first();
        if($verify){
            if($verify->email_code == $request->code){
                $verify->email_verified = 1;
                $verify->save();
                return $this->success('Email verified', $verify);
            }
            else{
                return $this->error('Incorrent Code');
            }
        }
        else{
            return $this->error('Email does not exist');
        }

    }
    public function tempPassword(Request $request){
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $temp = implode($pass);
        $verify = User::where('email','=', $request->email)->first();
        $token = $verify->createToken('seamstitch')->plainTextToken;
        $details = [
            'code' =>  $temp,
        ];
        if($verify){
            Mail::to($request->email)->send(new PasswordGenerator($details));
            $verify->email_code = $temp;
            $verify->save();
            return $this->success('Password has been sent', $token);
            }
            else{
                return $this->error('something went wrong');
            }
        }
        public function verifyCodeandPassword(Request $request){
            $verify = User::where('email','=', $request->email)->first();
            if($verify){
                if($verify->email_code == $request->code){
                    $verify->password = bcrypt($request->password);
                    $verify->email_verified = 1;
                    $verify->save();
                    return $this->success('Password changed', $verify);
                }
                else{
                    return $this->error('Incorrent Code');
                }
            }
            else{
                return $this->error('Email does not exist');
            }

        }
    }
