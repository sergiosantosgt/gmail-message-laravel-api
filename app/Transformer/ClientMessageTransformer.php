<?php

namespace App\Transformer;

use App\Models\Client;
use App\Models\Message;
use League\Fractal;

class ClientMessageTransformer extends Fractal\TransformerAbstract {

    /**
     * 
     * @param Client $client
     * @return type
     */
    public function transform(Client $client) {
        return [
            'id'    => (int) $client->client_id,
            'name' => $client->name,
            'email' => $client->email,
//            'created_at' => $client->created_at,
//            'updated_at' => $client->updated_at,
            'link' => [
                [
                    'uri' => url('api/client/'.$client->client_id)
                ]
            ],
        ]; 
    }
    
}
