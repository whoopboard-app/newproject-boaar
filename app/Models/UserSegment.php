<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSegment extends Model
{
    protected $fillable = [
        'name',
        'status',
        'description',
        'revenue_ranges',
        'locations',
        'age_ranges',
        'genders',
        'languages',
        'user_types',
        'plan_types',
        'engagement_levels',
        'usage_frequencies',
    ];

    protected $casts = [
        'revenue_ranges' => 'array',
        'locations' => 'array',
        'age_ranges' => 'array',
        'genders' => 'array',
        'languages' => 'array',
        'user_types' => 'array',
        'plan_types' => 'array',
        'engagement_levels' => 'array',
        'usage_frequencies' => 'array',
    ];

    /**
     * Relationship: User Segment has many Subscribers
     */
    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class, 'subscriber_user_segment');
    }
}
