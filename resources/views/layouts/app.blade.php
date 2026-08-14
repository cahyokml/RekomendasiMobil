<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobilku – @yield('title', 'Sahabat Perjalanan Anda')</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;700;800&family=Barlow:wght@300;400;500&display=swap" rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>

<body>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

</body>
</html>