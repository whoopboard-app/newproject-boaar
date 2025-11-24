<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory, BelongsToTeam;

    protected $fillable = [
        'team_id',
        'template_id',
        'campaign_id',
        'name',
        'email',
        'company',
        'position',
        'type',
        'text_content',
        'video_url',
        'video_thumbnail',
        'rating',
        'source',
        'status',
        'avatar',
        'custom_data',
    ];

    protected $casts = [
        'custom_data' => 'array',
        'rating' => 'integer',
    ];

    /**
     * Get the template for this testimonial
     */
    public function template()
    {
        return $this->belongsTo(TestimonialTemplate::class, 'template_id');
    }

    /**
     * Get the campaign for this testimonial
     */
    public function campaign()
    {
        return $this->belongsTo(TestimonialCampaign::class, 'campaign_id');
    }

    /**
     * Get the campaign subscriber who submitted this testimonial
     */
    public function campaignSubscriber()
    {
        return $this->hasOne(CampaignSubscriber::class, 'testimonial_id');
    }

    /**
     * Scope for published testimonials
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for pending review testimonials
     */
    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    /**
     * Scope for under review testimonials
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    /**
     * Scope for draft testimonials
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for active testimonials (backward compatibility)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive testimonials (backward compatibility)
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for text testimonials
     */
    public function scopeText($query)
    {
        return $query->where('type', 'text');
    }

    /**
     * Scope for video testimonials
     */
    public function scopeVideo($query)
    {
        return $query->where('type', 'video');
    }
}
