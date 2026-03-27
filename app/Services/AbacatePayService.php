<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AbacatePayService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.abacatepay.com/v2';
        $this->apiKey = config('services.abacatepay.api_key');
    }

    /**
     * Create a product in AbacatePay.
     */
    public function createProduct(array $data): array
    {
        $response = Http::withToken($this->apiKey)
            ->post("{$this->baseUrl}/products/create", [
                'externalId' => $data['externalId'],
                'name' => $data['name'],
                'price' => $data['price'], // in centavos
                'currency' => $data['currency'] ?? 'BRL',
                'description' => $data['description'] ?? null,
                'imageUrl' => $data['imageUrl'] ?? null,
                'cycle' => $data['cycle'] ?? null, // MONTHLY for subscriptions
            ]);

        if ($response->failed()) {
            Log::error('AbacatePay createProduct failed', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
            throw new \Exception('Failed to create product in AbacatePay: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Get a product by externalId.
     */
    public function getProduct(string $externalId): array
    {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/products/get", [
                'externalId' => $externalId,
            ]);

        if ($response->failed()) {
            throw new \Exception('Product not found: ' . $externalId);
        }

        return $response->json();
    }

    /**
     * Create a subscription checkout in AbacatePay.
     */
    public function createSubscriptionCheckout(array $data): array
    {
        $payload = [
            'items' => $data['items'], // [{id, quantity}]
            'methods' => $data['methods'] ?? ['CARD'],
        ];

        if (isset($data['returnUrl'])) {
            $payload['returnUrl'] = $data['returnUrl'];
        }
        if (isset($data['completionUrl'])) {
            $payload['completionUrl'] = $data['completionUrl'];
        }
        if (isset($data['customerId'])) {
            $payload['customerId'] = $data['customerId'];
        }
        if (isset($data['externalId'])) {
            $payload['externalId'] = $data['externalId'];
        }
        if (isset($data['metadata'])) {
            $payload['metadata'] = $data['metadata'];
        }

        $response = Http::withToken($this->apiKey)
            ->post("{$this->baseUrl}/subscriptions/create", $payload);

        if ($response->failed()) {
            Log::error('AbacatePay createSubscriptionCheckout failed', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
            throw new \Exception('Failed to create subscription checkout: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Create a customer in AbacatePay.
     */
    public function createCustomer(array $data): array
    {
        $payload = [
            'email' => $data['email'],
        ];

        if (isset($data['name'])) {
            $payload['name'] = $data['name'];
        }
        if (isset($data['cellphone'])) {
            $payload['cellphone'] = $data['cellphone'];
        }
        if (isset($data['taxId'])) {
            $payload['taxId'] = $data['taxId'];
        }
        if (isset($data['zipCode'])) {
            $payload['zipCode'] = $data['zipCode'];
        }

        $response = Http::withToken($this->apiKey)
            ->post("{$this->baseUrl}/customers/create", $payload);

        if ($response->failed()) {
            Log::error('AbacatePay createCustomer failed', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
            throw new \Exception('Failed to create customer: ' . $response->body());
        }

        return $response->json();
    }
}
