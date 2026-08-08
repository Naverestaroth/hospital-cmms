<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="bg-[#F3F7F9] font-sans antialiased relative min-h-screen overflow-x-hidden">

    <!-- Dedicated Fixed Viewport Background Layer (Apple VisionOS / macOS Tahoe Ambient Mesh) -->
    <div class="ambient-mesh-background" aria-hidden="true">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <div class="blob blob-4"></div>
    </div>

    <x-sidebar />

    <div class="app-shell ml-[19.5rem] min-h-screen lg:ml-[21rem] relative z-10 p-6 lg:p-8 space-y-5">

        <x-topbar />

        <main>

            {{ $slot }}

        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ambientMesh = document.querySelector('.ambient-mesh-background');
            if (ambientMesh) {
                let ticking = false;
                window.addEventListener('scroll', () => {
                    if (!ticking) {
                        window.requestAnimationFrame(() => {
                            // Move background up at 12% of scroll speed (parallax)
                            const scrollY = window.scrollY || window.pageYOffset;
                            ambientMesh.style.transform = `translateY(-${scrollY * 0.12}px)`;
                            ticking = false;
                        });
                        ticking = true;
                    }
                }, { passive: true });
            }
        });
    </script>
</body>
</html>
