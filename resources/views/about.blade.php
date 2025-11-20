<!--
about.blade.php

Purpose:
Displays information about the blog or the blog author.

URL References:
- asset(): https://laravel.com/docs/helpers#method-asset
- url(): https://laravel.com/docs/helpers#method-url
-->


@extends('layouts.layout')
@section('title', 'About')
@section('content')
  <h1>About This Blog</h1>
  <p>Welcome to <strong>Cool Blog</strong> — a place for coding tutorials, tech thoughts, and helpful how-tos.</p>
  <p>This blog was created by <strong>Demayne Govender</strong> as part of a journey into Laravel and full-stack web development.</p>
  <p>It’s designed to be lightweight, user-friendly, and mobile-first with a modern aesthetic.</p>
  <p><a href="{{ url('/') }}" class="back-link">← Back to Home</a></p>
@endsection
