<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppSettingsController extends Controller
{
    public function index()
    {
        // Only Owner, Admin, and Moderator can access app settings
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access app settings.');
        }

        return view('settings.index');
    }
}
