<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;

class TeamInvitationController extends Controller
{
    /**
     * Show team management page with invitations.
     */
    public function index()
    {
        $team = Auth::user()->currentTeam;

        if (!$team) {
            return redirect()->route('dashboard')->with('error', 'No team selected.');
        }

        // Check if user can invite (owner or admin)
        if (!in_array(Auth::user()->roleInTeam(), ['owner', 'admin'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to manage team members.');
        }

        $members = $team->members()->withPivot('role')->get();
        $pendingInvitations = $team->pendingInvitations()->with('inviter')->get();

        return view('team.manage', compact('team', 'members', 'pendingInvitations'));
    }

    /**
     * Send team invitation.
     */
    public function invite(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'in:admin,moderator,idea_submitter,viewer'],
        ]);

        $team = Auth::user()->currentTeam;

        if (!$team) {
            return back()->with('error', 'No team selected.');
        }

        // Check if user can invite
        if (!in_array(Auth::user()->roleInTeam(), ['owner', 'admin'])) {
            return back()->with('error', 'You do not have permission to invite members.');
        }

        // Check if email already exists in team
        if ($team->members()->where('email', $validated['email'])->exists()) {
            return back()->with('error', 'This email is already a team member.');
        }

        // Check if invitation already exists
        $existingInvitation = TeamInvitation::where('team_id', $team->id)
            ->where('email', $validated['email'])
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            return back()->with('error', 'An invitation has already been sent to this email.');
        }

        // Create invitation
        $invitation = TeamInvitation::create([
            'team_id' => $team->id,
            'invited_by' => Auth::id(),
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // Send email notification
        Notification::route('mail', $validated['email'])
            ->notify(new TeamInvitationNotification($invitation));

        return back()->with('success', 'Invitation sent successfully!');
    }

    /**
     * Show invitation accept page.
     */
    public function accept($token)
    {
        $invitation = TeamInvitation::where('token', $token)->firstOrFail();

        if (!$invitation->isValid()) {
            return redirect()->route('login')->with('error', 'This invitation has expired or has already been used.');
        }

        // Check if user already exists
        $existingUser = User::where('email', $invitation->email)->first();

        if ($existingUser) {
            // User exists, just add them to the team
            $this->addUserToTeam($existingUser, $invitation);
            $invitation->markAsAccepted();

            return redirect()->route('login')->with('success', 'You have been added to the team! Please login to continue.');
        }

        // Show signup form for new users
        return view('auth.invitation-signup', compact('invitation'));
    }

    /**
     * Process invitation signup.
     */
    public function acceptSignup(Request $request, $token)
    {
        $invitation = TeamInvitation::where('token', $token)->firstOrFail();

        if (!$invitation->isValid()) {
            return redirect()->route('login')->with('error', 'This invitation has expired or has already been used.');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'timezone' => ['nullable', 'string'],
        ]);

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Create user with email already verified (since they received the invitation via email)
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $invitation->email,
            'password' => Hash::make($validated['password']),
            'avatar' => $avatarPath,
            'timezone' => $validated['timezone'] ?? null,
            'current_team_id' => $invitation->team_id,
            'email_verified_at' => now(), // Auto-verify email for invited users
        ]);

        // Add user to team
        $this->addUserToTeam($user, $invitation);

        // Mark invitation as accepted
        $invitation->markAsAccepted();

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome to the team!');
    }

    /**
     * Cancel/delete invitation.
     */
    public function cancel(TeamInvitation $invitation)
    {
        $team = Auth::user()->currentTeam;

        if ($invitation->team_id !== $team->id) {
            abort(403);
        }

        // Check if user can cancel invitations
        if (!in_array(Auth::user()->roleInTeam(), ['owner', 'admin'])) {
            return back()->with('error', 'You do not have permission to cancel invitations.');
        }

        $invitation->delete();

        return back()->with('success', 'Invitation cancelled successfully.');
    }

    /**
     * Remove team member.
     */
    public function removeMember(Request $request, User $user)
    {
        $team = Auth::user()->currentTeam;

        // Check if user can remove members
        if (!in_array(Auth::user()->roleInTeam(), ['owner', 'admin'])) {
            return back()->with('error', 'You do not have permission to remove members.');
        }

        // Cannot remove owner
        if ($team->isOwner($user)) {
            return back()->with('error', 'Cannot remove team owner.');
        }

        // Cannot remove yourself
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Cannot remove yourself from the team.');
        }

        $team->members()->detach($user->id);

        return back()->with('success', 'Member removed successfully.');
    }

    /**
     * Update member role.
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,moderator,idea_submitter,viewer'],
        ]);

        $team = Auth::user()->currentTeam;

        // Check if user can update roles
        if (!in_array(Auth::user()->roleInTeam(), ['owner', 'admin'])) {
            return back()->with('error', 'You do not have permission to update roles.');
        }

        // Cannot change owner role
        if ($team->isOwner($user)) {
            return back()->with('error', 'Cannot change owner role.');
        }

        $team->members()->updateExistingPivot($user->id, ['role' => $validated['role']]);

        return back()->with('success', 'Role updated successfully.');
    }

    /**
     * Helper: Add user to team.
     */
    private function addUserToTeam(User $user, TeamInvitation $invitation)
    {
        $team = $invitation->team;

        $team->members()->attach($user->id, [
            'role' => $invitation->role,
        ]);

        // Set as current team if user doesn't have one
        if (!$user->current_team_id) {
            $user->update(['current_team_id' => $team->id]);
        }
    }
}
