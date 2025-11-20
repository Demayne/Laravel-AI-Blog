<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = ['description', 'slug'];

    public static function boot()
    {
        parent::boot();

        // Automatically generate slug from description if not manually set
        static::saving(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = str_replace(' ', '-', strtolower($tag->description));
            }
        });
    }

    public function blogPosts()
    {
        return $this->belongsToMany(BlogPost::class);
    }

    public function getSlugAttribute($value)
    {
        return $value ?? str_replace(' ', '-', strtolower($this->description));
    }
}
