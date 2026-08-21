<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>
<body>
    @if ($showGreeting)
        <h1>{{ $greeting }}</h1>
    @endif
</body>
</html>
