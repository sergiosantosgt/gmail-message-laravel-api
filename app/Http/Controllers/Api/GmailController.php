<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\MessageController;

include_once("../app/Integrations/gmail-client.php");

class GmailController extends Controller {

    use Helpers;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index() {

        $user         = "me";
        $msgLimit     = 100;
        $client       = getClient();
        $service      = getService($client);
        $listMessages = listMessages($service, $user, $msgLimit);

        $arrMessage = array();
        
        $objClientController  = new ClientController();
        $objMessageController = new MessageController();

        $clientStore  = 0;
        $messageStore = 0;

        foreach($listMessages as $message) {
            // getting gmail message by id 
            $arrMessage = getMessage($service, $user, $message["id"]);
            
            // store client
            $client   = $objClientController->store($arrMessage);
            $clientId = $client["client_id"];
            $store    = $client["store"];

            if($store) $clientStore++;
            
            //store messages
            if ($clientId) {
                $message = $objMessageController->store($arrMessage, $clientId);
                $store   = $message["store"];
                if($store) $messageStore++; 
            }
        }

        unset($objClientController, $objMessageController);

        if($clientStore || $messageStore) {
            $msg = $clientStore .  " customers and " . $messageStore . " messages were added!";
            return response($msg, 201);
        } else {
            $msg = "No clients and no messages added!";
            return response($msg, 400);
        }
    }
    
}