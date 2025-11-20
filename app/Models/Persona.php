<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory, BelongsToTeam;

    protected $fillable = [
        'team_id',
        'name',
        'avatar',
        'role',
        'age_range',
        'location',
        'description',
        'goals',
        'pain_points',
        'behaviors',
        'quote',
        'status',
    ];

    protected $casts = [
        'goals' => 'array',
        'pain_points' => 'array',
        'behaviors' => 'array',
    ];

    /**
     * Relationship: Persona belongs to many User Segments
     */
    public function segments()
    {
        return $this->belongsToMany(UserSegment::class, 'persona_user_segment');
    }

    /**
     * Scope to get only active personas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get inactive personas
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get formatted goals as bullet points
     */
    public function getFormattedGoalsAttribute()
    {
        return $this->goals ? collect($this->goals)->map(fn($goal) => "• $goal")->implode("\n") : '';
    }

    /**
     * Get formatted pain points as bullet points
     */
    public function getFormattedPainPointsAttribute()
    {
        return $this->pain_points ? collect($this->pain_points)->map(fn($point) => "• $point")->implode("\n") : '';
    }

    /**
     * Get formatted behaviors as bullet points
     */
    public function getFormattedBehaviorsAttribute()
    {
        return $this->behaviors ? collect($this->behaviors)->map(fn($behavior) => "• $behavior")->implode("\n") : '';
    }
}
