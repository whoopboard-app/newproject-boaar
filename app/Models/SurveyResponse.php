<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'survey_id',
        'team_id',
        'visitor_id',
        'user_identifier',
        'email',
        'name',
        'ip_address',
        'user_agent',
        'page_url',
        'score',
        'feedback',
        'answers',
        'category',
        'status',
        'started_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score' => 'integer',
        'answers' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Accessor for respondent_email (alias for email column).
     */
    public function getRespondentEmailAttribute(): ?string
    {
        return $this->email;
    }

    /**
     * Accessor for respondent_id (alias for user_identifier column).
     */
    public function getRespondentIdAttribute(): ?string
    {
        return $this->user_identifier;
    }

    /**
     * Get the survey that owns the response.
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * Determine the NPS category for this response.
     */
    public function getNpsCategoryAttribute(): ?string
    {
        if ($this->score === null) {
            return null;
        }

        if ($this->score >= 9) {
            return 'promoter';
        } elseif ($this->score >= 7) {
            return 'passive';
        } else {
            return 'detractor';
        }
    }

    /**
     * Scope to filter promoters (NPS 9-10).
     */
    public function scopePromoters($query)
    {
        return $query->where('score', '>=', 9);
    }

    /**
     * Scope to filter passives (NPS 7-8).
     */
    public function scopePassives($query)
    {
        return $query->whereBetween('score', [7, 8]);
    }

    /**
     * Scope to filter detractors (NPS 0-6).
     */
    public function scopeDetractors($query)
    {
        return $query->where('score', '<=', 6);
    }
}
