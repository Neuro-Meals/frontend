<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth {{ app()->getLocale() === 'ar' ? 'rtl' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Nutrio Meals'))</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css">

    {{-- Apply theme before page renders to prevent flash --}}
    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = saved ? saved === 'dark' : prefersDark;
            if (isDark) document.documentElement.classList.add('dark');
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">

    @include('partials.loading')

    @yield('content')

    {{-- Dark Mode Toggle Script --}}
    <script>
        (function() {
            const html = document.documentElement;
            const toggleBtn = document.getElementById('theme-toggle');
            const iconSun = document.getElementById('theme-icon-sun');
            const iconMoon = document.getElementById('theme-icon-moon');
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

            function updateIcons(isDark) {
                if (iconSun && iconMoon) {
                    iconSun.classList.toggle('hidden', isDark);
                    iconMoon.classList.toggle('hidden', !isDark);
                }
            }

            function setTheme(dark, save) {
                if (dark) {
                    html.classList.add('dark');
                    if (save) localStorage.setItem('theme', 'dark');
                } else {
                    html.classList.remove('dark');
                    if (save) localStorage.setItem('theme', 'light');
                }
                updateIcons(dark);
            }

            // On load: use saved preference, otherwise follow system
            const saved = localStorage.getItem('theme');
            const isDark = saved ? saved === 'dark' : mediaQuery.matches;
            setTheme(isDark, false);

            // Listen for system theme changes - only auto-switch if user hasn't manually set a preference
            mediaQuery.addEventListener('change', function(e) {
                if (!localStorage.getItem('theme')) {
                    setTheme(e.matches, false);
                }
            });

            // Manual toggle - saves preference
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    setTheme(!html.classList.contains('dark'), true);
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

    {{-- Hero Particle Network Animation --}}
    <script>
        (function() {
            const canvas = document.getElementById('hero-particles');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let width, height, particles = [];
            const particleCount = 45;
            const maxDistance = 120;
            const isDark = () => document.documentElement.classList.contains('dark');

            function resize() {
                const section = canvas.parentElement;
                width = section.offsetWidth;
                height = section.offsetHeight;
                canvas.width = width;
                canvas.height = height;
            }

            function initParticles() {
                particles = [];
                for (let i = 0; i < particleCount; i++) {
                    particles.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        vx: (Math.random() - 0.5) * 0.5,
                        vy: (Math.random() - 0.5) * 0.5,
                        radius: Math.random() * 2 + 1.5
                    });
                }
            }

            function draw() {
                ctx.clearRect(0, 0, width, height);
                const dark = isDark();
                const dotColor = dark ? 'rgba(110,122,37, 0.6)' : 'rgba(110,122,37, 0.5)';
                const lineColor = dark ? 'rgba(110,122,37, 0.15)' : 'rgba(3, 49, 51, 0.12)';

                for (let i = 0; i < particles.length; i++) {
                    const p = particles[i];
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0 || p.x > width) p.vx *= -1;
                    if (p.y < 0 || p.y > height) p.vy *= -1;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = dotColor;
                    ctx.fill();

                    for (let j = i + 1; j < particles.length; j++) {
                        const q = particles[j];
                        const dx = p.x - q.x;
                        const dy = p.y - q.y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < maxDistance) {
                            ctx.beginPath();
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(q.x, q.y);
                            ctx.strokeStyle = lineColor;
                            ctx.lineWidth = 1 - dist / maxDistance;
                            ctx.stroke();
                        }
                    }
                }

                requestAnimationFrame(draw);
            }

            resize();
            initParticles();
            draw();

            window.addEventListener('resize', () => {
                resize();
                initParticles();
            });
        })();
    </script>

</body>
</html>
