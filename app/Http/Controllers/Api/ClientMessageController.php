<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

use App\Models\Client;


class ClientMessageController extends Controller {

    use Helpers;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id) {  
        $client = Client::find($id);
        if (!$client) {
            return $this->response->errorNotFound('Could not find the client');
        }
        
        $messages = $client->messages()->paginate(10);
        return $this->response->paginator($messages, new \App\Transformer\MessageTransformer()); 
    }

}
