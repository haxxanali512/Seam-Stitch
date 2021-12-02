<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Messages;
use App\Models\Conversation;
use App\Models\User;
use App\Models\Tailor;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class ChatController extends Controller
{
    use ApiResponser;
    public function sendMessage(Request $request){
        $user_id =$request->sender_id;
        $reciever_id = $request->reviever_id;
        $message = $request->message;
        $reciever='';
        $sender='';
        $convo = Conversation::where('sender_id','=',$user_id)->where('reciever_id','=',$reciever_id)->count();
        $convo2 = Conversation::where('sender_id','=',$reciever_id)->where('reciever_id','=',$user_id)->count();
        if($convo !=0){
            $convo = Conversation::where('sender_id','=',$user_id)->where('reciever_id','=',$reciever_id)->first();
            $chat_messages = Messages::create([
                'convo_id' =>$convo->id,
                'content' => $message,
                'sender_id'=>$user_id,
                'reciever_id'=>$reciever_id
            ]);
            $convo->last_message = $message;
            $convo->save();
             $sender_check  = User::where('id',$user_id)->where('role_id','=',1)->count();  //customer honda te if
                 if($sender_check != 0){
                $reciever = Tailor::where('id',$reciever_id)->first();
                $reciever->name=$reciever->first_name." ".$reciever->last_name;
                $reciever->fcm_token=User::where('id', $reciever->user_id)->first()->fcm_token;
                $sender  = User::where('id',$user_id)->first();


            }
            else{
                                      $sender = Tailor::where('id',$reciever_id)->first();

                $sender->name=$sender->first_name." ".$sender->last_name;
                $sender->fcm_token=User::where('id', $sender->user_id)->first()->fcm_token;

                            $reciever  = User::where('id',$user_id)->first();





            }




            $this->send_push_notification($sender->name,$message,[$reciever->fcm_token],$chat_messages->sender_id,$chat_messages->reciever_id);
            return $this->success('Condition 1');

        }
        else if($convo2 != 0){
            $convo2 = Conversation::where('sender_id','=',$reciever_id)->where('reciever_id','=',$user_id)->first();
            $chat_messages = Messages::create([
                'convo_id' =>$convo2->id,
                'content' => $message,
                'sender_id'=>$user_id,
                'reciever_id'=>$reciever_id
            ]);
            $convo2->last_message = $message;
            $convo2->save();
            $tailor = Tailor::where('id',$user_id)->first();
            $sender_check  = User::where('id',$user_id)->where('role_id','=',1)->count();  //cutomer honda te if

                 if($sender_check != 0){
                $reciever = Tailor::where('id',$reciever_id)->first();
                $reciever->name=$reciever->first_name." ".$reciever->last_name;
                $reciever->fcm_token=User::where('id', $reciever->user_id)->first()->fcm_token;
                $sender  = User::where('id',$user_id)->first();


            }
            else{

                $sender = Tailor::where('id',$user_id)->first();
                $sender->name=$sender->first_name." ".$sender->last_name;
                $sender->fcm_token=User::where('id', $sender->user_id)->first()->fcm_token;
                $reciever  = User::where('id',$reciever_id)->first();



            }

            $this->send_push_notification($sender->name,$message,[$reciever->fcm_token],$chat_messages->sender_id,$chat_messages->reciever_id);

            return $this->success('Condition 2');
        }
        else{
            $convo3 = new Conversation();
            $convo3->sender_id = $user_id;
            $convo3->reciever_id =$reciever_id;
            $convo3->last_message = $message;
            $convo3->save();
            $chat_messages = Messages::create([
                'convo_id' =>$convo3->id,
                'content' => $message,
                'sender_id'=>$user_id,
                'reciever_id'=>$reciever_id
            ]);
            $sender_check  = User::where('id',$user_id)->where('role_id','=',1)->count();  //customer honda te if
                 if($sender_check != 0){
                 $reciever = Tailor::where('id',$reciever_id)->first();
                $reciever->name=$reciever->first_name." ".$reciever->last_name;
                $reciever->fcm_token=User::where('id', $reciever->user_id)->first()->fcm_token;
                $sender  = User::where('id',$user_id)->first();
            }
            else{
                $sender = Tailor::where('id',$user_id)->first();
                $sender->name=$sender->first_name." ".$sender->last_name;
                $sender->fcm_token=User::where('id', $sender->user_id)->first()->fcm_token;
                $reciever  = User::where('id',$reciever_id)->first();

            }

            $this->send_push_notification($sender->name,$message,[$reciever->fcm_token],$chat_messages->sender_id,$chat_messages->reciever_id);

            return $this->success('Condition 3');
        }
      //  return $this->success('Message Has been sent');
    }
      public function getMessages(Request $request){


        $message_data = DB::select("SELECT * FROM messages WHERE (sender_id =". $request->sender_id . " AND reciever_id =" . $request->reciever_id .") OR (sender_id =" . $request->reciever_id ." AND reciever_id = ".$request->sender_id .") ORDER BY created_at ASC");
        foreach($message_data as $key){
            $sender_check  = User::where('id',$key->sender_id)->where('role_id','=',1)->count();
            $sender=[];
            $reciever=[];
            if($sender_check != 0){

                $reciever = Tailor::where('id',$key->reciever_id)->first();
                $reciever->name=$reciever->first_name." ".$reciever->last_name;
                $sender  = User::where('id',$key->sender_id)->first();

            }
            else{
                $sender = Tailor::where('id',$key->sender_id)->first();
                $sender->name=$sender->first_name." ".$sender->last_name;
                $reciever = User::where('id',$key->reciever_id)->first();

            }
            $key->sender=$sender;
            $key->reciever=$reciever;

        }


        return $this->success('User Messages', $message_data);
    }
    public function getConvoAuth(Request $request){
        $user_id = $request->user_id;

        $conversations = Conversation::where('sender_id','=', $user_id)->get();
        $covernsation2 = Conversation::where('reciever_id','=', $user_id)->get();
        $message_data = $conversations->merge($covernsation2);
        foreach($message_data as $key){
            $sender_check  = User::where('id',$key->sender_id)->where('role_id','=',1)->count();
            $sender=[];
            $reciever=[];
           if($sender_check != 0){

                $reciever = Tailor::where('id',$key->reciever_id)->first();
                $reciever->name=$reciever->first_name." ".$reciever->last_name;
                $sender  = User::where('id',$key->sender_id)->first();

            }
            else{
                $sender = Tailor::where('id',$key->sender_id)->first();
                $sender->name=$sender->first_name." ".$sender->last_name;
                $reciever = User::where('id',$key->reciever_id)->first();

            }
            $key->sender=$sender;
            $key->reciever=$reciever;

        }
        return $this->success('',$message_data);
    }

    public function send_push_notification($title, $body, $tokens,$sender,$reciever)
    {

       // $fcm = User::select('fcm_token')->all();
        // $title = $request->title;
        // $body = $request->body;
        // $tokens = $fcm->merge($fcm);
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
        );

$notifdata = array(
    'sender' =>$sender,
                 'reciever' => $reciever,

          );
        //This array contains, the token and the notification. The 'to' attribute stores the token.
        $arrayToSend = array(
                             'registration_ids' => $tokens,
                             'notification' => $data,
                             'data' => $notifdata,
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
}
