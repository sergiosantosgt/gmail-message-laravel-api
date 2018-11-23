<?php

namespace App\Transformer;

use App\Models\Message;
use League\Fractal;

class MessageTransformer extends Fractal\TransformerAbstract {

    /**
     * 
     * @param Message $client
     * @return type
     */
    public function transform(Message $message) {
        return [
            'id'    => (int) $message->message_id,
            'api_message_id' => $message->api_message_id,
            'subject' => $message->subject,
            'message' => $message->message,
            'complete_message' => $message->complete_message,
            'client_id' => $message->client_id,
            'link' => [
                [
                    'uri' => url('api/message/'.$message->message_id),
                    'client' => url('api/client/'.$message->client_id)
            
                ]
            ],
        ]; 
    }
    
}
