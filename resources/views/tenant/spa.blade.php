<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ tenant('name') }} — Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

    <!-- CSS / JS -->
    @vite(['resources/tenant-portal/src/main.js'])
</head>
<body>
    <div id="app"></div>

    <script type="module">
        // Placeholder for SPA development
        // For production, we will use @vite('resources/tenant-portal/src/main.js')
        // But the current vite.php config needs to aware of this path.
    </script>
</body>
</html>
