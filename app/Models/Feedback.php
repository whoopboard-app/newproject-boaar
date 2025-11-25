<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory, BelongsToTeam;

    protected $table = 'feedbacks';

    protected $fillable = [
        'team_id',
        'idea',
        'feedback_category_id',
        'value_description',
        'roadmap_id',
        'name',
        'email',
        'login_access_enabled',
        'is_public',
        'show_in_roadmap',
        'tags',
        'image',
        'persona_id',
        'public_user_id',
        'source',
        'verification_token',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'login_access_enabled' => 'boolean',
        'is_public' => 'boolean',
        'show_in_roadmap' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the feedback category.
     */
    public function category()
    {
        return $this->belongsTo(FeedbackCategory::class, 'feedback_category_id');
    }

    /**
     * Get the roadmap status.
     */
    public function roadmap()
    {
        return $this->belongsTo(Roadmap::class);
    }

    /**
     * Get the persona.
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    /**
     * Get all comments for the feedback.
     */
    public function comments()
    {
        return $this->hasMany(FeedbackComment::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get public comments only.
     */
    public function publicComments()
    {
        return $this->hasMany(FeedbackComment::class)->where('is_internal', false)->orderBy('created_at', 'asc');
    }

    /**
     * Get internal comments only.
     */
    public function internalComments()
    {
        return $this->hasMany(FeedbackComment::class)->where('is_internal', true)->orderBy('created_at', 'asc');
    }

    /**
     * Get the public user who submitted this feedback.
     */
    public function publicUser()
    {
        return $this->belongsTo(PublicUser::class);
    }

    /**
     * Get all votes for this feedback.
     */
    public function votes()
    {
        return $this->hasMany(FeedbackVote::class);
    }

    /**
     * Get the vote count for this feedback.
     */
    public function voteCount()
    {
        return $this->votes()->count();
    }

    /**
     * Check if a user has voted for this feedback.
     */
    public function hasVotedBy($publicUserId = null, $email = null, $ip = null)
    {
        return FeedbackVote::hasVoted($this->id, $publicUserId, $email, $ip);
    }

    /**
     * Generate verification token for feedback submission.
     */
    public function generateVerificationToken()
    {
        $this->verification_token = \Illuminate\Support\Str::random(64);
        $this->save();
        return $this->verification_token;
    }

    /**
     * Mark feedback as verified.
     */
    public function markAsVerified()
    {
        $this->is_verified = true;
        $this->verified_at = now();
        $this->verification_token = null;
        $this->save();
    }
}
