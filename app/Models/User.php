<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'current_team_id',
        'role',
        'avatar',
        'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get teams owned by the user.
     */
    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    /**
     * Get all teams user belongs to.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get current team.
     */
    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    /**
     * Switch to a different team.
     */
    public function switchTeam(Team $team)
    {
        if ($this->belongsToTeam($team)) {
            $this->update(['current_team_id' => $team->id]);
            return true;
        }
        return false;
    }

    /**
     * Check if user belongs to team.
     */
    public function belongsToTeam(Team $team)
    {
        return $this->teams()->where('team_id', $team->id)->exists() || $this->ownsTeam($team);
    }

    /**
     * Check if user owns team.
     */
    public function ownsTeam(Team $team)
    {
        return $this->id === $team->owner_id;
    }

    /**
     * Get role in current team.
     */
    public function roleInTeam(Team $team = null)
    {
        $team = $team ?? $this->currentTeam;

        if (!$team) {
            return null;
        }

        if ($this->ownsTeam($team)) {
            return 'owner';
        }

        $membership = $this->teams()->where('team_id', $team->id)->first();
        return $membership ? $membership->pivot->role : null;
    }

    /**
     * Check if user has permission.
     */
    public function hasPermission($permission, Team $team = null)
    {
        $role = $this->roleInTeam($team);

        $permissions = [
            'owner' => ['*'], // All permissions
            'admin' => ['*'], // All permissions
            'moderator' => ['view', 'create', 'edit'], // No delete
            'idea_submitter' => ['view', 'create_feedback'], // Only create feedback
            'viewer' => ['view'], // Read only
        ];

        if (!isset($permissions[$role])) {
            return false;
        }

        return in_array('*', $permissions[$role]) || in_array($permission, $permissions[$role]);
    }

    /**
     * Check if user can delete.
     */
    public function canDelete()
    {
        return in_array($this->roleInTeam(), ['owner', 'admin']);
    }

    /**
     * Check if user can edit.
     */
    public function canEdit()
    {
        return in_array($this->roleInTeam(), ['owner', 'admin', 'moderator']);
    }

    /**
     * Check if user is in read-only mode (Viewer).
     */
    public function isReadOnly()
    {
        return $this->roleInTeam() === 'viewer';
    }

    /**
     * Check if user can create content (changelog, knowledge board, feedback).
     */
    public function canCreateContent()
    {
        return in_array($this->roleInTeam(), ['owner', 'admin', 'moderator', 'idea_submitter']);
    }

    /**
     * Check if user can create/edit feedback specifically.
     */
    public function canManageFeedback()
    {
        return in_array($this->roleInTeam(), ['owner', 'admin', 'moderator', 'idea_submitter']);
    }

    /**
     * Check if user can access app settings.
     */
    public function canAccessAppSettings()
    {
        return in_array($this->roleInTeam(), ['owner', 'admin', 'moderator']);
    }

    /**
     * Check if user can create changelog/knowledge board (not idea_submitter).
     */
    public function canManageChangelogAndKnowledge()
    {
        return in_array($this->roleInTeam(), ['owner', 'admin', 'moderator']);
    }

    /**
     * Get avatar URL or generate initials.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Generate avatar with initials
        $initials = strtoupper(substr($this->name, 0, 2));
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=random";
    }
}
