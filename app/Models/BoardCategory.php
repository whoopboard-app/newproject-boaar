<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardCategory extends Model
{
    protected $fillable = [
        'knowledge_board_id',
        'category_name',
        'category_icon',
        'short_description',
        'is_parent',
        'parent_category_id',
        'order',
        'status',
    ];

    protected $casts = [
        'is_parent' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            // Auto-increment order
            if (empty($category->order)) {
                $maxOrder = static::where('knowledge_board_id', $category->knowledge_board_id)->max('order');
                $category->order = $maxOrder ? $maxOrder + 1 : 1;
            }

            // Auto-generate icon if not provided
            if (empty($category->category_icon)) {
                $category->category_icon = 'ti ti-folder';
            }
        });
    }

    public function knowledgeBoard()
    {
        return $this->belongsTo(KnowledgeBoard::class);
    }

    public function parentCategory()
    {
        return $this->belongsTo(BoardCategory::class, 'parent_category_id');
    }

    public function childCategories()
    {
        return $this->hasMany(BoardCategory::class, 'parent_category_id');
    }
}
