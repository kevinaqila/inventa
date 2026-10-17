<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'INVENTA - Sistem Peminjaman Inventaris Kampus')</title>
    <link rel="icon" type="image/svg+xml" href="/images/inventa-logo.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background-color: #0d9488;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.875rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.2), 0 2px 4px -2px rgba(13, 148, 136, 0.2);
            transition: all 0.15s;
        }
        .btn-primary:hover {
            background-color: #0f766e;
        }
        .btn-primary:active {
            transform: scale(0.99);
        }
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
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between antialiased pb-20 sm:pb-0">
    @php
        $activeCount = auth()->check()
            ? \App\Models\Loan::where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'borrowed', 'return_pending'])
                ->count()
            : 0;
    @endphp

    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between relative">
            <a href="{{ route('home') }}" class="flex items-center gap-1.5 group">
                <img src="/images/inventa-logo.svg" alt="INVENTA Logo" class="w-9 h-9 group-hover:scale-105 transition drop-shadow-sm">
                <img src="/images/inventa-text.svg" alt="INVENTA" class="h-4 sm:h-5 w-auto">
            </a>

            <nav class="hidden md:flex items-center gap-1 absolute left-1/2 -translate-x-1/2">
                <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('home') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Beranda
                </a>

                <a href="{{ route('loans.create') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('loans.create') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Pinjam Barang
                </a>

                <a href="{{ route('loans.my') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ request()->routeIs('loans.my') || request()->routeIs('loans.return') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span>Peminjaman Saya</span>
                    @if($activeCount > 0)
                        <span class="w-5 h-5 rounded-full bg-teal-600 text-white text-[10px] font-extrabold flex items-center justify-center">
                            {{ $activeCount }}
                        </span>
                    @endif
                </a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">

                @auth
                    <div class="relative" id="profile-dropdown-container">
                        <button type="button" id="btn-profile-dropdown" aria-expanded="false" aria-haspopup="true" class="flex items-center gap-2 px-2.5 sm:px-3 py-1.5 rounded-full bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 transition focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                            <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-xs font-bold text-slate-800 leading-tight truncate max-w-[130px] hidden sm:inline">{{ auth()->user()->name }}</span>
                        </button>

                        <div id="profile-dropdown-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200 py-1 z-50 transition-all">
                            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/70 rounded-t-2xl">
                                <p class="text-xs font-bold text-slate-900 leading-tight truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-slate-500 font-medium mt-1 truncate">{{ auth()->user()->nim }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5 truncate">{{ auth()->user()->study_program ?? 'Mahasiswa' }}</p>
                            </div>

                            <div class="py-1">
                                <button type="button" id="btn-open-profile-modal" class="w-full text-left px-4 py-2 text-xs font-semibold text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit Profil</span>
                                </button>
                            </div>

                            <div class="border-t border-slate-100 pt-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 px-3 py-1.5 rounded-xl hover:bg-slate-100 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-xs font-semibold text-white bg-teal-600 hover:bg-teal-700 px-3.5 py-1.5 rounded-xl shadow-sm transition">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <main class="w-full flex-1">
        @if(session('success'))
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium flex items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-medium flex items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="py-6 border-t border-slate-200/80 bg-white/50 text-center text-xs text-slate-500">
        <div class="max-w-6xl mx-auto px-4">
            &copy; {{ date('Y') }} <strong class="text-slate-700 font-semibold">INVENTA</strong> &bull; Dikembangkan oleh <strong class="text-slate-700 font-semibold">Tim NusaWorks</strong> | S1 Sistem Informasi FT UNESA
        </div>
    </footer>

    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2 flex items-center justify-around shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-[11px] font-bold py-1 px-3 rounded-xl transition {{ request()->routeIs('home') ? 'text-teal-700' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('home') ? 'text-teal-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Beranda</span>
        </a>

        <a href="{{ route('loans.create') }}" class="flex flex-col items-center gap-1 text-[11px] font-bold py-1 px-3 rounded-xl transition {{ request()->routeIs('loans.create') ? 'text-teal-700' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('loans.create') ? 'text-teal-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Pinjam</span>
        </a>

        <a href="{{ route('loans.my') }}" class="relative flex flex-col items-center gap-1 text-[11px] font-bold py-1 px-3 rounded-xl transition {{ request()->routeIs('loans.my') || request()->routeIs('loans.return') ? 'text-teal-700' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('loans.my') || request()->routeIs('loans.return') ? 'text-teal-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span>Peminjaman Saya</span>
            @if($activeCount > 0)
                <span class="absolute top-0.5 right-8 w-4 h-4 rounded-full bg-teal-600 text-white text-[9px] font-extrabold flex items-center justify-center">
                    {{ $activeCount }}
                </span>
            @endif
        </a>
    </nav>

    @auth
        <div id="profile-modal-backdrop" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
            <div id="profile-modal-panel" class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden my-8 transition-all">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 leading-tight">Edit Profil</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Perbarui data diri akun Anda</p>
                    </div>
                    <button type="button" id="btn-close-profile-modal" class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition" title="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="px-6 space-y-3">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="prof_name" class="form-label">Nama Lengkap</label>
                        <input type="text" id="prof_name" name="name" value="{{ old('name', auth()->user()->name) }}" required class="form-input">
                        @error('name')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="prof_nim" class="form-label">NIM (Nomor Induk Mahasiswa)</label>
                            <input type="text" id="prof_nim" name="nim" value="{{ old('nim', auth()->user()->nim) }}" required class="form-input">
                            @error('nim')
                                <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prof_prodi" class="form-label">Program Studi</label>
                            <input type="text" id="prof_prodi" name="study_program" value="{{ old('study_program', auth()->user()->study_program) }}" required class="form-input" placeholder="S1 Sistem Informasi">
                            @error('study_program')
                                <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="prof_phone" class="form-label">Nomor WhatsApp Aktif</label>
                            <input type="text" id="prof_phone" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" required class="form-input" placeholder="08xxxxxxxxxx">
                            @error('phone_number')
                                <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prof_email" class="form-label">Email Akun</label>
                            <input type="email" id="prof_email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="form-input">
                            @error('email')
                                <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-2">
                        <label for="prof_password" class="form-label">Ganti Password <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin diubah)</span></label>
                        <input type="password" id="prof_password" name="password" class="form-input" placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="py-3 flex items-center justify-end gap-2.5">
                        <button type="button" id="btn-cancel-profile-modal" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                            Batal
                        </button>
                        <button type="submit" class="btn-primary py-2.5 px-5 text-xs font-bold">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const btnDropdown = document.getElementById('btn-profile-dropdown');
                const menuDropdown = document.getElementById('profile-dropdown-menu');
                const dropdownContainer = document.getElementById('profile-dropdown-container');

                const modalBackdrop = document.getElementById('profile-modal-backdrop');
                const btnOpenModal = document.getElementById('btn-open-profile-modal');
                const btnCloseModal = document.getElementById('btn-close-profile-modal');
                const btnCancelModal = document.getElementById('btn-cancel-profile-modal');

                if (btnDropdown && menuDropdown) {
                    btnDropdown.addEventListener('click', function (e) {
                        e.stopPropagation();
                        menuDropdown.classList.toggle('hidden');
                    });

                    document.addEventListener('click', function (e) {
                        if (dropdownContainer && !dropdownContainer.contains(e.target)) {
                            menuDropdown.classList.add('hidden');
                        }
                    });
                }

                function openProfileModal() {
                    if (menuDropdown) menuDropdown.classList.add('hidden');
                    if (modalBackdrop) {
                        modalBackdrop.classList.remove('hidden');
                        document.body.classList.add('overflow-hidden');
                    }
                }

                function closeProfileModal() {
                    if (modalBackdrop) {
                        modalBackdrop.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                }

                if (btnOpenModal) btnOpenModal.addEventListener('click', openProfileModal);
                if (btnCloseModal) btnCloseModal.addEventListener('click', closeProfileModal);
                if (btnCancelModal) btnCancelModal.addEventListener('click', closeProfileModal);

                if (modalBackdrop) {
                    modalBackdrop.addEventListener('click', function (e) {
                        if (e.target === modalBackdrop) {
                            closeProfileModal();
                        }
                    });
                }

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && modalBackdrop && !modalBackdrop.classList.contains('hidden')) {
                        closeProfileModal();
                    }
                });

                @if($errors->hasAny(['name', 'nim', 'study_program', 'phone_number', 'email', 'password']))
                    openProfileModal();
                @endif
            });
        </script>
    @endauth

    @stack('scripts')
</body>
</html>
