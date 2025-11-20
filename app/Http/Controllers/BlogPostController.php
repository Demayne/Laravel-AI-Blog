<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image; // <-- Ensure this is correctly aliased in config/app.php if not already
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;

class BlogPostController extends Controller
{
    /**
     * Display a listing of all blog posts for the admin panel.
     */
    public function indexAdmin()
    {
        $posts = BlogPost::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created blog post in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max 2MB
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // 2. Handle Image Upload and Resizing
        $imageFileNameInDb = null;
        if ($request->hasFile('image')) {
            $uploadedImage = $request->file('image');
            $titleSlug = Str::slug($request->title);
            $extension = strtolower($uploadedImage->getClientOriginalExtension());

            // Define the base filename (e.g., 'my-cool-post.jpg') that will be stored in DB
            $imageFileNameInDb = $titleSlug . '.' . $extension;

            // Define the full path for the 'original_' prefixed image
            $originalFileName = 'original_' . $imageFileNameInDb;
            $originalFilePathFull = public_path('blog-images/' . $originalFileName);

            // Move the original uploaded file to its permanent location
            try {
                $uploadedImage->move(public_path('blog-images'), $originalFileName);
                if (!file_exists($originalFilePathFull)) {
                    // This is a critical check. If move fails, log it and return.
                    Log::error('Image move failed for original: ' . $originalFilePathFull);
                    return back()->withInput()->with('error', 'Failed to upload original image.');
                }
                Log::info('Original image moved successfully to: ' . $originalFilePathFull);

            } catch (\Exception $e) {
                Log::error('Error moving original image: ' . $e->getMessage());
                return back()->withInput()->with('error', 'Error during original image upload.');
            }

            // Define other image sizes
            $sizes = [
                'thumb' => 300,
                'medium' => 800,
                'large' => 1200,
            ];

            // Process and save other sizes using Intervention Image
            foreach ($sizes as $prefix => $width) {
                $prefixedFileName = $prefix . '_' . $imageFileNameInDb;
                $prefixedFilePathFull = public_path('blog-images/' . $prefixedFileName);

                try {
                    // Create a new Intervention Image instance from the *already moved original image*
                    // This is robust as the file is now guaranteed to be on disk if previous step passed
                    Image::make($originalFilePathFull)
                         ->widen($width, function ($constraint) {
                             $constraint->upsize(); // Don't enlarge if image is smaller than width
                         })
                         ->save($prefixedFilePathFull);

                    if (!file_exists($prefixedFilePathFull)) {
                         Log::error("Failed to save {$prefix} image: " . $prefixedFilePathFull);
                    } else {
                        Log::info("Saved {$prefix} image to: " . $prefixedFilePathFull);
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to save {$prefix} image for {$titleSlug}: " . $e->getMessage());
                    // Optionally, remove the already saved 'original_' image if subsequent resizes fail
                    // if (file_exists($originalFilePathFull)) { unlink($originalFilePathFull); }
                    return back()->withInput()->with('error', "Error processing {$prefix} image: " . $e->getMessage());
                }
            }
        }

        // 3. Create the Blog Post in the database
        $post = BlogPost::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image' => $imageFileNameInDb, // Save the base filename (e.g., 'my-cool-post.jpg')
            'created' => now(), // Or use default timestamps like created_at if set in migration
        ]);

