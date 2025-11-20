<!--
category.blade.php

Purpose:
Displays a list of blog posts under a specific category, based on the slug in the URL.

URL References:
- asset(): https://laravel.com/docs/helpers#method-asset
- url(): https://laravel.com/docs/helpers#method-url
-->

@extends('layouts.layout')
@section('title', 'Category: ' . $category->name)
@section('content')
  <h1 class="text-center">Category: {{ $category->name }}</h1> {{-- Added text-center --}}

  @if(isset($articles) && count($articles) > 0)
    <div class="post-list">
      @foreach($articles as $post)
        @php
          $slug = \Illuminate\Support\Str::slug($post->title);
          $originalImagePath = 'storage/blog-images/original_' . $slug . '.jpg'; // Changed variable name
          $fallback = asset('storage/blog-images/default.jpg');
        @endphp
        <div class="post-card">
          <a href="{{ url('/blog/' . $post->id) }}">
            {{-- Responsive Image Tag with srcset and sizes --}}
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
              alt="{{ $post->title }}" {{-- Use post title for accessibility --}}
              onerror="this.onerror=null;this.src='{{ $fallback }}';"
              class="post-image"
            />
            <div class="post-content"> {{-- Added div for consistent styling --}}
              <h3>{{ $post->title }}</h3>
              <p>{{ \Illuminate\Support\Str::limit($post->content, 120) }}</p> {{-- Added content limit --}}
              <div class="meta"> {{-- Grouped meta information --}}
                <p class="category-label">Category: {{ $post->category->name ?? 'Uncategorized' }}</p>
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
          </a>
        </div>
      @endforeach
    </div>
  @else
    <p class="text-center">No articles found in this category.</p> {{-- Added text-center --}}
  @endif

  <p class="text-center"><a href="{{ url('/') }}" class="back-link">← Back to Home</a></p> {{-- Added text-center --}}
@endsection