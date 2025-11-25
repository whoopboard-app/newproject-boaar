<?php

namespace App\Http\Controllers;

use App\Models\PublicUser;
use App\Models\AppSettings;
use App\Notifications\MagicLinkNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

class PublicAuthController extends Controller
{
    /**
     * Show the magic link request form.
     */
    public function showLoginForm($uniqueUrl)
    {
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();
        return view('public.auth.login', compact('settings'));
    }

    /**
     * Send magic link to user's email.
     */
    public function sendMagicLink(Request $request, $uniqueUrl)
    {
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        // Find or create public user
        $publicUser = PublicUser::firstOrCreate(
            ['email' => $request->email],
            ['full_name' => null]
        );

        // Generate magic token
        $token = $publicUser->generateMagicToken();

        // Send magic link email
        Notification::route('mail', $publicUser->email)
            ->notify(new MagicLinkNotification($publicUser, $settings, $token));

        return view('public.auth.magic-link-sent', compact('settings', 'publicUser'));
    }

    /**
     * Verify magic link and log user in.
     */
    public function verifyMagicLink($token)
    {
        $publicUser = PublicUser::where('magic_token', $token)->first();

        if (!$publicUser) {
            return view('public.auth.invalid-link');
        }

        if (!$publicUser->isMagicTokenValid()) {
            return view('public.auth.expired-link');
        }

        // Log the user in
        $publicUser->markAsLoggedIn(request()->ip());

        // Store in session
        Session::put('public_user_id', $publicUser->id);
        Session::put('public_user_email', $publicUser->email);

        // Redirect to home page
        $appSettings = AppSettings::first(); // Get first settings or adjust logic
        if ($appSettings) {
            return redirect()->route('public.home', $appSettings->unique_url)
                ->with('success', 'You have successfully logged in!');
        }

        return redirect('/')->with('success', 'You have successfully logged in!');
    }

    /**
     * Log the public user out.
     */
    public function logout($uniqueUrl)
    {
        Session::forget('public_user_id');
        Session::forget('public_user_email');

        return redirect()->route('public.home', $uniqueUrl)
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Check if public user is logged in.
     */
    public static function isLoggedIn()
    {
        return Session::has('public_user_id');
    }

    /**
     * Get the currently logged in public user.
     */
    public static function user()
    {
        if (self::isLoggedIn()) {
            return PublicUser::find(Session::get('public_user_id'));
        }
        return null;
    }
}
