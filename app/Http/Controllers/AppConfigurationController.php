<?php

namespace App\Http\Controllers;

use App\Models\FeedbackSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppConfigurationController extends Controller
{
    /**
     * Display the app configuration page.
     */
    public function index()
    {
        $teamId = Auth::user()->current_team_id;
        $feedbackSettings = FeedbackSettings::forTeam($teamId);

        return view('configuration.index', compact('feedbackSettings'));
    }

    /**
     * Update feedback settings.
     */
    public function updateFeedbackSettings(Request $request)
    {
        $teamId = Auth::user()->current_team_id;

        $request->validate([
            'ip_rate_limit_per_hour' => 'nullable|integer|min:1|max:100',
        ]);

        $feedbackSettings = FeedbackSettings::forTeam($teamId);

        $feedbackSettings->enable_login_for_voting = $request->has('enable_login_for_voting');
        $feedbackSettings->enable_anonymous_voting = $request->has('enable_anonymous_voting');
        $feedbackSettings->enable_email_based_voting = $request->has('enable_email_based_voting');
        $feedbackSettings->enable_internal_voting = $request->has('enable_internal_voting');
        $feedbackSettings->ip_rate_limit_per_hour = $request->input('ip_rate_limit_per_hour', 10);
        $feedbackSettings->require_email_otp_first_vote = $request->has('require_email_otp_first_vote');
        $feedbackSettings->enable_device_fingerprinting = $request->has('enable_device_fingerprinting');
        $feedbackSettings->enable_captcha = $request->has('enable_captcha');

        $feedbackSettings->save();

        return redirect()->route('configuration.index', ['tab' => 'feedback'])
            ->with('success', 'Feedback settings updated successfully!');
    }
}
