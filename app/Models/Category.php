<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name', 'slug'];

    public static function boot()
    {
        parent::boot();

        // Automatically generate slug from name if not manually set
        static::saving(function ($category) {
            if (empty($category->slug)) {
                $category->slug = str_replace(' ', '-', strtolower($category->name));
            }
        });
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function getSlugAttribute($value)
    {
        return $value ?? str_replace(' ', '-', strtolower($this->name));
    }
}
