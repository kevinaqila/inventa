<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - INVENTA</title>
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

        .form-input {
            width: 100%;
            font-size: 0.875rem;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.625rem 0.875rem;
            color: #0f172a;
            transition: all 0.15s;
        }
        .form-input:focus {
            outline: none;
            border-color: #0d9488;
            background-color: #ffffff;
            box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.2);
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.375rem;
        }

        .btn-primary {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background-color: #0d9488;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.875rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.2), 0 2px 4px -2px rgba(13, 148, 136, 0.2);
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-align: center;
        }
        .btn-primary:hover {
            background-color: #0f766e;
        }
        .btn-primary:active {
            transform: scale(0.99);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between antialiased">
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-1 group">
                <img src="/images/inventa-logo.svg" alt="INVENTA Logo" class="w-10 h-10 group-hover:scale-105 transition drop-shadow-sm">
                <img src="/images/inventa-text.svg" alt="INVENTA" class="h-5 w-auto">
            </a>
        </div>
    </header>

    <main class="max-w-md mx-auto w-full px-4 py-8 sm:py-16 flex-1 flex flex-col justify-center">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Masuk ke Akun</h1>
                <p class="text-xs text-slate-500 mt-1">
                    untuk pengajuan peminjaman barang.
                </p>
            </div>

            @if (session('success') || session('status'))
                <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs flex items-start gap-2">
                    <x-heroicon-o-check-circle class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span>{{ session('success') ?? session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs">
                    <ul class="list-disc list-inside space-y-0.5 pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="nama@student.kampus.ac.id" class="form-input">
                </div>

                <div>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Masukkan password Anda" class="form-input">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary">
                        <span>Masuk Akun</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500">
                Belum punya akun peminjam?
                <a href="{{ route('register') }}" class="font-bold text-teal-700 hover:text-teal-800 underline ml-0.5">
                    Daftar di sini
                </a>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} <strong class="text-slate-600 font-semibold">INVENTA</strong> - Sistem Inventaris Kampus
    </footer>
</body>
</html>
