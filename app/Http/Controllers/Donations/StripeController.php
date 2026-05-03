<?php

namespace App\Http\Controllers\Donations;

use App\Http\Controllers\Controller;
use App\Mail\MonetaryDonationConfirmationMail;
use App\Mail\MonetaryDonationReceivedMail;
use App\Models\BloodRequestPost;
use App\Models\MonetaryDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;

class StripeController extends Controller
{
    /**
     * Handle the donation process.
     *
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

            // 4. Save the monetary donation record
            $monetaryDonation = MonetaryDonation::create([
                'user_id' => Auth::id(),
                'amount' => $validated['amount'],
                'post_id' => $bloodrequest->id,
            ]);

            $bloodrequest->load('user');
            $donor = Auth::user();
            Mail::to($donor->email)->send(new MonetaryDonationConfirmationMail($donor, $monetaryDonation));
            Mail::to($bloodrequest->user->email)->send(new MonetaryDonationReceivedMail($bloodrequest->user, $monetaryDonation));

            // 5. Return the client_secret to Vue
            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'status' => 'success',
                'message' => 'Payment intent created successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stripe Error: '.$e->getMessage(),
            ], 500);
        }
    }
}
