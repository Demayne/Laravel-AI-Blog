<?php

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getSidebarData')) {
    function getSidebarData() {
        return Cache::remember('sidebar_data', 3600, function () {
            return [
                'allCategories' => Category::all(),
                'allTags' => Tag::all(),
            ];
        });
    }
}
