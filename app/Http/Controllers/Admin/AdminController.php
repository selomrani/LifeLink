<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequestPost;
use App\Models\Donation;
use App\Models\MonetaryDonation;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use App\DTOs\UserDTO;

class AdminController extends Controller
{
    public function statistics()
    {
        $usersCount    = User::count();
        $requestCount  = BloodRequestPost::where('status', 'open')->count();
        $livesSaved    = Donation::where('status', 'accepted')->count();
        $totalMoney    = MonetaryDonation::sum('amount');
        $reportsCount  = Report::where('status', 'pending')->count();

        return response()->json([
            'users'      => $usersCount,
            'livesSaved' => $livesSaved,
            'totalMoney' => $totalMoney,
            'requests'   => $requestCount,
            'reports'    => $reportsCount,
        ]);
    }
    public function fetchUsers()
    {
        $users = User::with('bloodRequestPosts','role')->get();
        $cleanUsers = $users->map(function ($user) {
            return UserDTO::fromModel($user);
        });
        return response()->json([
            'data' => $cleanUsers
        ]);
    }
    public function review(Report $report)
    {
        $report->load(['reporter', 'reportedUser']);

        $report->reportedUser->is_active = false;
        $report->reportedUser->save();
        $report->status = 'resolved';
        $report->save();
        return response()->json([
            'message' => 'User banned successfully',
            'report_id' => $report->id,
            'banned_user_id' => $report->reported_user_id,
        ]);
    }
}
