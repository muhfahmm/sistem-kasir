<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KasirPOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex items-center justify-center p-4 antialiased selection:bg-emerald-500 selection:text-white">
    <!-- Background Decor Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-emerald-200/30 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-emerald-300/20 blur-3xl"></div>
    </div>

    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl mx-auto mb-3 shadow-lg shadow-emerald-600/30">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang di KasirPOS</h1>
            <p class="text-slate-500 text-xs mt-1 font-medium">Silakan masuk dengan Username & Password Anda</p>
        </div>

        <!-- Alert Error -->
        @if($errors->any())
            <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-base shrink-0"></i>
                <div class="font-medium">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                <div class="font-medium">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Card Login Form -->
        <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-xl shadow-slate-200/60 space-y-5">
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Username</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                        <input type="text" name="username" value="{{ old('username') }}" required placeholder="Ketik username Anda..." autocomplete="username" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-800 text-xs font-medium placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                        <input type="password" id="password" name="password" required placeholder="••••••••" autocomplete="current-password" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-10 py-2.5 text-slate-800 text-xs font-medium placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        <button type="button" onclick="togglePassword()" class="absolute right-3.5 top-3 text-slate-400 hover:text-slate-600 transition-colors">
                            <i id="toggleIcon" class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600 hover:text-slate-800">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="font-medium">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/25 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Sistem
                </button>
            </form>

            <div class="border-t border-slate-100 pt-4 text-center">
                <p class="text-xs text-slate-500 font-medium">
                    Belum memiliki akun? 
                    <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline ml-1">Daftar Akun Baru</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>

