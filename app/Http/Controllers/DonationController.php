<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationRequest;
use App\Models\Donation;
use App\Models\AuditLog;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class DonationController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function index()
    {
        return view('donations.index');
    }

    public function charge(DonationRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        // Block children from donating (extra safety beyond route middleware)
        if ($user->profile?->age_group === 'child') {
            abort(403, __('donations.minors_blocked'));
        }

        $amountCents = (int) round($data['amount'] * 100);

        try {
            $intent = PaymentIntent::create([
                'amount'               => $amountCents,
                'currency'             => 'usd',
                'payment_method'       => $data['payment_method_id'],
                'confirm'              => true,
                'automatic_payment_methods' => ['enabled' => true, 'allow_redirects' => 'never'],
                'metadata'             => [
                    'user_id'  => $user?->id ?? 'guest',
                    'type'     => $data['type'],
                    'platform' => 'hifz',
                ],
            ]);

            $donation = Donation::create([
                'user_id'      => $user?->id,
                'amount'       => $data['amount'],
                'currency'     => 'usd',
                'type'         => $data['type'],
                'stripe_pi_id' => $intent->id,
                'status'       => $intent->status === 'succeeded' ? 'completed' : 'pending',
                'donor_name'   => $data['donor_name'] ?? null,
            ]);

            AuditLog::record($user?->id, 'donation_created', 'Donation', $donation->id, [
                'amount' => $data['amount'],
                'type'   => $data['type'],
            ]);

            return redirect()->route('donations.success')
                ->with('status', __('donations.thank_you'));

        } catch (\Stripe\Exception\CardException $e) {
            return back()->withErrors(['payment' => $e->getUserMessage()]);
        } catch (\Exception $e) {
            \Log::error('Donation failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['payment' => __('donations.payment_failed')]);
        }
    }

    public function success()
    {
        return view('donations.success');
    }

    public function cancel()
    {
        return view('donations.cancel');
    }
}
