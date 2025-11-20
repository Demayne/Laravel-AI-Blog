<!--
search.blade.php

Purpose:
Displays a list of the latest blog posts on the homepage.

URL References:
- route(): https://laravel.com/docs/helpers#method-route
- url(): https://laravel.com/docs/helpers#method-url
- Str::slug(): https://laravel.com/docs/10.x/helpers#method-str-slug
- Str::limit(): https://laravel.com/docs/10.x/helpers#method-str-limit
-->

@extends('layouts.layout')

@section('title', 'Search')

@section('content')
  <h1>Search Blog Content</h1>

  {{-- Display Error Message if redirected back from a failed search (e.g., ID/slug not found) --}}
  @if (session()->has('error'))
    <div class="alert error-message">
      {{ session('error') }}
    </div>
  @endif

  <div class="search-section">
    <h2>Search by Article ID</h2>
    <form action="{{ url('/article-search') }}" method="GET">
      <input type="text" name="article_id" placeholder="Enter article ID (e.g., 1)" required>
      <button type="submit">Search Article</button>
    </form>
  </div>

  <div class="search-section">
    <h2>Search by Category Slug</h2>
    <form action="{{ url('/category-search') }}" method="GET">
      <input type="text" name="category_slug" placeholder="Enter category slug (e.g., tech-news)" required>
      <button type="submit">Search Category</button>
    </form>
  </div>

  <div class="search-section">
    <h2>Search by Tag Slug</h2>
    <form action="{{ url('/tag-search') }}" method="GET">
      <input type="text" name="tag_slug" placeholder="Enter tag slug (e.g., open-source)" required>
      <button type="submit">Search Tag</button>
    </form>
  </div>

  {{-- The section below is commented out because this page is primarily for search *inputs*.
       If you still want to display all posts below the search forms, you can uncomment this section
       and ensure the '/search' route in web.php passes actual posts, not just an empty collection.
       Currently, the web.php is set to pass an empty collection to avoid 'Undefined variable $posts'
       if this section were to be uncommented without an explicit search result being present.
  --}}
  {{--
  @if (!$posts->isEmpty())
    <h2>All Blog Posts (or Search Results)</h2>
    <div class="post-list">
      @foreach ($posts as $post)
        @php
          $slug = \Illuminate\Support\Str::slug($post->title);
          $originalImagePath = 'storage/blog-images/original_' . $slug . '.jpg';
          $fallback = asset('storage/blog-images/default.jpg');
        @endphp

        <div class="post-card">
          <img
            src="{{ asset($originalImagePath) }}"
            srcset="
                {{ asset('storage/blog-images/thumb_' . $slug . '.jpg') }} 300w,
                {{ asset('storage/blog-images/medium_' . $slug . '.jpg') }} 800w,
                {{ asset('storage/blog-images/large_' . $slug . '.jpg') }} 1200w,
                {{ asset($originalImagePath) }} 1600w
            "
            sizes="(max-width: 600px) 300px,
                   (max-width: 1200px) 800px,
                   1200px"
            alt="{{ $post->title }}"
            onerror="this.onerror=null;this.src='{{ $fallback }}';"
            class="post-image"
          />

          <h3>
            <a href="{{ url('/blog/' . $post->id) }}">{{ $post->title }}</a>
          </h3>

          <p>{{ \Illuminate\Support\Str::limit($post->content, 120) }}</p>

          <p class="category-label">
            Category:
            @if ($post->category)
              <a href="{{ url('/category/' . \Illuminate\Support\Str::slug($post->category->name)) }}">
                {{ $post->category->name }}
              </a>
            @else
              Uncategorized
            @endif
            <br>
            Tags:
            @if ($post->tags && $post->tags->count())
              @foreach ($post->tags as $tag)
                <a href="{{ url('/tag/' . \Illuminate\Support\Str::slug($tag->description)) }}">
                  {{ $tag->description }}
                </a>@if (! $loop->last), @endif
              @endforeach
            @else
              None
            @endif
          </p>
        </div>
      @endforeach
    </div>
  @endif
  --}}

  <p><a href="{{ url('/') }}" class="back-link">← Back to Home</a></p>
@endsection