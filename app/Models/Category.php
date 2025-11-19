<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from name
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }

            // Auto-assign random color if not provided
            if (empty($category->color)) {
                $category->color = self::generateRandomColor();
            }
        });
    }

    /**
     * Generate a random hex color code
     */
    private static function generateRandomColor()
    {
        $colors = [
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8',
            '#F7DC6F', '#BB8FCE', '#85C1E2', '#F8B739', '#52BE80',
            '#EC7063', '#AF7AC5', '#5DADE2', '#48C9B0', '#F4D03F',
            '#EB984E', '#DC7633', '#A569BD', '#5499C7', '#16A085'
        ];

        // Get already used colors
        $usedColors = self::pluck('color')->toArray();

        // Get available colors
        $availableColors = array_diff($colors, $usedColors);

        // If all colors are used, return a random color from the full palette
        if (empty($availableColors)) {
            return $colors[array_rand($colors)];
        }

        // Return a random available color
        return $availableColors[array_rand($availableColors)];
    }
}
