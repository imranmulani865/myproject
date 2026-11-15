<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\PaymentOrder;
use App\Models\TokenTransaction;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public const PLANS = [
        'trial' => [
            'name' => 'Trial Pack',
            'amount' => 9900, // Rs 99
            'tokens' => 3,
            'description' => '3 Video Credits (3 Full Videos - Unlimited Cuts)',
        ],
        'starter' => [
            'name' => 'Starter Pack',
            'amount' => 29900, // Rs 299
            'tokens' => 10,
            'description' => '10 Video Credits (10 Full Videos - Unlimited Cuts)',
        ],
        'pro' => [
            'name' => 'Creator Pack',
            'amount' => 49900, // Rs 499
            'tokens' => 20,
            'description' => '20 Video Credits (20 Full Videos - Unlimited Cuts)',
        ],
        'agency' => [
            'name' => 'Agency Scale',
            'amount' => 99900, // Rs 999
            'tokens' => 50,
            'description' => '50 Video Credits (50 Full Videos - Unlimited Cuts)',
        ],
        'mega' => [
            'name' => 'Mega Enterprise',
            'amount' => 499900, // Rs 4,999
            'tokens' => 1000,
            'description' => '1,000 Video Credits (1,000 Full Videos - Unlimited Cuts)',
        ],
    ];

    public function __construct(protected RazorpayService $razorpay)
    {
    }

    /**
     * Create Razorpay Order
     */
    public function createOrder(Request $request): JsonResponse
    {
        $request->validate([
            'plan_id' => 'required|string|in:trial,starter,pro,agency,mega',
        ]);

        $planId = $request->input('plan_id');
        $plan = self::PLANS[$planId];
        $user = auth()->user();

        $receipt = 'rcpt_' . $user->id . '_' . time();

        try {
            $rzpOrder = $this->razorpay->createOrder($plan['amount'], $receipt, [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'plan_id' => $planId,
                'tokens' => $plan['tokens'],
            ]);

            $paymentOrder = PaymentOrder::create([
                'user_id' => $user->id,
                'razorpay_order_id' => $rzpOrder['id'],
                'plan_id' => $planId,
                'amount_in_paise' => $plan['amount'],
                'currency' => 'INR',
                'tokens' => $plan['tokens'],
                'status' => 'created',
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $rzpOrder['id'],
                'amount' => $plan['amount'],
                'currency' => 'INR',
                'key_id' => $this->razorpay->getKeyId(),
                'is_mock' => $this->razorpay->isMockMode(),
                'plan_name' => $plan['name'],
                'tokens' => $plan['tokens'],
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error("Failed to create Razorpay order: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify payment signature & credit tokens
     */
    public function verifyPayment(Request $request): JsonResponse
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        $orderId = $request->input('razorpay_order_id');
        $paymentId = $request->input('razorpay_payment_id');
        $signature = $request->input('razorpay_signature', '');

        $paymentOrder = PaymentOrder::where('razorpay_order_id', $orderId)->first();

        if (!$paymentOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Payment order not found.',
            ], 404);
        }

        if ($paymentOrder->isPaid()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment already verified and tokens credited.',
                'balance' => auth()->user()->tokens,
            ]);
        }

        $isValid = $this->razorpay->verifySignature($orderId, $paymentId, $signature);

        if (!$isValid) {
            $paymentOrder->update([
                'status' => 'failed',
                'error_reason' => 'Invalid Razorpay signature',
                'razorpay_payment_id' => $paymentId,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed: Signature mismatch.',
            ], 400);
        }

        $user = $paymentOrder->user;

        DB::transaction(function () use ($paymentOrder, $user, $paymentId, $signature) {
            $paymentOrder->update([
                'status' => 'paid',
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
            ]);

            $user->increment('tokens', $paymentOrder->tokens);

            TokenTransaction::create([
                'user_id' => $user->id,
                'amount' => $paymentOrder->tokens,
                'type' => 'purchase',
                'description' => "Purchased {$paymentOrder->tokens} Tokens (" . strtoupper($paymentOrder->plan_id) . " Pack) via Razorpay [ID: {$paymentId}]",
            ]);

            AppNotification::create([
                'user_id' => $user->id,
                'title' => 'Tokens Credited Successfully!',
                'message' => "Payment of {$paymentOrder->formatted_amount} received. {$paymentOrder->tokens} tokens added to your wallet.",
                'type' => 'success',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Payment successful! {$paymentOrder->tokens} tokens credited to your account.",
            'balance' => $user->fresh()->tokens,
            'tokens_added' => $paymentOrder->tokens,
        ]);
    }
}
