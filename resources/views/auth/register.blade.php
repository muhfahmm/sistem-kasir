<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun Baru - KasirPOS</title>
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

    <div class="w-full max-w-md my-8">
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl mx-auto mb-3 shadow-lg shadow-emerald-600/30">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Daftar Akun Baru</h1>
            <p class="text-slate-500 text-xs mt-1 font-medium">Buat akun Admin atau Kasir baru untuk mengakses KasirPOS</p>
        </div>

        <!-- Alert Error -->
        @if($errors->any())
            <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1 shadow-sm">
                <div class="font-bold flex items-center gap-2 text-rose-600">
                    <i class="fa-solid fa-circle-exclamation"></i> Gagal Registrasi:
                </div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Register Form -->
        <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-xl shadow-slate-200/60 space-y-5">
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <i class="fa-solid fa-id-card absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-800 text-xs font-medium placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Username</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                        <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: admin_toko" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-800 text-xs font-medium placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Role Pengguna</label>
                    <div class="relative">
                        <i class="fa-solid fa-user-shield absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                        <select name="role" required class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-800 text-xs font-medium focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                            <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir (Terminal POS)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Kata Sandi</label>
                        <input type="password" name="password" required placeholder="Min 6 karakter" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 text-xs font-medium placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Konfirmasi Sandi</label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi sandi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 text-xs font-medium placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/25 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-check"></i> Buat Akun Sekarang
                </button>
            </form>

            <div class="border-t border-slate-100 pt-4 text-center">
                <p class="text-xs text-slate-500 font-medium">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline ml-1">Masuk ke Sistem</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
