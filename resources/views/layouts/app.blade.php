<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Cool Blog')</title>
  <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
</head>
<body>
  <main>
    @yield('content')
  </main>

  @include('components.footer')
  @include('components.cookie-notice')

  <script src="{{ asset('js/cookie.js') }}"></script> {{-- Optional if you separate JS --}}
</body>
</html>
