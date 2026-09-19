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
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center text-3xl mx-auto mb-4 shadow-xl shadow-emerald-500/10 backdrop-blur-md">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Selamat Datang di KasirPOS</h1>
            <p class="text-slate-400 text-xs mt-1 font-medium">Silakan masuk dengan akun Admin atau Kasir Anda</p>
        </div>

        <!-- Alert Error -->
        @if($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-rose-400 text-base shrink-0"></i>
                <div>
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-5 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-400 text-base shrink-0"></i>
                <div>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Card Login Form -->
        <div class="bg-slate-800/80 backdrop-blur-xl border border-slate-700/60 rounded-3xl p-7 shadow-2xl space-y-5">
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-slate-500 text-xs"></i>
                        <input type="email" name="email" value="{{ old('email', 'admin@pos.com') }}" required placeholder="nama@toko.com" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl pl-10 pr-4 py-2.5 text-white text-xs font-medium placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Kata Sandi (Password)</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-500 text-xs"></i>
                        <input type="password" name="password" required value="password" placeholder="••••••••" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl pl-10 pr-4 py-2.5 text-white text-xs font-medium placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-300">
                        <input type="checkbox" name="remember" class="rounded border-slate-700 bg-slate-900 text-emerald-500 focus:ring-emerald-500">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-600/25 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Sistem
                </button>
            </form>

            <div class="border-t border-slate-700/60 pt-4 text-center">
                <p class="text-xs text-slate-400">
                    Belum memiliki akun? 
                    <a href="{{ route('register') }}" class="font-bold text-emerald-400 hover:text-emerald-300 hover:underline ml-1">Daftar Akun Baru</a>
                </p>
            </div>
        </div>

        <!-- Demo Account Box -->
        <div class="mt-6 p-4 rounded-2xl bg-slate-800/40 border border-slate-800 text-xs text-slate-400 space-y-1 text-center">
            <span class="font-bold text-slate-300 block mb-1">Akun Demo Bawaan:</span>
            <div class="flex justify-center gap-4 text-[11px] font-mono">
                <div><span class="text-emerald-400 font-semibold">Admin:</span> admin@pos.com / password</div>
                <div><span class="text-sky-400 font-semibold">Kasir:</span> kasir@pos.com / password</div>
            </div>
        </div>
    </div>
</body>
</html>
