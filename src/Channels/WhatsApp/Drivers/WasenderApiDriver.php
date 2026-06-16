<?php

namespace OmniMsg\Channels\WhatsApp\Drivers;

use OmniMsg\Contracts\MessageDriverInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WasenderApiDriver implements MessageDriverInterface
{
    protected string $baseUrl;
    protected string $token;
    protected array $defaultCredentials;

    public function __construct(array $credentials)
    {
        $this->defaultCredentials = $credentials;
        $this->baseUrl = $credentials['base_url'] ?? 'https://www.wasenderapi.com/';
        $this->token   = $credentials['token'];
    }

    public function send(string $body, string $to, array $options = []): array
    {
        $baseUrl = $options['base_url'] ?? $this->baseUrl;
        $token   = $options['token'] ?? $this->token;
        try {
            $response = Http::timeout(60)
                ->withoutVerifying()
                ->withToken($token)
                ->post($baseUrl . 'api/send-message', [
                    'to'   => $to,
                    'body' => $body,
                ]);

            return [
                'success'     => $response->successful(),
                'status_code' => $response->status(),
                'data'        => $response->json() ?? $response->body(),
                'message'     => $response->successful() 
                    ? 'Message envoyé avec succès' 
                    : 'Erreur lors de l\'envoi',
                'provider'    => 'whapi',
            ];
        } catch (\Exception $e) {
            Log::error('OmniMsg WhapiCloudDriver error: ' . $e->getMessage(), [
                'to'    => $to,
                'error' => $e->getMessage(),
            ]);

            return [
                'success'     => false,
                'status_code' => 500,
                'data'        => null,
                'message'     => 'Exception: ' . $e->getMessage(),
                'provider'    => 'whapi',
            ];
        }
    }

    public function getStatus(string $messageId): array
    {
    }
}
