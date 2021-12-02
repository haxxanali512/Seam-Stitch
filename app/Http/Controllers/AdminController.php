<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Complaint;
use App\Models\Miscellaneous;
use App\Models\User;
use App\Models\Bank;
use App\Models\CNIC;
use App\Models\Order;
use App\Models\Tailor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
class AdminController extends Controller
{
    use ApiResponser;
    public function submitComplaint(Request $request){
            $images = new Collection();
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $file) {
                $name = rand() . "." . $file->extension();
                $file->move(public_path() . '/images', $name);
                $images = $images->merge($name);
            }

        $create_complaint = Complaint::create([
            'tailor_id' => $request->tailor_id,
            'complaint_name' => $request->name,
            'description'=> $request->description,
        ]);
       $create_complaint->user_id = Auth::user()->id;
       $create_complaint->photo = json_encode($images);
       $create_complaint->save();
       $create_complaint->photo = json_decode($create_complaint->photo);
       return $this->success('Complaint has been registered',$create_complaint);
    }
}
    public function viewComplaints(){
        $complaints = Complaint::all();
        foreach($complaints as $key){
            $users = User::where('id',$key->user_id)->first();
            $tailor = Tailor::where('id',$key->tailor_id)->first();
            $key->user = $users;
            $key->tailor = $tailor;
        }
        if($complaints->count() > 0){
            return $this->success('Complaints', $complaints);
        }
        else{
            return $this->error('No complaints found');
        }
    }
    public function deletecustomer($id){
        $delete = User::find($id)->delete();
        if($delete){
            return $this->success('User has been deleted,');
        }
        else{
            return $this->error('Unkown error');
        }
    }
    public function isAllowed(Request $request){

        $tailor=Tailor::find($request->tailor_id);
        $tailor->is_allowed = $request->status;
        $tailor->save();
        $tailors = Tailor::where('is_allowed','=',1)->get();
        return $this->success('tailor is allowed ', $tailors);
    }
    public function viewTailor(Request $request){
        $tailor = Tailor::where('id', $request->tailor_id)->first();
        $tailor->user_information = User::where('id',$tailor->user_id)->first();
        $cnic = CNIC::where('tailor_id','=', $request->tailor_id)->first();
        $bank = Bank::where('tailor_id','=', $request->tailor_id)->first();
        if($cnic){
            $tailor->CNIC = $cnic;
        }
        if($bank){
            $tailor->Bank = $bank;
        }

        return $this->success('Tailor', $tailor);
    }
    public function requestedtailors(){
        $tailors = Tailor::where('is_allowed' ,'=', 1)->count();
        if($tailors > 0 ){
            $tailors = Tailor::where('is_allowed' ,'=', 1)->get();
            foreach($tailors as $key){
                $key->shop_name = User::where('id', $key->user_id)->select('shop_name')->first();
            }
            return $this->success('',$tailors);
        }
        else{
            return $this->error('no tailors were found');
        }
    }


    public function send_push_notification(Request $request)
    {

        // echo $title;
        // print_r($tokens);
        // die;
        $users = User::select('fcm_token')->get();
        $title = $request->title;
        $body = $request->body;
        $tokens  = [];
        foreach($users as $token){
            if($token->fcm_token !=0 || $token->fcm_token ){
                $tokens[] =$token->fcm_token;

            }
        }
      //  return $this->success('',$tokens);

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );



        //Custom data to be sent with the push
        $data = array
        (
            'message'      => 'here is a message. message',
            'title'        => $title,
            'body'         => $body,
            'smallIcon'    => 'small_icon',
            'some data'    => 'Some Data',
            'Another Data' => 'Another Data'
        );

        //This array contains, the token and the notification. The 'to' attribute stores the token.
        $arrayToSend = array(
                             'registration_ids' => $tokens,
                             'notification' => $data,
                             'priority'=>'high'
                              );



        //Generating JSON encoded string form the above array.
        $json = json_encode($arrayToSend);
        //Setup headers:
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        $headers[] = 'Authorization: key= AAAAxY_Prro:APA91bGqRiXYX-CO9TO8nfq9lxO30Cjhh3M-AJTFNjwgqsBtp_r4e42If3R-Br-J84OddLM0Cuq4ZhB32LzK7hAkZTamDYCAw8Q7vq1B5oA56AgRXZTibv2XlhheIWFVm8itAcJmZXk1';


        //Setup curl, add headers and post parameters.

        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

        //Send the request
        $response = curl_exec($ch);

        //Close request
        curl_close($ch);
        return $response;

        // echo $response;

    }



    public function addImages(Request $request){
        $check = Miscellaneous::where('id',1)->first();
        if(!$check){
            if ($request->hasfile('images')) {
                foreach ($request->file('images') as $file) {
                    $name = rand() . "." . $file->extension();
                    $file->move(public_path() . '/images', $name);
                    $imgData[] = $name;
                }
                $upload_images = Miscellaneous::create([
                    'carasol_images'=>json_encode($imgData),
                ]);
                return $this->success('Images has been uploaded',json_decode($upload_images->carasol_images));
        }
        }
        else{
            if ($request->hasfile('images')) {
                foreach ($request->file('images') as $file) {
                    $name = rand() . "." . $file->extension();
                    $file->move(public_path() . '/images', $name);
                    $imgData[] = $name;
                }
                $upload_images = Miscellaneous::find(1);
                $upload_images->carasol_images = $imgData;
                $upload_images->save();
        }
        return $this->success('Images has been uploaded');

    }
    }
    public function getImages(){
        $uploaded_images = Miscellaneous::find(1);
        $pics = json_decode($uploaded_images->carasol_images);
        return $this->success('images', $pics);
    }
    public function deleteComplaint($id){
        $complaints = Complaint::find($id)->delete();
        if($complaints){
            return $this->success('complaint has been deleted');
        }
        else{
            return $this->error('something went wrong');
        }
    }
    public function adminDashboard(){
        $total = Tailor::all();
        $total_tailors = count($total);
        $customers = User::where('role_id','=',1)->get();
        $customers_total = count($customers);
        $complaints = Complaint::all();
        $total_complaints = count($complaints);
        $order_revenue = Order::sum('final_price');
        $admin_revenue = ($order_revenue/100) * 2;

        $response = [
            'tailors' => $total_tailors,
            'customers' => $customers_total,
            'complaints' => $total_complaints,
            'revenue' => $admin_revenue
        ];
        return $this->success('',$response);
    }

}
