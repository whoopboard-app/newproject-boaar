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
     * Scope for active testimonials
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive testimonials
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for draft testimonials
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
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
