<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'title', 'slug',
        'category_id', 'description',
        'content', 'status',
        'images', 'thumbnail',
        'published_at', 'author_id'
    ];

    protected $casts = [
        'images' => 'array',
        'published_at' => 'datetime',
    ];

    public const DRAFT_STATUS = 1;
    public const PUBLISHED_STATUS = 2;

    /**
     * Get the article category that owns the article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }
}
