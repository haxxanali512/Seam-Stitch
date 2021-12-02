<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\ShopAddress;
use App\Models\Tailor;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;



class LocationController extends Controller
{use ApiResponser;



    public function store(Request $request)
    {
        $location = $request->validate([
            'mobile_number' => 'string',
            'province' => 'string',
            'city'=>'string',
            'area'=>'string',
            'address'=>'string'
        ]);
        $user_id = Auth::user()->id;
        $data = Location::find($user_id);
        if($data){
            $data = Location::find($user_id);
        }else{
            $data = new Location();
        }

        $data->mobile_number = $request->mobile_number;
        $data->user_id = Auth::user()->id;
        $data->province = $request->province;
        $data->city = $request->city;
        $data->area = $request->area;
        $data->address = $request->address;
        $data->save();

        return $this->success('Location information has been Added', $data);
    }


    public function show()
    {
        $id = Auth::user()->id;
        $location = DB::table('location')->where('user_id', $id)->get();
        return $this->success('Location information for given id', $location);
    }

    // public function update(Request $request)
    // {
    //     $location = $request->validate([
    //         'mobile_number' => 'string',
    //         'province' => 'string',
    //         'city'=>'string',
    //         'area'=>'string',
    //         'address'=>'string',

    //     ]);


    //     $data->mobile_number = $request->mobile_number;

    //     $data->province = $request->province;
    //     $data->city = $request->city;
    //     $data->area = $request->area;
    //     $data->address = $request->address;
    //    $data->save();

    //     return $this->success('Location information has been Updated', $data);
    // }

   public function shopaddress(Request $request){
        $data = $request->validate([
            'address' => 'string',
            'country' => 'string',
            'state' => 'string',
            'area' => 'string'
        ]);
        $user_id = Auth::user()->id;
        $tailor = Tailor::where('user_id', '=' , $user_id)->first();
        $data = ShopAddress::where('tailor_id','=',$tailor->id)->first();
        if($data){
            $id = $data->id;
            $data = ShopAddress::find($id);
            $data->address = $request->address;
            $data->country_region = $request->country;
            $data->state = $request->state;
            $data->area = $request->area;
            $data->tailor_id = $tailor->id;
            $data->save();
            return $this->success("Shop address has been updated", $data);
        }
        else{
            $data = new ShopAddress();
            $data->address = $request->address;
            $data->country_region = $request->country;
            $data->state = $request->state;
            $data->area = $request->area;
            $data->tailor_id = $tailor->id;
            $data->save();
            $tailor_status = Tailor::find($tailor->id);
            $tailor_status->is_allowed = 1;
            $tailor_status->save();
        }


        return $this->success("Shop address has been Added", $data);
   }
}
