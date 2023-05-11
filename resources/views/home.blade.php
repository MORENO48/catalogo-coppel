<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Home</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <script>
            window.onload = function() {
                var time = new Date();
                console.log(time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds());
                axios.get('/sanctum/csrf-cookie').then(response => {
                    console.log('entre')
                });

                axios.get('/api/categoria').then(response => {
                    console.log('entre 2');
                    console.log(response);
                });
            };

        </script>
    </body>
</html>
