<?php

namespace App\Http\Controllers\Donations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class StripeController extends Controller
{
    public function index()
    {
        return view('stripe');
    }

    public function checkout(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = \Stripe\Checkout\Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Donation',
                    ],
                    'unit_amount' => 1000, // $10.00
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('donations.success'),
            'cancel_url'  => route('donations.cancel'),
        ]);

        return redirect($session->url);
    }
}
