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
        // Persona Header
        'name',
        'avatar',
        'tagline',
        'segmentation_tags',
        'status',
        // Identity
        'age_range',
        'gender',
        'location',
        'occupation',
        'education',
        'income',
        'role', // Legacy field for job title
        'description', // Legacy field
        // Goals & Pain Points
        'goals',
        'motivations',
        'pain_points',
        'frustrations',
        // Behavior
        'behaviors',
        'device_usage',
        'channel_preference',
        'workflow',
        'buying_behavior',
        // Product Fit
        'journey_stage',
        'modules_interacted',
        'weighted_impact_scores',
        // Persona Summary Card
        'bio',
        'quote',
    ];

    protected $casts = [
        'segmentation_tags' => 'array',
        'goals' => 'array',
        'motivations' => 'array',
        'pain_points' => 'array',
        'frustrations' => 'array',
        'behaviors' => 'array',
        'device_usage' => 'array',
        'channel_preference' => 'array',
        'modules_interacted' => 'array',
        'weighted_impact_scores' => 'array',
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
