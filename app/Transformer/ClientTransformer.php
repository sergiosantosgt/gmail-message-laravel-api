<?php

namespace App\Transformer;

use App\Models\Client;
use League\Fractal;

class ClientTransformer extends Fractal\TransformerAbstract {

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
            // 'link' => [
            //     [
            //         'uri' => url('api/client/'.$client->client_id)
            //     ]
            // ],
        ]; 
    }
    
}
