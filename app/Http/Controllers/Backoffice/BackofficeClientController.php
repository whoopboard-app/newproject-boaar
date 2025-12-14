<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BackofficeClientController extends Controller
{
    /**
     * Display a listing of clients (team owners).
     */
    public function index(Request $request): View
    {
        $query = User::whereHas('ownedTeams')
            ->with(['ownedTeams' => function ($q) {
                $q->withCount(['members']);
            }])
            ->withCount('ownedTeams');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(15);

        return view('backoffice.clients.index', compact('clients'));
    }

    /**
     * Display the specified client.
     */
    public function show(User $client): View
    {
        $client->load(['ownedTeams' => function ($q) {
            $q->withCount(['members', 'feedbacks', 'changelogs']);
        }]);

        return view('backoffice.clients.show', compact('client'));
    }
}
