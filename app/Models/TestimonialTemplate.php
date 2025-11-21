<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimonialTemplate extends Model
{
    use HasFactory, BelongsToTeam;

    protected $fillable = [
        'team_id',
        'name',
        'unique_url',
        // Email Invite
        'enable_email_invite',
        'email_subject',
        'email_content',
        'email_logo',
        'promotional_offer',
        // Form Fields
        'field_full_name',
        'field_first_name',
        'field_last_name',
        'field_email',
        'field_company',
        'field_position',
        'field_social_url',
        // Testimonial Type
        'collect_text',
        'collect_video',
        'collect_rating',
        'rating_style',
        // Thank You Page
        'enable_thankyou',
        'thankyou_title',
        'thankyou_description',
        'thankyou_offer',
        // Legacy fields
        'welcome_message',
        'thank_you_message',
        'custom_fields',
        'status',
    ];

    protected $casts = [
        'enable_email_invite' => 'boolean',
        'field_full_name' => 'boolean',
        'field_first_name' => 'boolean',
        'field_last_name' => 'boolean',
        'field_email' => 'boolean',
        'field_company' => 'boolean',
        'field_position' => 'boolean',
        'field_social_url' => 'boolean',
        'collect_rating' => 'boolean',
        'collect_video' => 'boolean',
        'collect_text' => 'boolean',
        'enable_thankyou' => 'boolean',
        'custom_fields' => 'array',
    ];

    /**
     * Get all testimonials for this template
     */
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'template_id');
    }

    /**
     * Get count of submissions
     */
    public function getSubmissionCountAttribute()
    {
        return $this->testimonials()->count();
    }

    /**
     * Scope for active templates
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive templates
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
