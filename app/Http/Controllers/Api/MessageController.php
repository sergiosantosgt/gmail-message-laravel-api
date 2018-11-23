<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

use App\Models\Message;
use App\Transformer\MessageTransformer;

class MessageController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index() {
        //$message = Message::all();
        $message = Message::paginate(10);
        if ($message->count()) {
            //return response()->json(['data' => $message]); // Use this by default
            //return $this->response->array($message->toArray()); // Use this if you using Dingo Api Routing Helpers
            //return $this->response->collection($message, new MessageTransformer()); // Use this if you using Fractal <=> Create a resource collection transformer
            return $this->response->paginator($message, new MessageTransformer()); // Use this if you using Fractal Responding With Paginated Items 
        } 
        else {
            //return response()->json(['message' => 'Could not find the message', 'status_code'=> '404'], 404); // Use this by default
            //return $this->response->errorNotFound();
            return $this->response->errorNotFound('Could not find the message'); // Use this if you you using Dingo Api Routing Helpers
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Gmail $arrMessage
     * @param  \Illuminate\Http\Gmail $clientId
     * @return \Illuminate\Http\Response
     */
    public function store($arrMessage, $clientId) {

        $textMessage     = "";
        $completeMessage = "";
        
        if(isset($arrMessage["message"]["payload"]["parts"][0]["body"]["data"])) {
            $textMessage = MessageController::base64url_decode($arrMessage["message"]["payload"]["parts"][0]["body"]["data"]);
        }

        if(isset($arrMessage["message"]["payload"]["parts"][1]["body"]["data"])) {
            $completeMessage = MessageController::base64url_decode($arrMessage["message"]["payload"]["parts"][1]["body"]["data"]);        
        } 
        

        $input['created_at']       =  \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $input['api_message_id']   = $arrMessage["message"]["id"];
        $input['subject']          = $arrMessage["senderSubject"];
        $input['message']          = $textMessage;
        $input['complete_message'] = $completeMessage;
        $input['client_id']        = $clientId;


        $message = json_decode(Message::where("api_message_id", '=', $arrMessage["message"]["id"])->first(), TRUE);
        $messageId = 0;

        if(empty($message)) {
            $message = Message::create($input);
            if ($message) {
                $messageId = $message["message_id"];
                $store = true;
            }
        } else {
            $messageId = $message["message_id"];
            $store = false;
        }

        $arrRet = array(
            "message_id" => $messageId,
            "store"      => $store
        );

        return $arrRet;
    }

    public static function base64url_decode($data) {
        return base64_decode(str_replace(array('-', '_'), array('+', '/'), $data));
    }
}
