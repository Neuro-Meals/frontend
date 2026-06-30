<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Nutrio Meals'))</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">

    @yield('content')

    {{-- Dark Mode Toggle Script --}}
    <script>
        (function() {
            const html = document.documentElement;
            const toggleBtn = document.getElementById('theme-toggle');
            const iconSun = document.getElementById('theme-icon-sun');
            const iconMoon = document.getElementById('theme-icon-moon');

            function updateIcons(isDark) {
                if (iconSun && iconMoon) {
                    iconSun.classList.toggle('hidden', isDark);
                    iconMoon.classList.toggle('hidden', !isDark);
                }
            }

            function setTheme(dark) {
                if (dark) {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
                updateIcons(dark);
            }

            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = saved ? saved === 'dark' : prefersDark;
            setTheme(isDark);

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    setTheme(!html.classList.contains('dark'));
                });
            }
        })();
    </script>

    {{-- Scroll Reveal Animation Script --}}
    <script>
        (function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));
        })();
    </script>

</body>
</html>
