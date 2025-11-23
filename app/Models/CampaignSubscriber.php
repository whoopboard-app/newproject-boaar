<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CampaignSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'tracking_token',
        'email_sent',
        'sent_at',
        'email_opened',
        'opened_at',
        'link_clicked',
        'clicked_at',
        'testimonial_submitted',
        'testimonial_id',
    ];

    protected $casts = [
        'email_sent' => 'boolean',
        'sent_at' => 'datetime',
        'email_opened' => 'boolean',
        'opened_at' => 'datetime',
        'link_clicked' => 'boolean',
        'clicked_at' => 'datetime',
        'testimonial_submitted' => 'boolean',
    ];

    /**
     * Boot method to auto-generate tracking token
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($campaignSubscriber) {
            if (empty($campaignSubscriber->tracking_token)) {
                $campaignSubscriber->tracking_token = Str::random(32);
            }
        });
    }

    /**
     * Get the campaign for this record
     */
    public function campaign()
    {
        return $this->belongsTo(TestimonialCampaign::class, 'campaign_id');
    }

    /**
     * Get the subscriber for this record
     */
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class, 'subscriber_id');
    }

    /**
     * Get the testimonial submitted by this subscriber
     */
    public function testimonial()
    {
        return $this->belongsTo(Testimonial::class, 'testimonial_id');
    }

    /**
     * Mark email as sent
     */
    public function markAsSent()
    {
        $this->email_sent = true;
        $this->sent_at = now();
        $this->save();
    }

    /**
     * Mark email as opened
     */
    public function markAsOpened()
    {
        if (!$this->email_opened) {
            $this->email_opened = true;
            $this->opened_at = now();
            $this->save();

            // Update campaign metrics
            $this->campaign->updateMetrics();
        }
    }

    /**
     * Mark link as clicked
     */
    public function markAsClicked()
    {
        if (!$this->link_clicked) {
            $this->link_clicked = true;
            $this->clicked_at = now();
            $this->save();

            // Update campaign metrics
            $this->campaign->updateMetrics();
        }
    }

    /**
     * Mark testimonial as submitted
     */
    public function markTestimonialSubmitted($testimonialId)
    {
        $this->testimonial_submitted = true;
        $this->testimonial_id = $testimonialId;
        $this->save();

        // Update campaign metrics
        $this->campaign->updateMetrics();
    }
}