        // 4. Attach Tags (if any)
        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags'));
        }

        // 5. Redirect with success message
        return redirect()->route('posts.admin.index')->with('success', 'Blog post created successfully!');
    }

    /**
     * Show the form for editing the specified blog post.
     */
    public function edit(BlogPost $blogPost)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('blogPost', 'categories', 'tags'));
    }

    /**
     * Update the specified blog post in storage.
     */
    public function update(Request $request, BlogPost $blogPost)
    {
        // 1. Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max 2MB
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // 2. Handle Image Upload/Update/Deletion
        $imageFileNameInDb = $blogPost->image; // Default: keep existing image filename

        if ($request->hasFile('image')) {
            // Delete old images first if a new one is being uploaded
            if ($blogPost->image) {
                $oldBaseFileName = $blogPost->image;
                $prefixes = ['original_', 'thumb_', 'medium_', 'large_'];
                foreach ($prefixes as $prefix) {
                    $oldFilePath = public_path('blog-images/' . $prefix . $oldBaseFileName);
                    if (file_exists($oldFilePath)) {
                        try {
                            unlink($oldFilePath);
                            Log::info("Deleted old image: " . $oldFilePath);
                        } catch (\Exception $e) {
                            Log::error("Failed to delete old image {$oldFilePath}: " . $e->getMessage());
                        }
                    }
                }
            }

            // Process and save new images (similar to store method)
            $uploadedImage = $request->file('image');
            $titleSlug = Str::slug($request->title);
            $extension = strtolower($uploadedImage->getClientOriginalExtension());

            $imageFileNameInDb = $titleSlug . '.' . $extension; // New base filename

            $originalFileName = 'original_' . $imageFileNameInDb;
            $originalFilePathFull = public_path('blog-images/' . $originalFileName);

            try {
                $uploadedImage->move(public_path('blog-images'), $originalFileName);
                if (!file_exists($originalFilePathFull)) {
                     Log::error('Image move failed for original (update): ' . $originalFilePathFull);
                     return back()->withInput()->with('error', 'Failed to upload original image during update.');
                }
                Log::info('New original image moved successfully to: ' . $originalFilePathFull);
            } catch (\Exception $e) {
                Log::error('Error moving new original image (update): ' . $e->getMessage());
                return back()->withInput()->with('error', 'Error during new original image upload on update.');
            }

            $sizes = [
                'thumb' => 300, 'medium' => 800, 'large' => 1200,
            ];

            foreach ($sizes as $prefix => $width) {
                $prefixedFileName = $prefix . '_' . $imageFileNameInDb;
                $prefixedFilePathFull = public_path('blog-images/' . $prefixedFileName);
                try {
                    Image::make($originalFilePathFull)
                         ->widen($width, function ($constraint) { $constraint->upsize(); })
                         ->save($prefixedFilePathFull);
                    if (!file_exists($prefixedFilePathFull)) {
                        Log::error("Failed to save {$prefix} image (update): " . $prefixedFilePathFull);
                    } else {
                        Log::info("Saved {$prefix} image (update) to: " . $prefixedFilePathFull);
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to save {$prefix} image for {$titleSlug} (update): " . $e->getMessage());
                    return back()->withInput()->with('error', "Error processing {$prefix} image during update: " . $e->getMessage());
                }
            }
        } elseif ($request->input('clear_image')) {
            // Option to clear image (checkbox in edit form)
            if ($blogPost->image) {
                $oldBaseFileName = $blogPost->image;
                $prefixes = ['original_', 'thumb_', 'medium_', 'large_'];
                foreach ($prefixes as $prefix) {
                    $oldFilePath = public_path('blog-images/' . $prefix . $oldBaseFileName);
                    if (file_exists($oldFilePath)) {
                        try {
                            unlink($oldFilePath);
                            Log::info("Deleted image on clear request: " . $oldFilePath);
                        } catch (\Exception $e) {
                            Log::error("Failed to delete image on clear request {$oldFilePath}: " . $e->getMessage());
                        }
                    }
                }
                $imageFileNameInDb = null; // Set image to null in DB
            }
        }

        // 3. Update the Blog Post in the database
        $blogPost->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image' => $imageFileNameInDb, // Update with new filename or null
        ]);

        // 4. Update Tags
        if ($request->has('tags')) {
            $blogPost->tags()->sync($request->input('tags'));
        } else {
            $blogPost->tags()->detach(); // Detach all tags if none are selected
        }

        // 5. Redirect with success message
        return redirect()->route('posts.admin.index')->with('success', 'Blog post updated successfully!');
    }

    /**
     * Remove the specified blog post from storage.
     */
    public function destroy(BlogPost $blogPost)
    {
        // Delete associated image files first
        if ($blogPost->image) {
            $baseFileName = $blogPost->image;
            $prefixes = ['original_', 'thumb_', 'medium_', 'large_'];
            foreach ($prefixes as $prefix) {
                $filePath = public_path('blog-images/' . $prefix . $baseFileName);
                if (file_exists($filePath)) {
                    try {
                        unlink($filePath);
                        Log::info("Deleted image during destroy: " . $filePath);
                    } catch (\Exception $e) {
                        Log::error("Failed to delete image during destroy {$filePath}: " . $e->getMessage());
                    }
                }
            }
        }

        $blogPost->delete();
        return redirect()->route('posts.admin.index')->with('success', 'Blog post deleted successfully!');
    }
}