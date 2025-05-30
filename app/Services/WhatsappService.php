<?php

namespace App\Services;

use Twilio\Rest\Client;

class WhatsappService
{
    public static function sendMessage(string $to, string $message)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token');
        $from = config('services.twilio.whatsapp_from');

        $client = new Client($sid, $token);

        return $client->messages->create(
            "whatsapp:$to",
            [
                'from' => $from,
                'body' => $message,
            ]
        );
    }
}
