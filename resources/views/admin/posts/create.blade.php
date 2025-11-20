<!-- resources/views/admin/posts/create.blade.php -->

@extends('layouts.layout')

@section('title', 'Admin: Create New Blog Post')

@section('content')
  <h1 class="text-center">Create New Blog Post</h1>

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
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
      @csrf {{-- CSRF token for security --}}

      <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required>
      </div>

      <div class="form-group">
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
      </div>

      <div class="form-group">
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id">
          <option value="">Select a Category</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="tags">Tags:</label>
        {{-- Use a multi-select for tags. You might need a JS library for a nicer UI. --}}
        <select id="tags" name="tags[]" multiple size="5">
          @foreach ($tags as $tag)
            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
              {{ $tag->description }}
            </option>
          @endforeach
        </select>
        <small>Hold Ctrl/Cmd to select multiple tags.</small>
      </div>

      <div class="form-group">
        <label for="image">Post Image:</label>
        <input type="file" id="image" name="image" accept="image/*">
        <small>Recommended: JPG, PNG. Max 2MB.</small>
      </div>

      <div class="form-actions text-center">
        <button type="submit" class="button primary-button"><i class="fas fa-save"></i> Create Post</button>
        <a href="{{ route('posts.admin.index') }}" class="button secondary-button"><i class="fas fa-arrow-alt-circle-left"></i> Cancel</a>
      </div>
    </form>
  </div>

  <p class="text-center" style="margin-top: 3rem;"><a href="{{ route('posts.admin.index') }}" class="back-link">← Back to Admin Posts</a></p>
@endsection

{{-- Add some basic form styling to your resources/sass/blog.scss --}}
<style>
    .form-card {
        background-color: #2a2a2a;
        padding: 2.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        max-width: 800px;
        margin: 2rem auto;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #f1f1f1;
        font-size: 1.1em;
    }

    .form-group input[type="text"],
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #3a3a3a;
        border-radius: 5px;
        background-color: #1e1e1e;
        color: #f1f1f1;
        font-size: 1em;
        box-sizing: border-box; /* Ensure padding doesn't add to width */
    }

    .form-group textarea {
        resize: vertical; /* Allow vertical resizing */
    }

    .form-group input[type="file"] {
        padding: 0.5rem 0; /* Adjust padding for file input */
    }

    .form-group small {
        display: block;
        margin-top: 0.5rem;
        color: #a0a0a0;
        font-size: 0.9em;
    }

    .form-actions {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 1.5rem;
    }

    .button {
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .primary-button {
        background-color: #64ffda; /* Primary color */
        color: #121212; /* Dark text on primary */
    }
    .primary-button:hover {
        background-color: lighten(#64ffda, 5%);
        transform: translateY(-2px);
    }

    .secondary-button {
        background-color: #4a4a4a; /* Muted background */
        color: #f1f1f1; /* Light text */
    }
    .secondary-button:hover {
        background-color: darken(#4a4a4a, 5%);
        transform: translateY(-2px);
    }
</style>