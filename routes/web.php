<?php

use Illuminate\Support\Facades\Route;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // Required for caching sidebar data
use App\Http\Controllers\BlogPostController; // REQUIRED: For new blog post management routes

/**
 * web.php
 *
 * Purpose:
 * Defines all web routes in the Laravel application.
 * Returns views and passes relevant data to Blade templates.
 *
 * Docs:
 * - Route::get(): https://laravel.com/docs/routing#basic-routing
 * - abort(): https://laravel.com/docs/helpers#method-abort
 * - compact(): https://www.php.net/manual/en/function.compact.php
 * - Eloquent: https://laravel.com/docs/eloquent
 */

// Helper function to get common sidebar data
// Caches data for performance for 1 hour (3600 seconds)
function getSidebarData() {
    return Cache::remember('sidebar_data', 3600, function () {
        return [
            'allCategories' => Category::all(),
            'allTags' => Tag::all(),
        ];
    });
}

// 🔐 Legal Routes (Terms of Service / Privacy Policy)
Route::get('/legal/{subsection}', function ($subsection) {
    if (!in_array($subsection, ['tos', 'privacy'])) {
        abort(404);
    }

    $pageName = $subsection === 'tos' ? 'Terms of Service' : 'Privacy Policy';
    return view('legal', array_merge(compact('pageName', 'subsection'), getSidebarData()));
})->where('subsection', '(tos|privacy)');

// 🏠 Homepage: shows 5 most recent posts
Route::get('/', function () {
    $posts = BlogPost::with(['category', 'tags'])
        // Use correct Laravel timestamp column name
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    return view('home', array_merge(compact('posts'), getSidebarData()));
});

// 📝 View single blog post by ID
Route::get('/blog/{id}', function ($id) {
    $post = BlogPost::with(['category', 'tags'])->find($id);

    if (!$post) {
        abort(404);
    }

    return view('post', array_merge(compact('post'), getSidebarData()));
});

// 📄 About Page
Route::get('/about', function () {
    return view('about', getSidebarData());
});

// 🗂 View posts by Category (slug)
Route::get('/category/{slug}', function ($slug) {
    $name = str_replace('-', ' ', $slug);
    $category = Category::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();

    if (!$category) {
        abort(404, 'Category not found');
    }

    $articles = $category->blogPosts()->with(['category', 'tags'])->get();
    return view('category', array_merge(compact('category', 'articles'), getSidebarData()));
});

// 🏷 View posts by Tag (slug)
Route::get('/tag/{slug}', function ($slug) {
    $description = str_replace('-', ' ', $slug);
    $tag = Tag::whereRaw('LOWER(description) = ?', [strtolower($description)])->first();

    if (!$tag) {
        abort(404, 'Tag not found');
    }

    $articles = $tag->blogPosts()->with(['category', 'tags'])->get();
    return view('tag', array_merge(compact('tag', 'articles'), getSidebarData()));
});

// 🔍 Search Pages and Redirect Handlers (rate limited to mitigate abuse)
Route::middleware(['throttle:30,1'])->group(function () {
    // Display the search input page
    Route::get('/search', function () {
        return view('search', array_merge(['posts' => collect()], getSidebarData()));
    });

    // Handle article ID search and redirect
    Route::get('/article-search', function (Request $request) {
        $id = $request->query('article_id');
        // Basic validation limits to numeric IDs within sensible length
        if ($id && ctype_digit($id) && strlen($id) <= 10 && BlogPost::find($id)) {
            return redirect("/blog/{$id}");
        }
        return redirect('/search')->with('error', 'Article not found for ID: ' . e($id));
    });

    // Handle category slug search and redirect
    Route::get('/category-search', function (Request $request) {
        $slug = $request->query('category_slug');
        if ($slug && is_string($slug) && strlen($slug) <= 100 && Category::where('slug', $slug)->exists()) {
            return redirect("/category/{$slug}");
        }
        return redirect('/search')->with('error', 'Category not found for slug: ' . e($slug));
    });

    // Handle tag slug search and redirect
    Route::get('/tag-search', function (Request $request) {
        $slug = $request->query('tag_slug');
        if ($slug && is_string($slug) && strlen($slug) <= 100 && Tag::where('slug', $slug)->exists()) {
            return redirect("/tag/{$slug}");
        }
        return redirect('/search')->with('error', 'Tag not found for slug: ' . e($slug));
    });
});

// === Blog Post Management Routes (Admin Section) ===
// These routes are commented out to prevent public access.
// Uncomment locally or use middleware for owner-only access (e.g., RestrictToOwner or auth).
/*
Route::get('/admin/posts/create', [BlogPostController::class, 'create'])->name('posts.create');
Route::post('/admin/posts', [BlogPostController::class, 'store'])->name('posts.store');
Route::get('/admin/posts/{blogPost}/edit', [BlogPostController::class, 'edit'])->name('posts.edit');
Route::put('/admin/posts/{blogPost}', [BlogPostController::class, 'update'])->name('posts.update');
Route::delete('/admin/posts/{blogPost}', [BlogPostController::class, 'destroy'])->name('posts.destroy');
Route::get('/admin/posts', [BlogPostController::class, 'indexAdmin'])->name('posts.admin.index');
*/
// Optionally, use middleware for owner-only access:
// Route::middleware(['owner'])->group(function () {
//     Route::get('/admin/posts/create', [BlogPostController::class, 'create'])->name('posts.create');
//     Route::post('/admin/posts', [BlogPostController::class, 'store'])->name('posts.store');
//     Route::get('/admin/posts/{blogPost}/edit', [BlogPostController::class, 'edit'])->name('posts.edit');
//     Route::put('/admin/posts/{blogPost}', [BlogPostController::class, 'update'])->name('posts.update');
//     Route::delete('/admin/posts/{blogPost}', [BlogPostController::class, 'destroy'])->name('posts.destroy');
//     Route::get('/admin/posts', [BlogPostController::class, 'indexAdmin'])->name('posts.admin.index');
// });

?>