<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found | Splity</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(["resources/css/app.css"])
</head>
<body class="font-sans min-h-screen flex flex-col bg-white text-gray-900">

    <header class="border-b border-gray-200 bg-white">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/dashboard" class="text-lg font-semibold tracking-tight text-black no-underline">Splity</a>
        </div>
    </header>

    <div class="flex-1 flex items-center justify-center px-6 py-8">
        <div class="text-center max-w-md">
            <div class="text-7xl sm:text-8xl font-bold leading-none text-black tracking-tighter mb-2">404</div>
            <div class="w-12 h-0.5 bg-gray-300 mx-auto my-6"></div>
            <h1 class="text-xl font-semibold text-gray-900 mb-3">Page not found</h1>
            <p class="text-sm text-gray-500 leading-relaxed mb-8">
                The page you're looking for doesn't exist or has been moved.
                Check the URL or head back to familiar territory.
            </p>
            <div class="flex gap-3 justify-center flex-wrap">
                <a href="/dashboard" class="inline-flex items-center gap-2 px-6 py-2.5 bg-black text-white text-sm font-medium rounded-md hover:bg-gray-800 transition-colors no-underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                    Dashboard
                </a>
                <a href="javascript:history.back()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-gray-600 text-sm font-medium rounded-md border border-gray-300 hover:border-gray-400 hover:text-gray-900 transition-all no-underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Go Back
                </a>
            </div>
        </div>
    </div>

    <footer class="border-t border-gray-200 bg-white">
        <div class="max-w-4xl mx-auto px-6 py-6 text-center">
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Splity. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
