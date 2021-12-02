<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Measurements;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
use Illuminate\Support\Collection;

class MeasurementsController extends Controller
{
    use ApiResponser;
    public function uploadImages(Request $request){
        $imgData = new Collection();
        $add_pic = Measurements::where('user_id','=',Auth::user()->id)->first();
        if($add_pic){
            if($request->calib){
                $name = 'calib' . "." . $request->calib->extension();
                $request->calib->move(public_path() . '/images', $name);
                $imgData = $imgData->merge($name);
            }
            if($request->front){
                $name = 'front' . "." . $request->front->extension();
                $request->front->move(public_path() . '/images', $name);
                $imgData = $imgData->merge($name);
            }
            if($request->side){
                $name = 'side' . "." . $request->side->extension();
                $request->side->move(public_path() . '/images', $name);
                $imgData = $imgData->merge($name);
            }
                $add_pic->images = json_encode($imgData);
                $add_pic->user_id = Auth::user()->id;
                $add_pic->save();
                $add_pic->images=json_decode($add_pic->images);
                return $this->success('',$add_pic);
        }
        else{
            if($request->calib){
                $name = rand() . "." . $request->calib->extension();
                $request->calib->move(public_path() . '/images', $name);
                $imgData = $imgData->merge($name);
            }
            if($request->front){
                $name = rand() . "." . $request->front->extension();
                $request->front->move(public_path() . '/images', $name);
                $imgData = $imgData->merge($name);
            }
            if($request->side){
                $name = rand() . "." . $request->side->extension();
                $request->side->move(public_path() . '/images', $name);
                $imgData = $imgData->merge($name);
            }
            $add_pic = new Measurements();
                $add_pic->images = json_encode($imgData);
                $add_pic->save();
                $add_pic->images=json_decode($add_pic->images);
                return $this->success('',$add_pic);
        }

    }
    public function addMeasurements(Request $request){

            $add_pic = Measurements::where('user_id','=',Auth::user()->id)->first();
            if($add_pic){
                $add_pic->type = $request->type;
                $add_pic->name = $request->nickname;
                $add_pic->shoulder = $request->shoulder;
                $add_pic->arms = $request->arms;
                $add_pic->pantslength = $request->pantslength;
                $add_pic->shirtlength = $request->shirtlength;
                $add_pic->chest = $request->chest;
                $add_pic->stomach = $request->stomach;
                $add_pic->waist = $request->waist;
                $add_pic->save();
                return $this->success('measurements has been updated',$add_pic);
            }
            else{
                $add_pic = Measurements::create([
                    'shoulder' => $request->shoulder,
                    'arms' => $request->arms,
                    'pantslength' => $request->pantslength,
                    'shirtlength' => $request->shirtlength,
                    'chest' =>  $request->chest,
                    'stomach' => $request->stomach,
                    'waist'=> $request->waist
                ]);
                $add_pic->user_id = Auth::user()->id;
                $add_pic->save();
                return $this->success('added', $add_pic);
            }
    }
}
