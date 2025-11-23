<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimonialCampaign extends Model
{
    use HasFactory, BelongsToTeam;

    protected $fillable = [
        'team_id',
        'template_id',
        'name',
        'objective',
        'status',
        'delivery_type',
        'scheduled_at',
        'sent_at',
        'subscribers_sent',
        'emails_opened',
        'emails_clicked',
        'testimonials_collected',
        'average_rating',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'subscribers_sent' => 'integer',
        'emails_opened' => 'integer',
        'emails_clicked' => 'integer',
        'testimonials_collected' => 'integer',
        'average_rating' => 'decimal:2',
    ];

    /**
     * Get the template for this campaign
     */
    public function template()
    {
        return $this->belongsTo(TestimonialTemplate::class, 'template_id');
    }

    /**
     * Get the testimonials collected from this campaign
     */
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'campaign_id');
    }

    /**
     * Get the campaign subscribers
     */
    public function campaignSubscribers()
    {
        return $this->hasMany(CampaignSubscriber::class, 'campaign_id');
    }

    /**
     * Get the subscribers associated with this campaign through the pivot table
     */
    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class, 'campaign_subscribers', 'campaign_id', 'subscriber_id')
            ->withPivot('tracking_token', 'email_sent', 'sent_at', 'email_opened', 'opened_at', 'link_clicked', 'clicked_at', 'testimonial_submitted', 'testimonial_id')
            ->withTimestamps();
    }

    /**
     * Scope for active campaigns
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for draft campaigns
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for scheduled campaigns
     */
    public function scopeScheduled($query)
    {
        return $query->where('delivery_type', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->whereNull('sent_at');
    }

    /**
     * Scope for sent campaigns
     */
    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at');
    }

    /**
     * Check if campaign is ready to send
     */
    public function isReadyToSend()
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->delivery_type === 'scheduled') {
            return $this->scheduled_at && $this->scheduled_at->isPast() && !$this->sent_at;
        }

        return true;
    }

    /**
     * Mark campaign as sent
     */
    public function markAsSent()
    {
        $this->sent_at = now();
        $this->save();
    }

    /**
     * Update campaign metrics
     */
    public function updateMetrics()
    {
        $this->subscribers_sent = $this->campaignSubscribers()->where('email_sent', true)->count();
        $this->emails_opened = $this->campaignSubscribers()->where('email_opened', true)->count();
        $this->emails_clicked = $this->campaignSubscribers()->where('link_clicked', true)->count();
        $this->testimonials_collected = $this->testimonials()->count();
        $this->average_rating = $this->testimonials()->avg('rating');
        $this->save();
    }
}
