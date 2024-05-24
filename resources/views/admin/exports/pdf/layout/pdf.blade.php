<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>@yield('title')</title>
        <style>
            /* devanagari */
            /* @font-face {
                font-family: 'Tiro Devanagari Hindi';
                src: url('{{ storage_path("fonts/TiroDevanagariHindi-Regular.ttf") }}') format('truetype');
            } */

            .HI{
                font-family: 'Tiro Devanagari Hindi', serif;
            }
            .EN{
                font-family: 'timefont', sans-serif;
            }

            body{
                font-family: 'timefont', sans-serif;
            }
        </style>

        @yield('styles')
    </head>
<body>
    <!-- [ Main Content ] start -->
    @yield("content")
    <!-- [ Main Content ] end -->
</body>
</html>
