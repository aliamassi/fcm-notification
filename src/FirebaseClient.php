<?php

namespace AliAmassi\FcmNotification;

use Google\Auth\OAuth2;
use GuzzleHttp\Client;

class FirebaseClient
{
    protected $credentials;
    protected $projectId;
    protected $http;
    protected $accessToken;

    public function __construct(string $credentialsPath, string $projectId)
    {
        $this->credentials = json_decode(file_get_contents($credentialsPath), true);
        $this->projectId = $projectId;
        $this->http = new Client();
    }

    protected function getAccessToken(): string
    {
        if (!empty($this->accessToken) && $this->accessToken['expires_at'] > time()) {
            return $this->accessToken['access_token'];
        }

        $oauth = new OAuth2([
            'audience'           => 'https://oauth2.googleapis.com/token',
            'issuer'             => $this->credentials['client_email'],
            'signingAlgorithm'   => 'RS256',
            'signingKey'         => $this->credentials['private_key'],
            'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
            'scope'              => 'https://www.googleapis.com/auth/firebase.messaging',
        ]);

        $token = $oauth->fetchAuthToken();
        $this->accessToken = [
            'access_token' => $token['access_token'],
            'expires_at'   => time() + $token['expires_in'] - 60,
        ];

        return $this->accessToken['access_token'];
    }

    /**
     * Send to a single or multiple tokens
     * @param string|array $tokens
     * @param array $notification
     * @param array $data
     */
    public function send($tokens, array $notification = [], array $data = []): array
    {
        $message = ['message' => []];

        if (is_array($tokens)) {
            // Multicast: use tokens field
            $message['message']['tokens'] = $tokens;
        } else {
            $message['message']['token'] = $tokens;
        }

        if ($notification) {
            $message['message']['notification'] = $notification;
        }

        if ($data) {
            $message['message']['data'] = $data;
        }

        $response = $this->http->post(
            "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send",
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->getAccessToken(),
                    'Content-Type'  => 'application/json',
                ],
                'json' => $message,
            ]
        );

        return json_decode((string) $response->getBody(), true);
    }
}
