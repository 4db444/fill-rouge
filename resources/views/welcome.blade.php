<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Splity — Split expenses with friends</title>
    <meta name="description" content="Splity makes it easy to split expenses, track balances, and settle up with your group.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(["resources/css/app.css"])
</head>
<body class="font-sans h-screen w-screen overflow-hidden flex items-center justify-center bg-white text-gray-900 relative">
    <main class="text-center px-8 relative z-10">
        <p class="text-sm font-semibold tracking-[0.15em] uppercase text-gray-400 mb-8">Splity</p>
        <h1 class="text-5xl sm:text-6xl md:text-7xl font-bold tracking-tighter leading-none text-black mb-5">Welcome to Splity</h1>
        <p class="text-base sm:text-lg md:text-xl font-normal text-gray-500 leading-relaxed max-w-md mx-auto mb-12">
            Split expenses, track balances, and settle up with your group — effortlessly.
        </p>
        <a href="/auth/login" class="group inline-flex items-center gap-2.5 px-9 py-3.5 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 active:translate-y-0 hover:-translate-y-px transition-all">
            Get Started
            <svg class="w-4.5 h-4.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
        </a>
    </main>
</body>
</html>
