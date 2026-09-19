<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Kasir POS')</title>
    <!-- Tailwind CSS CDN & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex antialiased">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 hidden md:flex sticky top-0 h-screen">
        <div>
            <!-- Brand Logo -->
            <div class="h-16 px-6 flex items-center gap-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-emerald-500/20">
                    <i class="fa-solid fa-cash-register"></i>
                </div>
                <div>
                    <h1 class="font-bold text-base text-slate-900 leading-none">KasirPOS</h1>
                    <span class="text-[11px] text-emerald-600 font-bold block truncate max-w-[130px] mt-1" title="{{ Auth::user()->name ?? 'Administrator' }}">
                        <i class="fa-solid fa-circle text-[7px] text-emerald-500 mr-1 inline-block align-middle"></i>{{ Auth::user()->name ?? 'Administrator' }}
                    </span>
                </div>
            </div>

            <!-- Menu Links -->
            <div class="p-4 space-y-1">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Menu Utama</span>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-base {{ request()->routeIs('dashboard') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pos.*') ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-barcode w-5 text-center text-base {{ request()->routeIs('pos.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Kasir (POS)</span>
                </a>

                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('products.*') ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center text-base {{ request()->routeIs('products.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Produk & Stok</span>
                </a>

                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('categories.*') ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-tags w-5 text-center text-base {{ request()->routeIs('categories.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Kategori Produk</span>
                </a>

                <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('transactions.*') ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-receipt w-5 text-center text-base {{ request()->routeIs('transactions.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Riwayat Transaksi</span>
                </a>
            </div>
        </div>

        <!-- User Profile Card in Sidebar Bottom -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm border border-emerald-200 shrink-0">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <h4 class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Pengguna' }}</h4>
                        <span class="text-[10px] uppercase font-bold text-emerald-600 block">{{ Auth::user()->role ?? 'Admin' }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Keluar (Logout)">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Right Main Wrapper -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar Header -->
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aplikasi Kasir POS</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('pos.index') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-cart-shopping"></i> Terminal POS
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-2 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 font-semibold rounded-xl text-xs border border-slate-200 transition-all flex items-center gap-1.5" title="Logout">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 p-6 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg"></i>
                        <span class="text-sm font-semibold">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- HTML5-QRCode Library for Web Cam Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>


    <!-- Global Barcode Scanner Listener Script -->
    <script>
    (function() {
        if (window.location.pathname.startsWith('/pos')) return;

        let globalBarcodeBuffer = '';
        let globalScanTimer;

        document.addEventListener('keydown', function(e) {
            const targetTag = e.target.tagName;
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(targetTag) || e.target.isContentEditable) {
                return;
            }

            if (e.key === 'Enter') {
                if (globalBarcodeBuffer.trim().length >= 3) {
                    const barcode = globalBarcodeBuffer.trim();
                    globalBarcodeBuffer = '';
                    window.location.href = `/pos?scan=${encodeURIComponent(barcode)}`;
                }
                globalBarcodeBuffer = '';
            } else if (e.key.length === 1) {
                globalBarcodeBuffer += e.key;
                clearTimeout(globalScanTimer);
                globalScanTimer = setTimeout(() => {
                    if (globalBarcodeBuffer.trim().length >= 6) {
                        const barcode = globalBarcodeBuffer.trim();
                        globalBarcodeBuffer = '';
                        window.location.href = `/pos?scan=${encodeURIComponent(barcode)}`;
                    } else {
                        globalBarcodeBuffer = '';
                    }
                }, 200);
            }
        });
    })();
    </script>

    @stack('scripts')
</body>
</html>
