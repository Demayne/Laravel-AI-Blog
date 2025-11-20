<!--
layout.blade.php

Purpose:
Base layout that structures the site into header, main content, and footer sections.
Used by all other Blade views for consistency and maintainability.

URL References:
{{-- @yield / @section / @include: https://laravel.com/docs/blade#template-inheritance --}}
-->
<!-- resources/views/layouts/layout.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Cool Blog')</title>

  {{-- Font Awesome for Icons (e.g., search icon, social media) --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- Using Laravel's @vite directive for asset bundling with Vite. --}}
  {{-- Ensure resources/js/app.js imports resources/sass/blog.scss --}}
  @vite(['resources/js/app.js'])

  {{-- Google Fonts for better typography --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">

</head>
<body>
  <header class="site-header">
    <div class="header-container">
      <h1 class="site-title"><a href="{{ url('/') }}">Cool Blog</a></h1>
      <nav class="nav-links">
        <a href="{{ url('/about') }}">About</a>
        <a href="{{ url('/search') }}">Search</a>
        <a href="{{ url('/legal/tos') }}">Legal</a>
        {{-- Admin link commented out to hide from public users --}}
        {{-- <a href="{{ route('posts.admin.index') }}">Admin</a> --}}
      </nav>
    </div>
  </header>

  <main>
    <div class="content-area">
      @yield('content')
    </div>
    {{-- Sidebar will appear on most pages, ensure data is passed via web.php --}}
    @include('components.sidebar')
  </main>

  @include('components.footer')
  @include('components.cookie-notice')

  <script>
  // Function for resetting cookie consent (e.g., for development/testing)
  function resetCookieConsent() {
    localStorage.removeItem("cookie-accepted");
    location.reload();
  }
  </script>
</body>
</html>