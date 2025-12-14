<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Mail\BackofficeAccessCodeMail;
use App\Models\AdminUser;
use App\Models\BackofficeAccessCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BackofficeAuthController extends Controller
{
    /**
     * Get the admin guard.
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Screen 1: Show the email form to request access code.
     */
    public function showEmailForm(): View|RedirectResponse
    {
        // If already authenticated in backoffice, redirect to dashboard
        if (session('backoffice_authenticated') && $this->guard()->check()) {
            return redirect()->route('backoffice.dashboard');
        }

        return view('backoffice.auth.email');
    }

    /**
     * Send verification code to the user's email.
     */
    public function sendCode(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // Check if admin user exists
        $adminUser = AdminUser::where('email', $email)->first();

        if (!$adminUser) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Not a validated user. You do not have access to the backoffice.']);
        }

        // Generate and send the access code
        $accessCode = BackofficeAccessCode::generateFor($email);

        Mail::to($email)->send(new BackofficeAccessCodeMail($accessCode));

        // Store email in session for the login form
        session(['backoffice_pending_email' => $email]);

        return redirect()
            ->route('backoffice.login')
            ->with('status', 'A verification code has been sent to your email address.');
    }

    /**
     * Screen 2: Show the login form (after code verification).
     */
    public function showLoginForm(): View|RedirectResponse
    {
        // If already authenticated in backoffice, redirect to dashboard
        if (session('backoffice_authenticated') && $this->guard()->check()) {
            return redirect()->route('backoffice.dashboard');
        }

        // Check if user has requested a code
        if (!session('backoffice_pending_email')) {
            return redirect()->route('backoffice.email')
                ->withErrors(['email' => 'Please request a verification code first.']);
        }

        return view('backoffice.auth.login', [
            'email' => session('backoffice_pending_email'),
        ]);
    }

    /**
     * Authenticate the user with code and password.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|string',
        ]);

        $email = $request->input('email');
        $code = $request->input('code');
        $password = $request->input('password');

        // Find and validate the access code
        $accessCode = BackofficeAccessCode::findValidCode($email, $code);

        if (!$accessCode) {
            return back()
                ->withInput()
                ->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        // Attempt to authenticate the admin user
        if (!$this->guard()->attempt(['email' => $email, 'password' => $password])) {
            return back()
                ->withInput()
                ->withErrors(['password' => 'Invalid password.']);
        }

        // Mark the code as used
        $accessCode->markAsUsed();

        // Set backoffice session flag
        session(['backoffice_authenticated' => true]);
        session()->forget('backoffice_pending_email');

        // Regenerate session
        $request->session()->regenerate();

        return redirect()->route('backoffice.dashboard');
    }

    /**
     * Logout from the backoffice.
     */
    public function logout(Request $request): RedirectResponse
    {
        // Clear backoffice session
        session()->forget('backoffice_authenticated');
        session()->forget('backoffice_pending_email');

        // Logout admin user
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('backoffice.email');
    }
}
