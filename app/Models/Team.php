<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($team) {
            if (empty($team->slug)) {
                $team->slug = Str::slug($team->name);
            }
        });
    }

    /**
     * Get the owner of the team.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all team members.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'team_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get team invitations.
     */
    public function invitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }

    /**
     * Get pending invitations.
     */
    public function pendingInvitations()
    {
        return $this->hasMany(TeamInvitation::class)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now());
    }

    /**
     * Check if user is owner.
     */
    public function isOwner(User $user)
    {
        return $this->owner_id === $user->id;
    }

    /**
     * Check if user is member.
     */
    public function hasMember(User $user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Get user's role in this team.
     */
    public function getUserRole(User $user)
    {
        if ($this->isOwner($user)) {
            return 'owner';
        }

        $member = $this->members()->where('user_id', $user->id)->first();
        return $member ? $member->pivot->role : null;
    }
}
