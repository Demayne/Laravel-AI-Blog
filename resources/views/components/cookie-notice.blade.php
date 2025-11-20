<!--
components/cookie-notice.blade.php

Purpose:
Displays a cookie consent popup in the center of the screen with a dark theme.
Centered using Tailwind CSS, visible on all pages until accepted.

References:
- Tailwind CSS: https://tailwindcss.com/docs
- JavaScript controls visibility with .active class
-->
<div class="cookie-popup fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gray-800 text-gray-200 p-6 rounded-lg shadow-lg max-w-md w-full opacity-0 transition-opacity duration-500 z-50" id="cookie-popup">
  <p>This site uses cookies to improve your experience. By continuing, you accept our cookie policy.</p>
  <button id="cookie-accept" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4">Accept</button>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const popup = document.getElementById("cookie-popup");
    const acceptBtn = document.getElementById("cookie-accept");

    if (!localStorage.getItem("cookie-accepted")) {
      setTimeout(() => {
        popup.classList.add("active");
      }, 500);
    }

    acceptBtn.addEventListener("click", () => {
      localStorage.setItem("cookie-accepted", "true");
      popup.classList.remove("active");
      setTimeout(() => {
        popup.style.display = "none";
      }, 500);
    });
  });
</script>