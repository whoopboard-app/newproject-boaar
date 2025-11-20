<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait BelongsToTeam
{
    /**
     * Boot the trait.
     */
    protected static function bootBelongsToTeam()
    {
        // Automatically add team_id when creating
        static::creating(function (Model $model) {
            if (Auth::check() && Auth::user()->current_team_id && !$model->team_id) {
                $model->team_id = Auth::user()->current_team_id;
            }
        });

        // Automatically scope all queries to current team
        static::addGlobalScope('team', function (Builder $builder) {
            if (Auth::check() && Auth::user()->current_team_id) {
                $builder->where($builder->getQuery()->from . '.team_id', Auth::user()->current_team_id);
            }
        });
    }

    /**
     * Get the team that owns the model.
     */
    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}
