<!--
greeting.blade.php

Purpose:
Displays a greeting message using a dynamic name passed to the view.

URL References:
- {{ $name }}: Blade syntax for echoing variables: https://laravel.com/docs/blade#displaying-data
-->
<html>
<body>
    <h1>Good day, {{ $name }}!</h1>
</body>
</html>
