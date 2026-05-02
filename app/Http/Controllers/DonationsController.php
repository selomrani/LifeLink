<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationRequest;
use App\Models\BloodRequestPost;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    if($user->medicalCoolDown() > now()){
        return response()->json([
            'status' => 'error',
            'message' => 'You can only make one donation every 56 days',
        ], 422);
    }
    $validated = $request->validated();
    $validated['donor_id'] = Auth::id();
    $validated['blood_request_post_id'] = $post->id;
    $donation = Donation::create($validated);

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
    public function acceptDonation(Donation $donation){
        $donation->status = 'accepted';
        $donation->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Donation accepted successfully',
            'donation' => $donation]
        );
    }
    public function rejectDonation(Donation $donation){
        $donation->status = 'rejected';
        $donation->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Donation rejected successfully',
            'donation' => $donation]
        );
    }
    public function donationDetails(Donation $donation){
        return response()->json([
            'status' => 'success',
            'message' => 'Donation details',
            'donation' => $donation]
        );
    }
    public function postDonationsIndex(BloodRequestPost $post){
        $donations = Donation::with('donor')
    ->where('blood_request_post_id', $post->id)
    ->where('status', 'pending')
    ->get();
        return response()->json($donations);
    }
}
