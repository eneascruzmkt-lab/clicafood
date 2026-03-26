<?php

namespace App\Http\Controllers;

use App\Services\AbacatePayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    protected array $plans = [
        'free' => [
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'price_display' => 'R$ 0',
            'items_limit' => 5,
            'features' => [
                'Ate 5 itens no cardapio',
                'QR Code basico',
                'Link do cardapio digital',
            ],
            'abacatepay_product_id' => null,
        ],
        'pro' => [
            'name' => 'Pro',
            'slug' => 'pro',
            'price' => 4990,
            'price_display' => 'R$ 49,90',
            'items_limit' => 50,
            'features' => [
                'Ate 50 itens no cardapio',
                'Videos nos itens',
                'Metricas e analytics',
                'QR Codes ilimitados',
                'Stories',
            ],
            'abacatepay_product_id' => null,
        ],
        'business' => [
            'name' => 'Business',
            'slug' => 'business',
            'price' => 9990,
            'price_display' => 'R$ 99,90',
            'items_limit' => null,
            'features' => [
                'Itens ilimitados',
                'Videos nos itens',
                'Metricas e analytics avancados',
                'QR Codes ilimitados',
                'Stories',
                'Suporte prioritario',
            ],
            'abacatepay_product_id' => null,
        ],
    ];

    /**
     * Show available plans.
     */
    public function plans(Request $request)
    {
        return Inertia::render('Plans/Index', [
            'plans' => array_values($this->plans),
            'currentPlan' => $request->user()->subscription_plan ?? 'free',
        ]);
    }

    /**
     * Create a subscription checkout and redirect to AbacatePay.
     */
    public function subscribe(Request $request, string $plan)
    {
        if (!isset($this->plans[$plan]) || $plan === 'free') {
            return back()->with('error', 'Plano invalido.');
        }

        $user = $request->user();
        $planData = $this->plans[$plan];
        $service = new AbacatePayService();

        try {
            // Create or reuse AbacatePay customer
            if (!$user->abacatepay_customer_id) {
                $customerResponse = $service->createCustomer([
                    'email' => $user->email,
                    'name' => $user->name,
                    'cellphone' => $user->phone,
                    'taxId' => $user->cpf_cnpj,
                ]);

                $user->abacatepay_customer_id = $customerResponse['data']['id'];
                $user->save();
            }

            // Create the product in AbacatePay (or use a cached product ID)
            $productResponse = $service->createProduct([
                'externalId' => "clicafood_{$plan}_monthly",
                'name' => "ClicaFood {$planData['name']} - Mensal",
                'price' => $planData['price'],
                'currency' => 'BRL',
                'description' => "Plano {$planData['name']} do ClicaFood - Assinatura mensal",
                'cycle' => 'MONTHLY',
            ]);

            $productId = $productResponse['data']['id'];

            // Create subscription checkout
            $checkoutResponse = $service->createSubscriptionCheckout([
                'items' => [
                    ['id' => $productId, 'quantity' => 1],
                ],
                'methods' => ['CARD'],
                'returnUrl' => route('subscription.success'),
                'completionUrl' => route('subscription.success'),
                'customerId' => $user->abacatepay_customer_id,
                'externalId' => "user_{$user->id}_{$plan}",
                'metadata' => [
                    'user_id' => $user->id,
                    'plan' => $plan,
                ],
            ]);

            $checkoutUrl = $checkoutResponse['data']['url'];

            // Store pending subscription info
            $user->update([
                'subscription_plan' => $plan,
                'subscription_status' => 'trial',
            ]);

            return Inertia::location($checkoutUrl);

        } catch (\Exception $e) {
            Log::error('Subscription creation failed', [
                'user_id' => $user->id,
                'plan' => $plan,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Erro ao processar assinatura. Tente novamente.');
        }
    }

    /**
     * Handle AbacatePay webhooks.
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        $event = $payload['event'] ?? null;

        Log::info('AbacatePay webhook received', ['event' => $event, 'data' => $payload]);

        switch ($event) {
            case 'billing.paid':
                $this->handleBillingPaid($payload);
                break;

            case 'subscription.created':
                $this->handleSubscriptionCreated($payload);
                break;

            case 'subscription.canceled':
                $this->handleSubscriptionCanceled($payload);
                break;

            default:
                Log::warning('Unhandled AbacatePay webhook event', ['event' => $event]);
        }

        return response()->json(['received' => true]);
    }

    /**
     * Success page after payment.
     */
    public function success(Request $request)
    {
        return Inertia::render('Subscription/Success', [
            'plan' => $request->user()->subscription_plan ?? 'free',
        ]);
    }

    /**
     * Subscription management page.
     */
    public function manage(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Subscription/Manage', [
            'subscription' => [
                'status' => $user->subscription_status,
                'plan' => $user->subscription_plan ?? 'free',
                'expires_at' => $user->subscription_expires_at?->format('d/m/Y'),
                'subscription_id' => $user->subscription_id,
            ],
            'plans' => array_values($this->plans),
        ]);
    }

    /**
     * Handle billing.paid webhook.
     */
    protected function handleBillingPaid(array $payload): void
    {
        $data = $payload['data'] ?? [];
        $customerId = $data['customer']['id'] ?? null;
        $subscriptionId = $data['id'] ?? null;

        if (!$customerId) {
            Log::warning('billing.paid webhook missing customer ID');
            return;
        }

        $user = \App\Models\User::where('abacatepay_customer_id', $customerId)->first();

        if (!$user) {
            Log::warning('billing.paid webhook: user not found', ['customer_id' => $customerId]);
            return;
        }

        $user->update([
            'subscription_status' => 'active',
            'subscription_id' => $subscriptionId ?? $user->subscription_id,
            'subscription_expires_at' => now()->addMonth(),
        ]);

        Log::info('Subscription activated via billing.paid', ['user_id' => $user->id]);
    }

    /**
     * Handle subscription.created webhook.
     */
    protected function handleSubscriptionCreated(array $payload): void
    {
        $data = $payload['data'] ?? [];
        $customerId = $data['customer']['id'] ?? null;
        $subscriptionId = $data['id'] ?? null;

        if (!$customerId) {
            return;
        }

        $user = \App\Models\User::where('abacatepay_customer_id', $customerId)->first();

        if ($user) {
            $user->update([
                'subscription_id' => $subscriptionId,
            ]);
        }
    }

    /**
     * Handle subscription.canceled webhook.
     */
    protected function handleSubscriptionCanceled(array $payload): void
    {
        $data = $payload['data'] ?? [];
        $customerId = $data['customer']['id'] ?? null;

        if (!$customerId) {
            return;
        }

        $user = \App\Models\User::where('abacatepay_customer_id', $customerId)->first();

        if ($user) {
            $user->update([
                'subscription_status' => 'canceled',
            ]);
            Log::info('Subscription canceled', ['user_id' => $user->id]);
        }
    }
}
