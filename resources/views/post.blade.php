<!--
post.blade.php

Purpose:
Displays a single blog post using the ID passed via the URL.

URL References:
- asset(): https://laravel.com/docs/10.x/helpers#method-asset
- url(): https://laravel.com/docs/10.x/helpers#method-url
- date(): https://www.php.net/manual/en/function.date.php
- nl2br(): https://www.php.net/manual/en/function.nl2br.php
- e(): https://laravel.com/docs/10.x/helpers#method-e
- Str::slug(): https://laravel.com/docs/10.x/helpers#method-str-slug
-->
@extends('layouts.layout')

@section('title', $post->title)

@section('content')
  @php
    $slug = \Illuminate\Support\Str::slug($post->title);
    $originalImagePath = 'storage/blog-images/original_' . $slug . '.jpg'; // Changed variable name
    $fallback = asset('storage/blog-images/default.jpg');
  @endphp

  <div class="post-image-container">
    {{-- Responsive Image Tag with srcset and sizes --}}
    <img
      src="{{ asset($originalImagePath) }}"
      srcset="
          {{ asset('storage/blog-images/thumb_' . $slug . '.jpg') }} 300w,
          {{ asset('storage/blog-images/medium_' . $slug . '.jpg') }} 800w,
          {{ asset('storage/blog-images/large_' . $slug . '.jpg') }} 1200w,
          {{ asset($originalImagePath) }} 1600w
      "
      sizes="100vw" {{-- This main image often takes full width, so 100vw is appropriate --}}
      alt="{{ $post->title }}" {{-- Use post title for accessibility --}}
      onerror="this.onerror=null;this.src='{{ $fallback }}';"
      class="post-image"
    />
  </div>

  <h1 class="text-center">{{ $post->title }}</h1> {{-- Added text-center --}}

  <p class="date text-center"> {{-- Added text-center --}}
    {{ $post->created_at ? $post->created_at->format('F j, Y') : 'Date unavailable' }}
  </p>

  <p>{!! nl2br(e($post->content)) !!}</p>

  @if ($post->category)
    <p class="category-label">
      Category:
      <a href="{{ url('/category/' . \Illuminate\Support\Str::slug($post->category->name)) }}">
        {{ $post->category->name }}
      </a>
    </p>
  @endif

  @if ($post->tags && $post->tags->count())
    <p class="tag-label">
      Tags:
      @foreach ($post->tags as $tag)
        <a href="{{ url('/tag/' . \Illuminate\Support\Str::slug($tag->description)) }}">
          {{ $tag->description }}
        </a>@if (! $loop->last), @endif
      @endforeach
    </p>
  @endif

  <p class="text-center"><a href="{{ url('/') }}" class="back-link">← Back to Home</a></p> {{-- Added text-center --}}
@endsection