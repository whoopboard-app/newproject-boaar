<?php

namespace App\Http\Controllers;

use App\Mail\TestimonialInviteMail;
use App\Models\TestimonialTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TestimonialTemplateController extends Controller
{
    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        return view('testimonials.templates.create');
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Email Invite
            'enable_email_invite' => 'nullable|boolean',
            'email_title' => 'nullable|string',
            'email_subject' => 'nullable|string',
            'email_content' => 'nullable|string',
            'email_logo' => 'nullable|image|max:2048',
            'email_background_color' => 'nullable|string|max:7',
            'cta_button_color' => 'nullable|string|max:7',
            'cta_button_text' => 'nullable|string',
            'promotional_offer' => 'nullable|string',
            // Form Fields
            'field_full_name' => 'nullable|boolean',
            'field_first_name' => 'nullable|boolean',
            'field_last_name' => 'nullable|boolean',
            'field_email' => 'nullable|boolean',
            'field_company' => 'nullable|boolean',
            'field_position' => 'nullable|boolean',
            'field_social_url' => 'nullable|boolean',
            // Testimonial Type
            'collect_text' => 'nullable|boolean',
            'collect_video' => 'nullable|boolean',
            'collect_rating' => 'nullable|boolean',
            'rating_style' => 'nullable|in:star,smile',
            // Thank You Page
            'enable_thankyou' => 'nullable|boolean',
            'thankyou_title' => 'nullable|string',
            'thankyou_description' => 'nullable|string',
            'thankyou_offer' => 'nullable|string',
            // Appearance
            'page_background_color' => 'nullable|string|max:7',
            'form_background_color' => 'nullable|string|max:7',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['team_id'] = Auth::user()->current_team_id;
        $validated['unique_url'] = Str::slug($validated['name']) . '-' . Str::random(8);

        // Handle email logo upload
        if ($request->hasFile('email_logo')) {
            $validated['email_logo'] = $request->file('email_logo')->store('testimonials/logos', 'public');
        }

        // Convert null checkboxes to false
        $booleanFields = [
            'enable_email_invite', 'field_full_name', 'field_first_name', 'field_last_name',
            'field_email', 'field_company', 'field_position', 'field_social_url',
            'collect_text', 'collect_video', 'collect_rating', 'enable_thankyou'
        ];

        foreach ($booleanFields as $field) {
            if (!isset($validated[$field])) {
                $validated[$field] = false;
            }
        }

        $template = TestimonialTemplate::create($validated);

        // If published (active), redirect to success page with URL and code
        if ($validated['status'] === 'active') {
            return redirect()->route('testimonial-templates.show', $template)
                ->with('success', 'Template published successfully!')
                ->with('show_embed_code', true);
        }

        return redirect()->route('testimonials.index', ['tab' => 'templates'])
            ->with('success', 'Template saved as draft!');
    }

    /**
     * Send a test email for the template.
     */
    public function sendTestEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string',
            'email_title' => 'nullable|string',
            'email_subject' => 'nullable|string',
            'email_content' => 'nullable|string',
            'email_logo' => 'nullable|string',
            'email_logo_data' => 'nullable|string',
            'email_background_color' => 'nullable|string',
            'cta_button_color' => 'nullable|string',
            'cta_button_text' => 'nullable|string',
            'promotional_offer' => 'nullable|string',
            'page_background_color' => 'nullable|string',
            'form_background_color' => 'nullable|string',
            // Form field settings
            'field_full_name' => 'nullable|boolean',
            'field_first_name' => 'nullable|boolean',
            'field_last_name' => 'nullable|boolean',
            'field_email' => 'nullable|boolean',
            'field_company' => 'nullable|boolean',
            'field_position' => 'nullable|boolean',
            'field_social_url' => 'nullable|boolean',
            // Testimonial type settings
            'collect_text' => 'nullable|boolean',
            'collect_video' => 'nullable|boolean',
            'collect_rating' => 'nullable|boolean',
            'rating_style' => 'nullable|string',
        ]);

        try {
            // Create a temporary template object with the form data
            $template = new TestimonialTemplate();
            $template->name = $validated['name'] ?? 'Test Template';
            $template->email_title = $validated['email_title'] ?? 'Your Feedback Matters!';
            $template->email_subject = $validated['email_subject'] ?? "We'd love to hear your feedback!";
            $template->email_content = $validated['email_content'] ?? '<p>Hi there!</p><p>We hope you\'re enjoying our product. We\'d love to hear about your experience!</p>';
            $template->email_logo = $validated['email_logo'] ?? null;
            $template->email_logo_data = $validated['email_logo_data'] ?? null;
            $template->email_background_color = $validated['email_background_color'] ?? '#667eea';
            $template->cta_button_color = $validated['cta_button_color'] ?? '#667eea';
            $template->cta_button_text = $validated['cta_button_text'] ?? 'Share Your Feedback';
            $template->promotional_offer = $validated['promotional_offer'] ?? null;
            $template->page_background_color = $validated['page_background_color'] ?? '#667eea';
            $template->form_background_color = $validated['form_background_color'] ?? '#667eea';
            $template->unique_url = 'test-preview';

            // Form field settings
            $template->field_full_name = $validated['field_full_name'] ?? false;
            $template->field_first_name = $validated['field_first_name'] ?? false;
            $template->field_last_name = $validated['field_last_name'] ?? false;
            $template->field_email = $validated['field_email'] ?? true;
            $template->field_company = $validated['field_company'] ?? false;
            $template->field_position = $validated['field_position'] ?? false;
            $template->field_social_url = $validated['field_social_url'] ?? false;

            // Testimonial type settings
            $template->collect_text = $validated['collect_text'] ?? true;
            $template->collect_video = $validated['collect_video'] ?? false;
            $template->collect_rating = $validated['collect_rating'] ?? true;
            $template->rating_style = $validated['rating_style'] ?? 'star';

            // Store template data in session for test preview
            session()->put('test_template', $template->toArray());

            // Send test email immediately (not queued)
            Mail::to($validated['email'])->send(new TestimonialInviteMail($template, true));

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully! Check your inbox.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified template.
     */
    public function show(TestimonialTemplate $template)
    {
        $template->load('testimonials');
        return view('testimonials.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(TestimonialTemplate $template)
    {
        return view('testimonials.templates.edit', compact('template'));
    }

    /**
     * Update the specified template.
     */
    public function update(Request $request, TestimonialTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'welcome_message' => 'nullable|string',
            'thank_you_message' => 'nullable|string',
            'collect_rating' => 'boolean',
            'collect_video' => 'boolean',
            'collect_text' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $template->update($validated);

        return redirect()->route('testimonial-templates.show', $template)
            ->with('success', 'Template updated successfully!');
    }

    /**
     * Remove the specified template.
     */
    public function destroy(TestimonialTemplate $template)
    {
        $template->delete();

        return redirect()->route('testimonials.index', ['tab' => 'templates'])
            ->with('success', 'Template deleted successfully!');
    }
}
