<!--
components/footer.blade.php

Purpose:
Shared footer displayed on all pages.
Contains legal links and a cookie preferences reset option.

URL References:
- date('Y'): https://www.php.net/manual/en/function.date.php
-->

<footer>
  <p>
    <a href="{{ url('/search') }}">Search</a> |
    <a href="{{ url('/legal/tos') }}">Terms of Service</a> |
    <a href="{{ url('/legal/privacy') }}">Privacy Policy</a> |
    <a href="#" onclick="resetCookieConsent()">Reset Cookie Preferences</a>
  </p>
  <p>&copy; {{ date('Y') }} Demayne Govender. All rights reserved.</p>
</footer>
