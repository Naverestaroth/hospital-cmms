<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="bg-slate-50 font-sans antialiased">

    <x-sidebar />

    <div class="ml-64 min-h-screen">

        <x-topbar />

        <main class="p-8">

            {{ $slot }}

        </main>

    </div>

</body>

</html>