<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <script>
            window.onload = function() {
                var time = new Date();
                console.log(time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds());
            };

        </script>
    </body>
</html>
