<!--
legal.blade.php

Purpose:
Displays legal content like Terms of Use and Privacy Policy in a clear, ethical manner.

URL References:
- $pageName and $subsection: passed from controller using compact()
-->

@extends('layouts.layout')
@section('title', 'Legal: ' . $pageName)
@section('content')
  <h1>Legal: {{ $pageName }}</h1>

  @if ($subsection === 'tos')
    <h2>Terms of Use</h2>
    <p>Welcome to Cool Blog. By accessing this site, you agree to use it ethically and responsibly.</p>
    <p>You may browse and share content freely, but you may not copy, scrape, or redistribute it without proper credit or permission.</p>
    <p>This website is provided "as is" without warranties of any kind. We do our best to keep content accurate, but we are not liable for any errors or decisions made based on the content here.</p>
    <p>Please respect other users and refrain from posting inappropriate or harmful comments.</p>
  @else
    <h2>Privacy Policy</h2>
    <p>Your privacy is important to us. We collect minimal data necessary to provide the best experience — such as anonymous visit stats or form submissions.</p>
    <p>We do not share, sell, or misuse your personal information. Any cookies used on this site are for basic user experience enhancements (e.g. remembering preferences).</p>
    <p>By using this website, you consent to this policy. You can clear cookies from your browser at any time.</p>
    <p>If you submit information through contact forms, we handle it securely and never share it with third parties.</p>
  @endif

  <p><a href="{{ url('/') }}" class="back-link">← Back to Home</a></p>
@endsection