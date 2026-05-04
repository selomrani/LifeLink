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
    public function donate(Request $request, BloodRequestPost $bloodrequest)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            $stripe = new StripeClient(config('services.stripe.secret') ?? env('STRIPE_SECRET'));

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $validated['amount'] * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'user_id' => Auth::id(),
                    'post_id' => $bloodrequest->id,
                ],
            ]);

            $monetaryDonation = MonetaryDonation::create([
                'user_id' => Auth::id(),
                'amount' => $validated['amount'],
                'post_id' => $bloodrequest->id,
            ]);

            $bloodrequest->load('user');
            $donor = Auth::user();
            Mail::to($donor->email)->send(new MonetaryDonationConfirmationMail($donor, $monetaryDonation));
            Mail::to($bloodrequest->user->email)->send(new MonetaryDonationReceivedMail($bloodrequest->user, $monetaryDonation));

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'status' => 'success',
                'message' => 'Payment intent created successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stripe Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
