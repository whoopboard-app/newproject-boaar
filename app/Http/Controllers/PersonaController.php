<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\UserSegment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonaController extends Controller
{
    /**
     * Display a listing of personas.
     */
    public function index()
    {
        $personas = Persona::with('segments')->latest()->paginate(12);
        return view('personas.index', compact('personas'));
    }

    /**
     * Show the form for creating a new persona.
     */
    public function create()
    {
        $segments = UserSegment::where('status', 'active')->get();
        return view('personas.create', compact('segments'));
    }

    /**
     * Store a newly created persona.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Persona Header
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,avif|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif|max:2048',
            // Identity
            'age_range' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'income' => 'nullable|string|max:255',
            // Behavior
            'workflow' => 'nullable|string',
            'buying_behavior' => 'nullable|string',
            // Product Fit
            'journey_stage' => 'nullable|string|max:255',
            // Persona Summary Card
            'bio' => 'nullable|string',
            'quote' => 'nullable|string',
            // Legacy fields
            'role' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('personas', 'public');
        }

        // Handle array fields from form (they come as individual array items)
        $validated['segmentation_tags'] = $request->has('segmentation_tags') ? (is_array($request->segmentation_tags) ? $request->segmentation_tags : []) : [];
        $validated['goals'] = $request->has('goals') ? (is_array($request->goals) ? $request->goals : []) : [];
        $validated['motivations'] = $request->has('motivations') ? (is_array($request->motivations) ? $request->motivations : []) : [];
        $validated['pain_points'] = $request->has('pain_points') ? (is_array($request->pain_points) ? $request->pain_points : []) : [];
        $validated['frustrations'] = $request->has('frustrations') ? (is_array($request->frustrations) ? $request->frustrations : []) : [];
        $validated['behaviors'] = $request->has('behaviors') ? (is_array($request->behaviors) ? $request->behaviors : []) : [];
        $validated['device_usage'] = $request->has('device_usage') ? (is_array($request->device_usage) ? $request->device_usage : []) : [];
        $validated['channel_preference'] = $request->has('channel_preference') ? (is_array($request->channel_preference) ? $request->channel_preference : []) : [];
        $validated['modules_interacted'] = $request->has('modules_interacted') ? (is_array($request->modules_interacted) ? $request->modules_interacted : []) : [];
        $validated['weighted_impact_scores'] = $request->has('weighted_impact_scores') ? (is_array($request->weighted_impact_scores) ? $request->weighted_impact_scores : []) : [];

        // Create persona
        $persona = Persona::create($validated);

        // Attach segments
        if ($request->has('segments') && is_array($request->segments)) {
            $persona->segments()->attach($request->segments);
        }

        return redirect()->route('personas.index')
            ->with('success', 'Persona created successfully!');
    }

    /**
     * Display the specified persona.
     */
    public function show(Persona $persona)
    {
        $persona->load('segments');
        return view('personas.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified persona.
     */
    public function edit(Persona $persona)
    {
        $segments = UserSegment::where('status', 'active')->get();
        return view('personas.edit', compact('persona', 'segments'));
    }

    /**
     * Update the specified persona.
     */
    public function update(Request $request, Persona $persona)
    {
        $validated = $request->validate([
            // Persona Header
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,avif|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif|max:2048',
            // Identity
            'age_range' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'income' => 'nullable|string|max:255',
            // Behavior
            'workflow' => 'nullable|string',
            'buying_behavior' => 'nullable|string',
            // Product Fit
            'journey_stage' => 'nullable|string|max:255',
            // Persona Summary Card
            'bio' => 'nullable|string',
            'quote' => 'nullable|string',
            // Legacy fields
            'role' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Handle avatar removal
        if ($request->input('remove_avatar') == '1') {
            if ($persona->avatar) {
                Storage::disk('public')->delete($persona->avatar);
            }
            $validated['avatar'] = null;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($persona->avatar) {
                Storage::disk('public')->delete($persona->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('personas', 'public');
        }

        // Handle array fields from form (they come as individual array items)
        $validated['segmentation_tags'] = $request->has('segmentation_tags') ? (is_array($request->segmentation_tags) ? $request->segmentation_tags : []) : [];
        $validated['goals'] = $request->has('goals') ? (is_array($request->goals) ? $request->goals : []) : [];
        $validated['motivations'] = $request->has('motivations') ? (is_array($request->motivations) ? $request->motivations : []) : [];
        $validated['pain_points'] = $request->has('pain_points') ? (is_array($request->pain_points) ? $request->pain_points : []) : [];
        $validated['frustrations'] = $request->has('frustrations') ? (is_array($request->frustrations) ? $request->frustrations : []) : [];
        $validated['behaviors'] = $request->has('behaviors') ? (is_array($request->behaviors) ? $request->behaviors : []) : [];
        $validated['device_usage'] = $request->has('device_usage') ? (is_array($request->device_usage) ? $request->device_usage : []) : [];
        $validated['channel_preference'] = $request->has('channel_preference') ? (is_array($request->channel_preference) ? $request->channel_preference : []) : [];
        $validated['modules_interacted'] = $request->has('modules_interacted') ? (is_array($request->modules_interacted) ? $request->modules_interacted : []) : [];
        $validated['weighted_impact_scores'] = $request->has('weighted_impact_scores') ? (is_array($request->weighted_impact_scores) ? $request->weighted_impact_scores : []) : [];

        // Update persona
        $persona->update($validated);

        // Sync segments
        if ($request->has('segments') && is_array($request->segments)) {
            $persona->segments()->sync($request->segments);
        } else {
            $persona->segments()->sync([]);
        }

        return redirect()->route('personas.index')
            ->with('success', 'Persona updated successfully!');
    }

    /**
     * Remove the specified persona.
     */
    public function destroy(Persona $persona)
    {
        // Delete avatar if exists
        if ($persona->avatar) {
            Storage::disk('public')->delete($persona->avatar);
        }

        $persona->delete();

        return redirect()->route('personas.index')
            ->with('success', 'Persona deleted successfully!');
    }
}
