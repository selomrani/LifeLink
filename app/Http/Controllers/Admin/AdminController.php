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
use App\Mail\BannedMail;
use App\Mail\UnbannedMail;
use Illuminate\Support\Facades\Mail;

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
    public function fetchReports()
    {
        $reports = Report::where('status','pending')->with(['reporter', 'reportedUser'])
            ->latest()
            ->get();

        return response()->json([
            'data' => $reports
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
    public function ban(User $user)
    {
        $user->is_active = false;
        $user->save();

        return response()->json([
            'message' => 'User banned successfully',
            'banned_user_id' => $user->id,
        ]);
    }

    public function toggleBan(User $user)
    {
        if ($user->is_active) {
            $user->is_active = false;
            $user->save();
            Mail::to($user->email)->send(new BannedMail($user));
            return response()->json(['message' => 'User banned successfully', 'is_active' => false]);
        }

        $user->is_active = true;
        $user->save();
        Mail::to($user->email)->send(new UnbannedMail($user));
        return response()->json(['message' => 'User unbanned successfully', 'is_active' => true]);
    }

    public function review(Report $report)
    {
        $report->load(['reporter', 'reportedUser']);

        $report->reportedUser->is_active = false;
        $report->reportedUser->save();
        $report->status = 'resolved';
        $report->save();

        Mail::to($report->reportedUser->email)->send(new BannedMail($report->reportedUser));

        return response()->json([
            'message' => 'User banned successfully',
            'report_id' => $report->id,
            'banned_user_id' => $report->reported_user_id,
        ]);
    }
}
