<?php

namespace App\Http\Controllers\Donations;

use App\Http\Controllers\Controller;
use App\Models\BloodRequestPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class StripeController extends Controller
{
    /**
     * Handle the donation process.
     * * @param Request $request
     * @param BloodRequestPost $bloodrequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function donate(Request $request, BloodRequestPost $bloodrequest)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $stripe = new StripeClient(config('services.stripe.secret'));

        $paymentIntent = $stripe->paymentIntents->create([
            'amount'   => (int) ($validated['amount'] * 100),
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'metadata' => [
                'user_id' => Auth::id(),
                'post_id' => $bloodrequest->id,
            ],
        ]);
        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
        ]);
    }
}
