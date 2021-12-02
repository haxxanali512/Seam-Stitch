<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tailor;
use App\Models\CNIC;
use App\Models\Bank;
use App\Models\ShopAddress;
use Twilio\Rest\Client;
use Illuminate\Support\Collection;
use App\Models\Location;
use App\Models\Measurements;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;

class UsersController extends Controller
{
    use ApiResponser;

    public function registeration(Request $request)
    {
        $input_values = $request->validate([
            'name' => 'string',
            'email' => 'string|unique:users,email',
            'password' => 'string',
        ]);
        $user = User::create([
            'name' => $input_values['name'],
            'email' => $input_values['email'],
            'password' => bcrypt($input_values['password']),
            'fcm_token' => $request->fcm_token,
        ]);
        $user->role_id = 1;
        $user->save();
        $token = $user->createToken('seamstitch')->plainTextToken;
        $response = [
            'User' => $user,
            'Token' => $token,
        ];
        return $this->success('User has been registered', $response);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|exists:users',
            'password' => 'string'
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = User::find(Auth::user()->id);
            $user->fcm_token = $request->fcm_token;
            $user->save();
            if (auth()->user()->role_id == 1) {
                $token = Auth::user()->createToken('seamstitch')->plainTextToken;
                $location = Location::where('user_id', '=', Auth::user()->id)->first();
                $measurements = Measurements::where('user_id','=',Auth::user()->id)->first();
                $response = [
                    'user' => Auth::user(),
                    'token' => $token,
                    'Location' => $location,
                    'measurements' => $measurements
                ];
                return $this->success('Login successfully', $response);
            } else {
                return response()->json([
                    'code' => 405,
                    'message' => 'Unauthorized Attempt'
                ]);
            }
            // Authentication failed...
        }
        return $this->error('Invalid email or password');
    }

    public function logout(Request $request)
    {

        Auth::user()->currentAccessToken()->delete();
        return $this->success('Token Deleted');
    }
    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'dob' => 'date_format:Y-m-d|before:today',
            'gender' => 'in:male,female',
            'image' => 'mimes:jpg,png,jped|max:5048'
        ]);
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->phone_number = $request->phone_number;
        $user->save();
        if ($request->image) {
            $fName = explode(" ", $request->name);
            $newImage = time() . $fName[0] . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $newImage);
            $user->image_path = $newImage;
            $user->save();
        }
        return $this->success('Information updated', $user);
    }
    public function getUserData($id)
    {
        $user = User::find($id);
        return $this->success('User Information', $user);
    }
    public function getUserLocation($id)
    {
        $user = User::find($id, 'location_id');
        return $this->success('User Information', $user);
    }
    public function getAllUsers()
    {
        $user = User::where('role_id','=', 1)->get();
        return $this->success('Users', $user);
    }

    public function registerTailor(Request $request)
    {
        $tailor_data = $request->validate([
            'shop_name' => 'string',
            'email' => 'string|unique:users,email',
            'password' => 'string',
            'phone_number' => 'string',
        ]);

        /* Get credentials from .env */
        $token = "b41d57d4ba767e8c46f492962e4b30eb";
        $twilio_sid = "ACe8b9e0dd03ab3bde301a4bbe4f485ea9";
        $twilio_verify_sid = "VAa8fe5f02d78b2613e660f806210becb4";
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($tailor_data['phone_number'], "sms");
        $tailor = User::create([
            'shop_name' => $tailor_data['shop_name'],
            'email' => $tailor_data['email'],
            'password' => bcrypt($tailor_data['password']),
            'phone_number' => $tailor_data['phone_number'],
            'fcm_token' => $request->fcm_token
        ]);
        $tailor->role_id = 2;
        $tailor->fcm_token = $request->fcm_token;
        $tailor->save();
        $token = $tailor->createToken('seamstitch')->plainTextToken;
        $response = [
            'user' => $tailor,
            'token' => $token
        ];
        return $this->success('Tailor Registered: ', $response);
    }
    public function logintailor(Request $request)
    {
        $request->validate([
            'email' => 'email|exists:users',
            'password' => 'string',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if (auth()->user()->role_id == 2) {
                $user = User::find(Auth::user()->id);
                $user->fcm_token = $request->fcm_token;
                $user->save();
                $banks = new Collection();
                $cnics = new Collection();
                $token = Auth::user()->createToken('seamstitch')->plainTextToken;

                $tailor = Tailor::where('user_id', '=', Auth::user()->id)->count();
                if ($tailor > 0) {
                    $tailor = Tailor::where('user_id', '=', Auth::user()->id)->first();
                    $bank = Bank::where('tailor_id', '=', $tailor->id)->count();
                    $cnic = CNIC::where('tailor_id', '=', $tailor->id)->count();
                    $shop =  ShopAddress::where('tailor_id', '=', $tailor->id)->count();
                    if ($bank > 0) {
                        $banks = Bank::where('tailor_id', '=', $tailor->id)->get();
                    }
                    if ($cnic > 0) {
                        $cnics = CNIC::where('tailor_id', '=', $tailor->id)->first();
                    }
                    if ($shop > 0) {
                        $shop =  ShopAddress::where('tailor_id', '=', $tailor->id)->first();
                    }
                    $response = [
                        'user' => Auth::user(),
                        'token' => $token,
                        'profile' => $tailor,
                        'bank' => $banks,
                        'cnic' => $cnics,
                        'shop' => $shop
                    ];
                    return $this->success('Login successfully', $response);
                }
                $response = [
                    'user' => Auth::user(),
                    'token' => $token,
                ];


                return $this->success('Login successfully', $response);
            } else {
                return response()->json([
                    'code' => 405,
                    'message' => 'Unauthorized Attempt'
                ]);
            }
        }
        return $this->error('Invalid email or password');
    }
    protected function verify(Request $request)
    {
        $data = $request->validate([
            'verification_code' => 'numeric',
            'phone_number' =>  'string'
        ]);
        /* Get credentials from .env */
        $token = "b41d57d4ba767e8c46f492962e4b30eb";
        $twilio_sid = "ACe8b9e0dd03ab3bde301a4bbe4f485ea9";
        $twilio_verify_sid = "VAa8fe5f02d78b2613e660f806210becb4";
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create($data['verification_code'], array('to' => $data['phone_number']));
        if ($verification->valid) {
            $user = tap(User::where('phone_number', $data['phone_number']))->update(['isVerified' => true]);
            /* Authenticate user */
            Auth::login($user->first());
            return $this->success('Phone number verified', $request->phone_number);
        }
        return $this->error('Invalid OTP');
    }
    public function viewCustomer(Request $request)
    {
        $customer = User::find($request->user_id);
        return $this->success('Customer Information', $customer);
    }
    public function customer($id)
    {
        $user = User::where('id', $id)->first();
        $location = Location::where('user_id', '=', $user->id)->first();
        $user->location = $location;
        $user->measurements = Measurements::where('user_id','=', $id)->first();
        return $this->success("user data", $user);
    }
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'email|exists:users',
            'password' => 'string'
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = User::find(Auth::user()->id);
            $user->fcm_token = $request->fcm_token;
            $user->save();
            if (auth()->user()->role_id == 3) {
                $token = Auth::user()->createToken('seamstitch')->plainTextToken;
                $location = Location::where('user_id', '=', Auth::user()->id)->first();
                $response = [
                    'user' => Auth::user(),
                    'token' => $token,
                    'Location' => $location
                ];
                return $this->success('Login successfully', $response);
            } else {
                return response()->json([
                    'code' => 405,
                    'message' => 'Unauthorized Attempt'
                ]);
            }
            // Authentication failed...
        }
        return $this->error('Invalid email or password');
    }
}
//customer role_id ==1
//vendor role_id == 2
//admin role_id == 3
