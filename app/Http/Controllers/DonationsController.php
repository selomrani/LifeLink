<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationRequest;
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
    public function offerDonation(DonationRequest $request)
    {
        $donation = Donation::create($request->all());
        return response()->json($donation);
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
    public function postDonationsIndex(Donation $donation){
        $donations = Donation::where('blood_request_post_id', $donation->id)->where('status', 'pending')->get();
        return response()->json($donations);
    }
}
