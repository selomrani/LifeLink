<?php

namespace App\Http\Controllers;

use App\Models\BloodRequestPost;
use App\Models\MonetaryDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonetaryDonationController extends Controller
{
    public function checkout(Request $request, BloodRequestPost $bloodrequest)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['post_id'] = $bloodrequest->id;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Donation',
                    ],
                    'unit_amount' => $validated['amount'] * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('donations.success'),
            'cancel_url'  => route('donations.cancel'),
            'metadata' => [
                'donation' => json_encode($validated),
            ],
        ]);

        MonetaryDonation::create($validated);

        return response()->json(['url' => $session->url]);
    }
}
