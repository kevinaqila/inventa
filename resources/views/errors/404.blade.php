<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | INVENTA</title>
    <link rel="icon" type="image/svg+xml" href="/images/inventa-logo.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between antialiased selection:bg-teal-500 selection:text-white">
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-1 group">
                <img src="/images/inventa-logo.svg" alt="INVENTA Logo" class="w-10 h-10 group-hover:scale-105 transition drop-shadow-sm">
                <img src="/images/inventa-text.svg" alt="INVENTA" class="h-5 w-auto">
            </a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-sm text-center space-y-5">
            <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 border border-teal-200 flex items-center justify-center mx-auto text-xl font-extrabold shadow-xs">
                404
            </div>

            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Halaman Tidak Ditemukan
                </h1>
            </div>

            <div class="pt-2 flex justify-center">
                <a href="{{ url('/') }}" class="px-6 py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs transition shadow-sm inline-flex items-center justify-center gap-2">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} <strong class="text-slate-600 font-semibold">INVENTA</strong> - Sistem Inventaris Kampus
    </footer>
</body>
</html>
