<!--
home.blade.php

Purpose:
Displays a list of the latest blog posts on the homepage.

URL References:
- route(): https://laravel.com/docs/helpers#method-route
- url(): https://laravel.com/docs/helpers#method-url
- Str::slug(): https://laravel.com/docs/10.x/helpers#method-str-slug
- Str::limit(): https://laravel.com/docs/10.x/helpers#method-str-limit
-->

@extends('layouts.layout')

@section('title', 'Home')

@section('content')
  <h1 class="text-center">Latest Blog Posts</h1> {{-- Added text-center class for aesthetic --}}

  @if ($posts->isEmpty())
    <p class="text-center">No blog posts available yet.</p> {{-- Added text-center class --}}
  @else
    <div class="post-list">
      @foreach ($posts as $post)
        @php
          $slug = \Illuminate\Support\Str::slug($post->title);
          // Original image path for fallback if srcset doesn't work or for direct access
          $originalImagePath = 'storage/blog-images/original_' . $slug . '.jpg';
          $fallback = asset('storage/blog-images/default.jpg'); // Fallback for missing images
        @endphp

        <div class="post-card">
          {{-- Responsive Image Tag with srcset and sizes --}}
          <img
            src="{{ asset($originalImagePath) }}" {{-- Fallback src points to the larger original --}}
            srcset="
                {{ asset('storage/blog-images/thumb_' . $slug . '.jpg') }} 300w,
                {{ asset('storage/blog-images/medium_' . $slug . '.jpg') }} 800w,
                {{ asset('storage/blog-images/large_' . $slug . '.jpg') }} 1200w,
                {{ asset($originalImagePath) }} 1600w {{-- Assume original is ~1600px or higher --}}
            "
            sizes="(max-width: 600px) 300px,
                   (max-width: 1200px) 800px,
                   1200px" {{-- Adjust these 'sizes' based on your CSS layout and breakpoints --}}
            alt="{{ $post->title }}" {{-- Use post title for accessibility --}}
            onerror="this.onerror=null;this.src='{{ $fallback }}';"
            class="post-image"
          />

          <div class="post-content"> {{-- Added a div for content for better styling --}}
            <h3>
              <a href="{{ url('/blog/' . $post->id) }}">{{ $post->title }}</a>
            </h3>

            <p>{{ \Illuminate\Support\Str::limit($post->content, 120) }}</p>

            <div class="meta"> {{-- Grouped meta information --}}
              <p class="category-label">
                Category:
                @if ($post->category)
                  <a href="{{ url('/category/' . \Illuminate\Support\Str::slug($post->category->name)) }}">
                    {{ $post->category->name }}
                  </a>
                @else
                  Uncategorized
                @endif
              </p>
              <p class="tag-label">
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
            </div> {{-- /meta --}}
          </div> {{-- /post-content --}}
        </div> {{-- /post-card --}}
      @endforeach
    </div> {{-- /post-list --}}
  @endif
@endsection