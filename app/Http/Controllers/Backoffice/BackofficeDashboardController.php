<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Changelog;
use App\Models\Feedback;
use App\Models\Team;
use App\Models\User;
use Illuminate\View\View;

class BackofficeDashboardController extends Controller
{
    /**
     * Display the backoffice dashboard.
     */
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_teams' => Team::count(),
            'total_feedbacks' => Feedback::count(),
            'total_changelogs' => Changelog::count(),
            'total_clients' => User::whereHas('ownedTeams')->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentTeams = Team::with('owner')->latest()->take(5)->get();

        return view('backoffice.dashboard', compact('stats', 'recentUsers', 'recentTeams'));
    }
}
