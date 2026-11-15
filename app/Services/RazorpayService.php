<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected string $keyId;
    protected string $keySecret;
    protected string $apiBaseUrl = 'https://api.razorpay.com/v1';

    public function __construct()
    {
        $this->keyId = config('services.razorpay.key_id') ?? env('RAZORPAY_KEY_ID', 'rzp_test_YourRazorpayKeyId');
        $this->keySecret = config('services.razorpay.key_secret') ?? env('RAZORPAY_KEY_SECRET', 'YourRazorpayKeySecret');
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }

    public function isMockMode(): bool
    {
        // Mock mode if default placeholder keys are used
        return empty($this->keyId) 
            || $this->keyId === 'rzp_test_YourRazorpayKeyId' 
            || $this->keyId === 'rzp_test_mock_key'
            || str_contains($this->keyId, 'YourRazorpayKeyId');
    }

    /**
     * Create Razorpay Order
     */
    public function createOrder(int $amountInPaise, string $receipt, array $notes = []): array
    {
        if ($this->isMockMode()) {
            $mockOrderId = 'order_mock_' . time() . '_' . bin2hex(random_bytes(4));
            Log::info("RazorpayService: Generated mock order {$mockOrderId} for amount {$amountInPaise} paise.");

            return [
                'id' => $mockOrderId,
                'entity' => 'order',
                'amount' => $amountInPaise,
                'amount_paid' => 0,
                'amount_due' => $amountInPaise,
                'currency' => 'INR',
                'receipt' => $receipt,
                'status' => 'created',
                'mock' => true,
            ];
        }

        try {
            $response = Http::withBasicAuth($this->keyId, $this->keySecret)
                ->timeout(15)
                ->post("{$this->apiBaseUrl}/orders", [
                    'amount' => $amountInPaise,
                    'currency' => 'INR',
                    'receipt' => $receipt,
                    'notes' => $notes,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Razorpay API create order failed: " . $response->body());
            throw new \Exception("Razorpay API error: " . ($response->json('error.description') ?? $response->body()));
        } catch (\Throwable $e) {
            Log::error("Razorpay Order Exception: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify payment signature
     */
    public function verifySignature(string $orderId, string $paymentId, string $signature): bool
    {
        if ($this->isMockMode()) {
            return true;
        }

        $expectedSignature = hash_hmac('sha256', "{$orderId}|{$paymentId}", $this->keySecret);

        return hash_equals($expectedSignature, $signature);
    }
}
