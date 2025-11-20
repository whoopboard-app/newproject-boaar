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
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'age_range' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'quote' => 'nullable|string',
            'status' => 'required|in:active,inactive,draft',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('personas', 'public');
        }

        // Handle array fields from form (they come as individual array items)
        $validated['goals'] = $request->has('goals') ? (is_array($request->goals) ? $request->goals : []) : [];
        $validated['pain_points'] = $request->has('pain_points') ? (is_array($request->pain_points) ? $request->pain_points : []) : [];
        $validated['behaviors'] = $request->has('behaviors') ? (is_array($request->behaviors) ? $request->behaviors : []) : [];

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
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'age_range' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'quote' => 'nullable|string',
            'status' => 'required|in:active,inactive,draft',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        $validated['goals'] = $request->has('goals') ? (is_array($request->goals) ? $request->goals : []) : [];
        $validated['pain_points'] = $request->has('pain_points') ? (is_array($request->pain_points) ? $request->pain_points : []) : [];
        $validated['behaviors'] = $request->has('behaviors') ? (is_array($request->behaviors) ? $request->behaviors : []) : [];

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
