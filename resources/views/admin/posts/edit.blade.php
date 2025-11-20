<!-- resources/views/admin/posts/edit.blade.php -->

@extends('layouts.layout')

@section('title', 'Admin: Edit Blog Post - ' . $blogPost->title)

@section('content')
  <h1 class="text-center">Edit Blog Post: "{{ \Illuminate\Support\Str::limit($blogPost->title, 50) }}"</h1>

  {{-- Error display for validation failures --}}
  @if ($errors->any())
    <div class="alert error-message text-center">
      <strong>Whoops! Something went wrong.</strong>
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="form-card">
    <form action="{{ route('posts.update', $blogPost->id) }}" method="POST" enctype="multipart/form-data">
      @csrf {{-- CSRF token for security --}}
      @method('PUT') {{-- Use PUT method for update requests --}}

      <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="{{ old('title', $blogPost->title) }}" required>
      </div>

      <div class="form-group">
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" required>{{ old('content', $blogPost->content) }}</textarea>
      </div>

      <div class="form-group">
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id">
          <option value="">Select a Category</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $blogPost->category_id) == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="tags">Tags:</label>
        {{-- Determine selected tags for multi-select --}}
        @php
          $currentTags = $blogPost->tags->pluck('id')->toArray();
        @endphp
        <select id="tags" name="tags[]" multiple size="5">
          @foreach ($tags as $tag)
            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $currentTags)) ? 'selected' : '' }}>
              {{ $tag->description }}
            </option>
          @endforeach
        </select>
        <small>Hold Ctrl/Cmd to select multiple tags.</small>
      </div>

      <div class="form-group">
        <label for="image">Current Post Image:</label>
        @if ($blogPost->image)
            @php
                $slug = \Illuminate\Support\Str::slug($blogPost->title);
                $originalImagePath = 'blog-images/original_' . $slug . '.' . pathinfo($blogPost->image, PATHINFO_EXTENSION);
                $fallback = asset('blog-images/default.jpg');
            @endphp
            <div class="current-image-preview" style="max-width: 200px; margin-bottom: 1rem;">
                <img src="{{ asset($originalImagePath) }}"
                     alt="Current Image"
                     onerror="this.onerror=null;this.src='{{ $fallback }}';"
                     style="width: 100%; height: auto; border-radius: 5px;">
            </div>
            <label for="clear_image">
                <input type="checkbox" id="clear_image" name="clear_image" value="1"> Clear current image
            </label>
        @else
            <p>No image currently associated with this post.</p>
        @endif

        <label for="new_image" style="margin-top: 1rem;">Upload New Image (replaces current):</label>
        <input type="file" id="new_image" name="image" accept="image/*">
        <small>Recommended: JPG, PNG. Max 2MB. Leave empty to keep current image.</small>
      </div>

      <div class="form-actions text-center">
        <button type="submit" class="button primary-button"><i class="fas fa-sync-alt"></i> Update Post</button>
        <a href="{{ route('posts.admin.index') }}" class="button secondary-button"><i class="fas fa-arrow-alt-circle-left"></i> Cancel</a>
      </div>
    </form>
  </div>

  <p class="text-center" style="margin-top: 3rem;"><a href="{{ route('posts.admin.index') }}" class="back-link">← Back to Admin Posts</a></p>
@endsection

{{-- The styling for .form-card, .form-group, .form-actions, and buttons is already in blog.scss from create.blade.php. No new style block needed. --}}