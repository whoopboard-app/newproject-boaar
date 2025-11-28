<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    use HasFactory, BelongsToTeam;

    protected $fillable = [
        'team_id',
        'logo',
        'product_name',
        'website_url',
        'unique_url',
        'subdomain_url',
        'block_search_indexing',
        'site_visibility',
        'maintenance_mode',
        'maintenance_scheduled_at',
        'maintenance_ends_at',
        'maintenance_message',
    ];

    protected $casts = [
        'block_search_indexing' => 'boolean',
        'maintenance_mode' => 'boolean',
        'maintenance_scheduled_at' => 'datetime',
        'maintenance_ends_at' => 'datetime',
    ];

    /**
     * Check if site is in maintenance mode.
     */
    public function isInMaintenanceMode(): bool
    {
        if (!$this->maintenance_mode) {
            return false;
        }

        // If scheduled, check if current time is within the maintenance window
        if ($this->maintenance_scheduled_at && $this->maintenance_ends_at) {
            $now = now();
            return $now->between($this->maintenance_scheduled_at, $this->maintenance_ends_at);
        }

        return $this->maintenance_mode;
    }

    /**
     * Check if site is private.
     */
    public function isPrivate(): bool
    {
        return $this->site_visibility === 'private';
    }

    /**
     * Get site access invites.
     */
    public function siteAccessInvites()
    {
        return $this->hasMany(SiteAccessInvite::class, 'team_id', 'team_id');
    }

    /**
     * Get the team that owns the settings.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
