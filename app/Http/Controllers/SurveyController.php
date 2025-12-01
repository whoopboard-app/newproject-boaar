<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    /**
     * Display a listing of surveys.
     */
    public function index()
    {
        $surveys = Survey::withCount('responses')
            ->latest()
            ->paginate(20);

        return view('surveys.index', compact('surveys'));
    }

    /**
     * Show the form for selecting survey type.
     */
    public function create()
    {
        $surveyTypes = Survey::getTypes();

        return view('surveys.create', compact('surveyTypes'));
    }

    /**
     * Show the builder for a specific survey type.
     */
    public function builder(string $type)
    {
        $validTypes = array_keys(Survey::getTypes());

        if (!in_array($type, $validTypes)) {
            return redirect()->route('surveys.create')
                ->with('error', 'Invalid survey type.');
        }

        $surveyTypes = Survey::getTypes();
        $defaultSettings = Survey::getDefaultSettings($type);

        return view('surveys.builder', compact('type', 'surveyTypes', 'defaultSettings'));
    }

    /**
     * Store a newly created survey.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:nps,csat,open_feedback,quick_poll,idea_pool',
            'status' => 'nullable|in:draft,published,paused',

            // General settings
            'general_settings' => 'nullable|array',
            'general_settings.question' => 'nullable|string|max:500',
            'feedback_form_option' => 'nullable|in:optional,required,dont_show',
            'show_labels' => 'nullable',
            'label_least_likely' => 'nullable|string|max:255',
            'label_most_likely' => 'nullable|string|max:255',
            'label_back_button' => 'nullable|string|max:50',
            'label_skip_button' => 'nullable|string|max:50',
            'label_submit_button' => 'nullable|string|max:50',
            'feedback_question' => 'nullable|string|max:500',
            'feedback_placeholder' => 'nullable|string|max:255',
            'feedback_thank_you' => 'nullable|string|max:500',

            // Trigger settings
            'trigger_type' => 'nullable|in:page_load,page_leave,code_trigger,scroll',
            'trigger_delay_seconds' => 'nullable|integer|min:0',
            'show_survey_timing' => 'nullable|in:first_visit,after_period',
            'show_after_value' => 'nullable|integer|min:1',
            'show_after_unit' => 'nullable|in:hours,days,months',

            // Location settings
            'location_type' => 'nullable|in:all_pages,specific_pages',
            'location_urls' => 'nullable|array',
            'location_urls.*.type' => 'nullable|in:show,hide',
            'location_urls.*.url' => 'nullable|string|max:500',
            'enable_in_subdomain' => 'nullable',

            // Audience settings
            'only_identified_users' => 'nullable',
            'segment_ids' => 'nullable|array',
            'segment_ids.*' => 'nullable|integer|exists:user_segments,id',

            // Frequency settings
            'on_response_action' => 'nullable|in:never_show,show_after',
            'on_response_show_after_value' => 'nullable|integer|min:1',
            'on_response_show_after_unit' => 'nullable|in:hours,days,months',
            'on_close_action' => 'nullable|in:never_show,show_after',
            'on_close_show_after_value' => 'nullable|integer|min:1',
            'on_close_show_after_unit' => 'nullable|in:hours,days,months',
            'skip_if_answered_in_session' => 'nullable',

            // Appearance settings
            'theme' => 'nullable|in:inherit,light,dark,purple,navy',
            'accent_color' => 'nullable|string|max:7',
            'position' => 'nullable|in:bottom_left,bottom_right,bottom_center,center',
            'hide_branding' => 'nullable',
        ]);

        // Set team_id
        $validated['team_id'] = Auth::user()->current_team_id;

        // Set default status if not provided
        if (empty($validated['status'])) {
            $validated['status'] = Survey::STATUS_DRAFT;
        }

        // Handle checkbox fields
        $validated['show_labels'] = $request->has('show_labels');
        $validated['only_identified_users'] = $request->has('only_identified_users');
        $validated['enable_in_subdomain'] = $request->has('enable_in_subdomain');
        $validated['skip_if_answered_in_session'] = $request->has('skip_if_answered_in_session');
        $validated['hide_branding'] = $request->has('hide_branding');

        // Clean up location_urls - remove empty entries
        if (isset($validated['location_urls'])) {
            $validated['location_urls'] = array_filter($validated['location_urls'], function ($item) {
                return !empty($item['url']);
            });
            $validated['location_urls'] = array_values($validated['location_urls']);
        }

        // Merge with default settings
        $defaultSettings = Survey::getDefaultSettings($validated['type']);
        $validated['general_settings'] = array_merge($defaultSettings, $validated['general_settings'] ?? []);

        $survey = Survey::create($validated);

        return redirect()->route('surveys.show', $survey)
            ->with('success', 'Survey created successfully!');
    }

    /**
     * Display the specified survey.
     */
    public function show(Survey $survey)
    {
        $survey->load('responses');
        $statistics = $survey->getStatistics();
        $npsScore = $survey->calculateNpsScore();

        return view('surveys.show', compact('survey', 'statistics', 'npsScore'));
    }

    /**
     * Show the form for editing the specified survey.
     */
    public function edit(Survey $survey)
    {
        $surveyTypes = Survey::getTypes();
        $defaultSettings = $survey->general_settings ?? Survey::getDefaultSettings($survey->type);

        return view('surveys.builder', [
            'survey' => $survey,
            'type' => $survey->type,
            'surveyTypes' => $surveyTypes,
            'defaultSettings' => $defaultSettings,
        ]);
    }

    /**
     * Update the specified survey.
     */
    public function update(Request $request, Survey $survey)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'nullable|in:draft,published,paused',

            // General settings
            'general_settings' => 'nullable|array',
            'general_settings.question' => 'nullable|string|max:500',
            'feedback_form_option' => 'nullable|in:optional,required,dont_show',
            'show_labels' => 'nullable',
            'label_least_likely' => 'nullable|string|max:255',
            'label_most_likely' => 'nullable|string|max:255',
            'label_back_button' => 'nullable|string|max:50',
            'label_skip_button' => 'nullable|string|max:50',
            'label_submit_button' => 'nullable|string|max:50',
            'feedback_question' => 'nullable|string|max:500',
            'feedback_placeholder' => 'nullable|string|max:255',
            'feedback_thank_you' => 'nullable|string|max:500',

            // Trigger settings
            'trigger_type' => 'nullable|in:page_load,page_leave,code_trigger,scroll',
            'trigger_delay_seconds' => 'nullable|integer|min:0',
            'show_survey_timing' => 'nullable|in:first_visit,after_period',
            'show_after_value' => 'nullable|integer|min:1',
            'show_after_unit' => 'nullable|in:hours,days,months',

            // Location settings
            'location_type' => 'nullable|in:all_pages,specific_pages',
            'location_urls' => 'nullable|array',
            'location_urls.*.type' => 'nullable|in:show,hide',
            'location_urls.*.url' => 'nullable|string|max:500',
            'enable_in_subdomain' => 'nullable',

            // Audience settings
            'only_identified_users' => 'nullable',
            'segment_ids' => 'nullable|array',
            'segment_ids.*' => 'nullable|integer|exists:user_segments,id',

            // Frequency settings
            'on_response_action' => 'nullable|in:never_show,show_after',
            'on_response_show_after_value' => 'nullable|integer|min:1',
            'on_response_show_after_unit' => 'nullable|in:hours,days,months',
            'on_close_action' => 'nullable|in:never_show,show_after',
            'on_close_show_after_value' => 'nullable|integer|min:1',
            'on_close_show_after_unit' => 'nullable|in:hours,days,months',
            'skip_if_answered_in_session' => 'nullable',

            // Appearance settings
            'theme' => 'nullable|in:inherit,light,dark,purple,navy',
            'accent_color' => 'nullable|string|max:7',
            'position' => 'nullable|in:bottom_left,bottom_right,bottom_center,center',
            'hide_branding' => 'nullable',
        ]);

        // Handle checkbox fields
        $validated['show_labels'] = $request->has('show_labels');
        $validated['only_identified_users'] = $request->has('only_identified_users');
        $validated['enable_in_subdomain'] = $request->has('enable_in_subdomain');
        $validated['skip_if_answered_in_session'] = $request->has('skip_if_answered_in_session');
        $validated['hide_branding'] = $request->has('hide_branding');

        // Clean up location_urls - remove empty entries
        if (isset($validated['location_urls'])) {
            $validated['location_urls'] = array_filter($validated['location_urls'], function ($item) {
                return !empty($item['url']);
            });
            $validated['location_urls'] = array_values($validated['location_urls']);
        }

        // Merge with existing settings
        if (isset($validated['general_settings'])) {
            $validated['general_settings'] = array_merge(
                $survey->general_settings ?? [],
                $validated['general_settings']
            );
        }

        $survey->update($validated);

        return redirect()->route('surveys.show', $survey)
            ->with('success', 'Survey updated successfully!');
    }

    /**
     * Remove the specified survey.
     */
    public function destroy(Survey $survey)
    {
        $survey->delete();

        return redirect()->route('surveys.index')
            ->with('success', 'Survey deleted successfully!');
    }

    /**
     * Toggle the active status of a survey.
     */
    public function toggleActive(Survey $survey)
    {
        $newStatus = $survey->status === Survey::STATUS_PUBLISHED
            ? Survey::STATUS_PAUSED
            : Survey::STATUS_PUBLISHED;

        $survey->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', 'Survey status updated successfully!');
    }

    /**
     * Display the embed/install code for a survey.
     */
    public function install(Survey $survey)
    {
        return view('surveys.install', compact('survey'));
    }

    /**
     * Public endpoint to submit a survey response.
     */
    public function submitResponse(Request $request, Survey $survey)
    {
        if ($survey->status !== Survey::STATUS_PUBLISHED) {
            return response()->json(['error' => 'Survey is not active'], 400);
        }

        $validated = $request->validate([
            'score' => 'nullable|integer',
            'feedback' => 'nullable|string|max:2000',
            'respondent_id' => 'nullable|string|max:255',
            'respondent_email' => 'nullable|email|max:255',
            'visitor_id' => 'nullable|string|max:255',
            'page_url' => 'nullable|string|max:2000',
            'answers' => 'nullable|array',
        ]);

        // Map incoming fields to database columns
        $responseData = [
            'survey_id' => $survey->id,
            'team_id' => $survey->team_id,
            'score' => $validated['score'] ?? null,
            'feedback' => $validated['feedback'] ?? null,
            'user_identifier' => $validated['respondent_id'] ?? null,
            'email' => $validated['respondent_email'] ?? null,
            'visitor_id' => $validated['visitor_id'] ?? null,
            'page_url' => $validated['page_url'] ?? $request->header('Referer'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'answers' => $validated['answers'] ?? null,
            'status' => 'completed',
            'completed_at' => now(),
        ];

        SurveyResponse::create($responseData);

        $thankYouMessage = $survey->feedback_thank_you
            ?? $survey->general_settings['thank_you_message']
            ?? 'Thank you for your feedback!';

        return response()->json([
            'success' => true,
            'message' => $thankYouMessage,
        ]);
    }

    /**
     * Get survey responses as JSON (for AJAX).
     */
    public function responses(Survey $survey)
    {
        $responses = $survey->responses()
            ->latest()
            ->paginate(50);

        return response()->json($responses);
    }

    /**
     * Public endpoint to get survey configuration for the widget.
     */
    public function getConfig(Survey $survey)
    {
        if ($survey->status !== Survey::STATUS_PUBLISHED) {
            return response()->json(['error' => 'Survey is not active'], 400);
        }

        $settings = $survey->general_settings ?? Survey::getDefaultSettings($survey->type);

        return response()->json([
            'id' => $survey->id,
            'type' => $survey->type,
            'title' => $survey->title,
            'question' => $settings['question'] ?? null,
            'follow_up_question' => $survey->feedback_question ?? $settings['follow_up_question'] ?? null,
            'thank_you_message' => $survey->feedback_thank_you ?? $settings['thank_you_message'] ?? 'Thank you for your feedback!',
            'scale' => $settings['scale'] ?? null,
            'options' => $settings['options'] ?? null,
            'feedback_form_option' => $survey->feedback_form_option ?? 'optional',
            'show_labels' => $survey->show_labels ?? true,
            'label_least_likely' => $survey->label_least_likely ?? $settings['scale']['min_label'] ?? 'Not at all likely',
            'label_most_likely' => $survey->label_most_likely ?? $settings['scale']['max_label'] ?? 'Extremely likely',
            'label_back_button' => $survey->label_back_button ?? 'Back',
            'label_skip_button' => $survey->label_skip_button ?? 'Skip',
            'label_submit_button' => $survey->label_submit_button ?? 'Submit',
            'feedback_placeholder' => $survey->feedback_placeholder ?? 'Share your thoughts...',
            'trigger_type' => $survey->trigger_type ?? 'page_load',
            'trigger_delay_seconds' => $survey->trigger_delay_seconds ?? 0,
            'position' => $survey->position ?? 'bottom_right',
            'theme' => $survey->theme ?? 'light',
            'accent_color' => $survey->accent_color ?? '#6366f1',
            'hide_branding' => $survey->hide_branding ?? false,
            'only_identified_users' => $survey->only_identified_users ?? false,
            'skip_if_answered_in_session' => $survey->skip_if_answered_in_session ?? true,
            'enable_in_subdomain' => $survey->enable_in_subdomain ?? false,
        ]);
    }
}
