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
        // Mux video fields
        'mux_upload_id',
        'mux_asset_id',
        'mux_playback_id',
        'mux_status',
        'video_duration',
        'mux_data',
        'rating',
        'source',
        'status',
        'avatar',
        'custom_data',
    ];

    protected $casts = [
        'custom_data' => 'array',
        'mux_data' => 'array',
        'rating' => 'integer',
        'video_duration' => 'integer',
    ];

    /**
     * Check if testimonial has a Mux video ready
     */
    public function hasMuxVideo(): bool
    {
        return $this->type === 'video' && $this->mux_playback_id && $this->mux_status === 'ready';
    }

    /**
     * Get the Mux stream URL for playback
     */
    public function getMuxStreamUrl(): ?string
    {
        if (!$this->mux_playback_id) {
            return null;
        }
        return "https://stream.mux.com/{$this->mux_playback_id}.m3u8";
    }

    /**
     * Get the Mux thumbnail URL
     */
    public function getMuxThumbnailUrl(int $width = 640, int $height = 360): ?string
    {
        if (!$this->mux_playback_id) {
            return null;
        }
        return "https://image.mux.com/{$this->mux_playback_id}/thumbnail.jpg?width={$width}&height={$height}";
    }

    /**
     * Get the Mux animated GIF URL
     */
    public function getMuxGifUrl(int $width = 320): ?string
    {
        if (!$this->mux_playback_id) {
            return null;
        }
        return "https://image.mux.com/{$this->mux_playback_id}/animated.gif?width={$width}";
    }

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
