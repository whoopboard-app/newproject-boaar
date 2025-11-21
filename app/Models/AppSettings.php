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
    ];

    /**
     * Get the team that owns the settings.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
