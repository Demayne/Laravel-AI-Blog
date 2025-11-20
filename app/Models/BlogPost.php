<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_posts';

    // Allow mass assignment for these fields (timestamps managed automatically)
    protected $fillable = ['title', 'content', 'category_id', 'image'];

    /**
     * Relationship: Each blog post belongs to one category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Each blog post may have multiple tags.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Accessor: Return a short excerpt from the content.
     */
    public function getExcerptAttribute()
    {
        return substr($this->content, 0, 100) . '...';
    }

    /**
     * Accessor: Format the created date as "Day Month Year"
     * Example: "12 June 2025"
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('j F Y') : null;
    }
}
