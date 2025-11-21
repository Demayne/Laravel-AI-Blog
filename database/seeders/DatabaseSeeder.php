<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\BlogPost;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Basic test user (direct create to avoid Faker dependency in production)
        User::query()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        // Seed Categories
        $categories = collect([
            'Tech News', 'Programming', 'AI Research', 'DevOps', 'Security'
        ])->map(fn($name) => Category::create(['name' => $name, 'slug' => str_replace(' ', '-', strtolower($name))]));

        // Seed Tags
        $tags = collect([
            'Laravel', 'PHP', 'Open Source', 'Cloud', 'Tutorial'
        ])->map(fn($desc) => Tag::create(['description' => $desc, 'slug' => str_replace(' ', '-', strtolower($desc))]));

        // Seed Blog Posts
        $sampleContent = str_repeat('This is placeholder paragraph content for the article. ', 8);
        $posts = [];
        for ($i = 1; $i <= 5; $i++) {
            $category = $categories[$i - 1];
            $posts[$i] = BlogPost::create([
                'title' => "Sample Article $i",
                'content' => $sampleContent,
                'category_id' => $category->id,
                'image' => null,
            ]);
        }

        // Attach random tags to posts
        $postsCollection = collect($posts);
        $tagsCollection = collect($tags);
        $postsCollection->each(function ($post) use ($tagsCollection) {
            $post->tags()->sync($tagsCollection->random(3)->pluck('id')->toArray());
        });
    }
}
