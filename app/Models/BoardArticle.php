<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardArticle extends Model
{
    protected $fillable = [
        'knowledge_board_id',
        'board_category_id',
        'article_title',
        'detailed_post',
        'cover_image',
        'tags',
        'author_id',
        'display_as_popular',
        'status',
    ];

    protected $casts = [
        'display_as_popular' => 'boolean',
        'tags' => 'array',
    ];

    public function knowledgeBoard()
    {
        return $this->belongsTo(KnowledgeBoard::class);
    }

    public function boardCategory()
    {
        return $this->belongsTo(BoardCategory::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function changelogCategories()
    {
        return $this->belongsToMany(Category::class, 'board_article_changelog_category');
    }
}
