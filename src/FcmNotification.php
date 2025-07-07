<?php

namespace AliAmassi\FcmNotification;

use GuzzleHttp\Client;

class FcmNotification
{
    protected string $serverKey;
    protected string $projectId;
    public function __construct(string $serverKey,string $projectId)
    {
        $this->serverKey = $serverKey;
        $this->projectId = $projectId;
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
        $fcmUrl = 'https://fcm.googleapis.com/v1/projects/'.$this->projectId.'/messages:send';
        $response = $client->post($fcmUrl, [
            'headers' => [
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => $payload
        ]);

        return json_decode($response->getBody(), true);
    }
}
