<?php

namespace OmniMsg\Channels\MobileMoney\Driver;

use OmniMsg\Contracts\MobileMoneyDriverInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PawaPayDriver implements MobileMoneyDriverInterface
{
    protected string $baseUrl;
    protected string $token;
    protected array $defaultCredentials;
    private string $payloadType = "MMO";

    public function __construct(array $credentials)
    {
        $this->defaultCredentials = $credentials;
        $this->baseUrl = $credentials['base_url'] ?? 'https://api.sandbox.pawapay.io/v2';
        $this->token   = $credentials['token']; // refere to pawapay api key 
    }

    public function initDeposit(array $options): array
    {
        // 1. List of required fields for the transaction
        $requiredFields = ['transactionid', 'amount', 'currency', 'phone', 'provider'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (!isset($options[$field]) || empty($options[$field])) {
                $missingFields[] = $field;
            }
        }

        // If required fields are missing, stop and return an error
        if (!empty($missingFields)) {
            return [
                'success'     => false,
                'status_code' => 400, // Bad Request
                'data'        => null,
                'message'     => 'Missing or invalid data: ' . implode(', ', $missingFields),
                'provider'    => 'pawapay',
            ];
        }
        try {
            $endpoint = $this->baseUrl . '/deposits';
            $endpoint = str_replace('//', '/', $endpoint);

            $payload = [
                "depositId" => $options['transactionid'],
                "amount" => $options['amount'],
                "currency" => $options['currency'],
                "payer" => [
                    "type" => $this->payloadType,
                    "accountDetails" => [
                        "phoneNumber" => $options['phone'],
                        "provider" => $options['provider'] // 'AIRTEL_COD'
                    ]
                ],
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json'
            ])
            ->post($endpoint, $payload);

            return [
                'success'     => $response->successful(),
                'status_code' => $response->status(),
                'data'        => $response->json() ?? $response->body(),
                'message'     => $response->successful() 
                    ? 'Deposit initiatied successfully' 
                    : 'Error initiating deposit',
                'provider'    => 'pawapay',
            ];
        } catch (\Exception $e) {
            Log::error('OmniMsg PawaPayDriver error: ' . $e->getMessage(), [
                'data'    => $options,
                'error' => $e->getMessage(),
            ]);

            return [
                'success'     => false,
                'status_code' => 500,
                'data'        => null,
                'message'     => 'Exception: ' . $e->getMessage(),
                'provider'    => 'pawapay',
            ];
        }
    }

    public function initWithdraw(array $options): array
    {
        // 1. List of required fields for the transaction
        $requiredFields = ['transactionid', 'amount', 'currency', 'phone', 'provider'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (!isset($options[$field]) || empty($options[$field])) {
                $missingFields[] = $field;
            }
        }

        // If required fields are missing, stop and return an error
        if (!empty($missingFields)) {
            return [
                'success'     => false,
                'status_code' => 400, // Bad Request
                'data'        => null,
                'message'     => 'Missing or invalid data: ' . implode(', ', $missingFields),
                'provider'    => 'pawapay',
            ];
        }
        try {
            $endpoint = $this->baseUrl . '/payouts';
            $endpoint = str_replace('//', '/', $endpoint);

            $payload = [
                "payoutId" => $options['transactionid'],
                "amount" => $options['amount'],
                "currency" => $options['currency'],
                "recipient" => [
                    "type" => $this->payloadType,
                    "accountDetails" => [
                        "phoneNumber" => $options['phone'],
                        "provider" => $options['provider'] // 'AIRTEL_COD'
                    ]
                ],
            ];
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json'
            ])
            ->post($endpoint, $payload);

            return [
                'success'     => $response->successful(),
                'status_code' => $response->status(),
                'data'        => $response->json() ?? $response->body(),
                'message'     => $response->successful() 
                    ? 'Withdraw initiatied successfully' 
                    : 'Error initiating withdraw',
                'provider'    => 'pawapay',
            ];
        } catch (\Exception $e) {
            Log::error('OmniMsg PawaPayDriver error: ' . $e->getMessage(), [
                'data'    => $options,
                'error' => $e->getMessage(),
            ]);

            return [
                'success'     => false,
                'status_code' => 500,
                'data'        => null,
                'message'     => 'Exception: ' . $e->getMessage(),
                'provider'    => 'pawapay',
            ];
        }
    }

    public function getTransactionStatus($transaction_id, $type = 'deposit'): array
    {
        try {
            if ($type === 'withdraw') {
                $endpoint = $this->baseUrl . '/payouts/' . $transaction_id;
            } else {
                $endpoint = $this->baseUrl . '/deposits/' . $transaction_id;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json'
            ])
            ->get($endpoint);

            return [
                'success'     => $response->successful(),
                'status_code' => $response->status(),
                'data'        => $response->json() ?? $response->body(),
                'message'     => $response->successful() 
                    ? 'Request transaction sent successfully' 
                    : 'Error retrieving transaction',   
                'provider'    => 'pawapay',
            ];
        } catch (\Exception $e) {
            Log::error('OmniMsg PawaPayDriver error: ' . $e->getMessage(), [
                'transaction_id'    => $transaction_id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);

            return [
                'success'     => false,
                'status_code' => 500,
                'data'        => null,
                'message'     => 'Exception: ' . $e->getMessage(),
                'provider'    => 'pawapay',
            ];
        }
    }
}
