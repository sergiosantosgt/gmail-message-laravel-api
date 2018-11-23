<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

use App\Models\Client;
use App\Transformer\ClientTransformer;

class ClientController extends Controller
{   
    use Helpers;

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index() {
        //$client = Client::all();
        $client = Client::paginate(10);
        if ($client->count()) {
            //return response()->json(['data' => $client]); // Use this by default
            //return $this->response->array($client->toArray()); // Use this if you using Dingo Api Routing Helpers
            //return $this->response->collection($client, new ClientTransformer()); // Use this if you using Fractal <=> Create a resource collection transformer
            return $this->response->paginator($client, new ClientTransformer()); // Use this if you using Fractal Responding With Paginated Items 
        } 
        else {
            //return response()->json(['client' => 'Could not find the client', 'status_code'=> '404'], 404); // Use this by default
            //return $this->response->errorNotFound();
            return $this->response->errorNotFound('Could not find the client'); // Use this if you you using Dingo Api Routing Helpers
        }
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Gmail $arrMessage
     * @return \Illuminate\Http\Gmail $clientId
     */

    public function store($arrMessage) {
        if(empty($arrMessage)) return false;

        
        $senderEmail     = $arrMessage["senderEmail"];
        $senderName      = $arrMessage["senderName"];

        $input['created_at'] =  \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $input['name']       = $senderName;
        $input['email']      = $senderEmail;

        $client = json_decode(Client::where("email", '=', $senderEmail)->first(), TRUE);
        $clientId = 0;

        if(empty($client)) {
            if($senderEmail) {
                $client = Client::create($input);
                if ($client) {
                    $clientId = $client["client_id"];
                    $store = true;
                }
            }
        } else {
            $clientId = $client["client_id"];
            $store = false;
        }

        $arrRet = array(
            "client_id" => $clientId,
            "store"     => $store
        );

        return $arrRet;
    }


}
