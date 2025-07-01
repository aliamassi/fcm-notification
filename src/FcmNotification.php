<?php

namespace AliAmassi\FcmNotification;

use GuzzleHttp\Client;

class FcmNotification
{
    protected string $serverKey;
    protected string $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    public function __construct(string $serverKey)
    {
        $this->serverKey = $serverKey;
    }

    public function send($deviceTokens, array $notification = [], array $data = []): array
    {
        $client = new Client();

        $payload = [
            'priority'     => 'high',
            'notification' => $notification,
            'data'         => $data,
        ];

        if (is_array($deviceTokens)) {
            $payload['registration_ids'] = $deviceTokens;
        } else {
            $payload['to'] = $deviceTokens;
        }

        $response = $client->post($this->fcmUrl, [
            'headers' => [
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => $payload
        ]);

        return json_decode($response->getBody(), true);
    }
}
