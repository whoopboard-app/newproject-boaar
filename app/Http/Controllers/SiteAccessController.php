<?php

namespace App\Http\Controllers;

use App\Mail\SiteAccessVerificationCode;
use App\Models\AppSettings;
use App\Models\SiteAccessInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SiteAccessController extends Controller
{
    /**
     * Show the access login form.
     */
    public function showLoginForm(Request $request)
    {
        $settings = $request->attributes->get('subdomain_settings');

        if (!$settings || !$settings->isPrivate()) {
            return redirect('/');
        }

        return view('public.access-login', [
            'settings' => $settings,
        ]);
    }

    /**
     * Request a verification code.
     */
    public function requestCode(Request $request)
    {
        $settings = $request->attributes->get('subdomain_settings');

        if (!$settings || !$settings->isPrivate()) {
            return redirect('/');
        }

        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $email = strtolower($validated['email']);

        // Check if email is in the invite list
        $invite = SiteAccessInvite::forEmailAndTeam($email, $settings->team_id)->first();

        if (!$invite) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'This email address is not authorized to access this site.']);
        }

        // Generate and send verification code
        $code = $invite->generateVerificationCode();

        // Send email with code
        Mail::to($email)->send(new SiteAccessVerificationCode($invite, $settings));

        // Store email in session for verification step
        $request->session()->put('site_access_pending_email', $email);

        return redirect()->route('public.access.verify-form')
            ->with('success', 'A verification code has been sent to your email.');
    }

    /**
     * Show the verification code form.
     */
    public function showVerifyForm(Request $request)
    {
        $settings = $request->attributes->get('subdomain_settings');

        if (!$settings || !$settings->isPrivate()) {
            return redirect('/');
        }

        $email = $request->session()->get('site_access_pending_email');

        if (!$email) {
            return redirect()->route('public.access.login');
        }

        return view('public.access-verify', [
            'settings' => $settings,
            'email' => $email,
        ]);
    }

    /**
     * Verify the code and grant access.
     */
    public function verifyCode(Request $request)
    {
        $settings = $request->attributes->get('subdomain_settings');

        if (!$settings || !$settings->isPrivate()) {
            return redirect('/');
        }

        $validated = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $email = $request->session()->get('site_access_pending_email');

        if (!$email) {
            return redirect()->route('public.access.login')
                ->withErrors(['code' => 'Session expired. Please request a new code.']);
        }

        $invite = SiteAccessInvite::forEmailAndTeam($email, $settings->team_id)->first();

        if (!$invite) {
            return redirect()->route('public.access.login')
                ->withErrors(['email' => 'This email address is not authorized to access this site.']);
        }

        if (!$invite->isCodeValid($validated['code'])) {
            return back()
                ->withErrors(['code' => 'Invalid or expired verification code. Please try again or request a new code.']);
        }

        // Mark as verified
        $invite->markAsVerified();

        // Store access in session
        $request->session()->put('site_access_email', $email);
        $request->session()->put('site_access_team_id', $settings->team_id);

        // Clear pending email
        $request->session()->forget('site_access_pending_email');

        // Redirect to intended URL or home
        $intendedUrl = $request->session()->pull('site_access_intended_url', '/');

        return redirect($intendedUrl)
            ->with('success', 'Access granted! Welcome to the site.');
    }

    /**
     * Logout and clear access session.
     */
    public function logout(Request $request)
    {
        $request->session()->forget([
            'site_access_email',
            'site_access_team_id',
            'site_access_pending_email',
            'site_access_intended_url',
        ]);

        return redirect()->route('public.access.login')
            ->with('success', 'You have been logged out.');
    }

    /**
     * Resend verification code.
     */
    public function resendCode(Request $request)
    {
        $settings = $request->attributes->get('subdomain_settings');

        if (!$settings || !$settings->isPrivate()) {
            return redirect('/');
        }

        $email = $request->session()->get('site_access_pending_email');

        if (!$email) {
            return redirect()->route('public.access.login');
        }

        $invite = SiteAccessInvite::forEmailAndTeam($email, $settings->team_id)->first();

        if (!$invite) {
            return redirect()->route('public.access.login')
                ->withErrors(['email' => 'This email address is not authorized to access this site.']);
        }

        // Generate new code
        $code = $invite->generateVerificationCode();

        // Send email with code
        Mail::to($email)->send(new SiteAccessVerificationCode($invite, $settings));

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
