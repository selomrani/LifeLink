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
        // 1. Validate the incoming amount
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            // 2. Initialize Stripe
            $stripe = new StripeClient(config('services.stripe.secret') ?? env('STRIPE_SECRET'));

            // 3. Create the Payment Intent
            // This creates a "pending" payment in Stripe's system
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $validated['amount'] * 100, // Stripe expects amounts in cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'user_id' => Auth::id(),
                    'post_id' => $bloodrequest->id,
                ],
            ]);

            // 4. Save the record to your local database
            // We use the relationship to ensure post_id is set correctly
            $donation = $bloodrequest->donations()->create([
                'amount'  => $validated['amount'],
                'user_id' => Auth::id(),
                // 'post_id' is automatically handled by the relationship helper
            ]);

            // 5. Return the client_secret to Vue
            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'donation'      => $donation,
                'status'        => 'success',
                'message'       => 'Payment intent created successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Stripe Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
