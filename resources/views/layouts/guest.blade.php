<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hospital CMMS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-emerald-50">

    <div class="mx-auto flex min-h-screen items-center justify-center px-6">

        <div class="mx-auto w-full max-w-lg">

             <!-- Logo -->
            <div class="mb-8 text-center">

               <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-xl font-bold text-white shadow-lg">
                   CM
                </div>

                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                Hospital CMMS
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                  RSUD Dr. H. Moch. Ansari Saleh
                </p>

            </div>

            <div
              class="rounded-3xl border border-slate-200 bg-white p-10 shadow-lg">

              {{ $slot }}

            </div>

        </div>

    </div>

</body>
</html>