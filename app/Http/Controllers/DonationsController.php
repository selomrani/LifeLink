<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationRequest;
use App\Mail\DonationAcceptedMail;
use App\Mail\DonationRejectedMail;
use App\Mail\NewDonationOfferMail;
use App\Models\BloodRequestPost;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DonationsController extends Controller
{
    public function myDonations()
    {
        $donations = Donation::where('donor_id', Auth::id())->get();

        return response()->json($donations);
    }

    public function offerDonation(DonationRequest $request, BloodRequestPost $post)
    {
        $user = Auth::user();
        $cooldown = $user->medicalCoolDown();
        if ($cooldown && $cooldown > now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You can only make one donation every 56 days',
            ], 422);
        }
        $validated = $request->validated();
        $validated['donor_id'] = Auth::id();
        $validated['blood_request_post_id'] = $post->id;
        $donation = Donation::create($validated);

        $post->load('user');
        Mail::to($post->user->email)->send(new NewDonationOfferMail($post->user, $donation));

        return response()->json([
            'status' => 'success',
            'data' => $donation,
            'message' => 'Donation created successfully',
        ]);
    }

    public function deleteDonation(Donation $donation)
    {
        $donation->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Donation deleted successfully',
        ]);
    }

    public function acceptDonation(Donation $donation)
    {
        $donation->status = 'accepted';
        $donation->save();
        $donation->load('donor');
        Mail::to($donation->donor->email)->send(new DonationAcceptedMail($donation->donor, $donation));

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Donation accepted successfully',
                'donation' => $donation,
            ]
        );
    }

    public function rejectDonation(Donation $donation)
    {
        $donation->status = 'rejected';
        $donation->save();
        $donation->load('donor');
        Mail::to($donation->donor->email)->send(new DonationRejectedMail($donation->donor, $donation));

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Donation rejected successfully',
                'donation' => $donation,
            ]
        );
    }

    public function donationDetails(Donation $donation)
    {
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Donation details',
                'donation' => $donation,
            ]
        );
    }

    public function postDonationsIndex(BloodRequestPost $post)
    {
        $donations = Donation::with('donor')
            ->where('blood_request_post_id', $post->id)
            ->where('status', 'pending')
            ->get();

        return response()->json($donations);
    }
}
