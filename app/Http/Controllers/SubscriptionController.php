<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SubscriptionController extends Controller
{
    public function pricingPage()
    {
        return view('pricing.index');
    }

    public function subscribe(Request $request)
    {
        $user = auth()->user();

        // Block children from subscribing
        if ($user->profile?->age_group === 'child') {
            abort(403, __('subscriptions.minors_blocked'));
        }

        $data = $request->validate([
            'plan'           => ['required', 'in:monthly,annual'],
            'payment_method' => ['required', 'string'],
        ]);

        $priceId = $data['plan'] === 'annual'
            ? config('services.stripe.annual_price_id')
            : config('services.stripe.monthly_price_id');

        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($data['payment_method']);

            $user->newSubscription('default', $priceId)->create($data['payment_method']);

            AuditLog::record($user->id, 'subscription_created', 'User', $user->id, [
                'plan' => $data['plan'],
            ]);

            return redirect()->route('subscribe.success')
                ->with('status', __('subscriptions.success'));

        } catch (IncompletePayment $e) {
            return redirect()->route('cashier.payment', [$e->payment->id, 'redirect' => route('subscribe.success')]);
        } catch (\Exception $e) {
            \Log::error('Subscription failed', ['error' => $e->getMessage(), 'user' => $user->id]);
            return back()->withErrors(['payment' => __('subscriptions.failed')]);
        }
    }

    public function success()
    {
        return view('subscriptions.success');
    }

    public function cancelSubscription(Request $request)
    {
        $user = auth()->user();

        if ($user->subscribed('default')) {
            $user->subscription('default')->cancel();

            AuditLog::record($user->id, 'subscription_cancelled', 'User', $user->id);
        }

        return redirect()->route('account.settings')
            ->with('status', __('subscriptions.cancelled'));
    }

    public function billingPortal(Request $request)
    {
        return auth()->user()->redirectToBillingPortal(route('account.settings'));
    }

    // Stripe webhook handler — verified by signature
    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response('Webhook signature verification failed.', 400);
        }

        match ($event->type) {
            'invoice.payment_succeeded'      => $this->handleInvoicePaid($event->data->object),
            'customer.subscription.deleted'  => $this->handleSubDeleted($event->data->object),
            default                          => null,
        };

        return response('OK', 200);
    }

    protected function handleInvoicePaid(object $invoice): void
    {
        AuditLog::record(null, 'stripe_invoice_paid', null, null, [
            'invoice_id'  => $invoice->id,
            'amount_paid' => $invoice->amount_paid,
        ]);
    }

    protected function handleSubDeleted(object $sub): void
    {
        AuditLog::record(null, 'stripe_sub_deleted', null, null, [
            'stripe_sub_id' => $sub->id,
        ]);
    }
}
