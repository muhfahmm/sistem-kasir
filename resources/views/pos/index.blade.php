@extends('layouts.app')

@section('title', 'Terminal Kasir (POS)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-[calc(100vh-140px)]">
    <!-- Left Column: Barcode Reader & Product Catalog Grid (7 Cols) -->
    <div class="lg:col-span-7 flex flex-col gap-4">
        <!-- Scan Barcode Bar -->
        <div class="bg-white border border-slate-200 p-4 rounded-2xl shadow-sm flex gap-3 items-center">
            <div class="relative flex-1">
                <i class="fa-solid fa-barcode absolute left-3.5 top-3.5 text-emerald-600 text-base"></i>
                <input type="text" id="barcode_scanner_input" autofocus placeholder="Scan Barcode produk / tekan Enter..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 font-mono text-sm focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500 transition-all">
            </div>
            <button onclick="triggerCamera()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-semibold rounded-xl text-xs flex items-center gap-2 transition-all">
                <i class="fa-solid fa-camera"></i> Scan Cam
            </button>
        </div>

        <div id="cam_reader" class="hidden bg-white border border-slate-200 p-3 rounded-2xl max-w-sm mx-auto w-full shadow-sm"></div>

        <!-- Catalog Items -->
        <div class="bg-white border border-slate-200 p-4 rounded-2xl shadow-sm flex-1 overflow-y-auto">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Katalog Produk Cepat</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($products as $prod)
                <div onclick="addToCart({{ json_encode($prod) }})" class="p-3 bg-slate-50 hover:bg-emerald-50/50 border border-slate-200 hover:border-emerald-300 rounded-xl cursor-pointer transition-all flex flex-col justify-between group shadow-sm">
                    <div>
                        <span class="text-[10px] font-mono text-slate-400 group-hover:text-emerald-700">{{ $prod->barcode }}</span>
                        <h4 class="font-bold text-xs text-slate-800 line-clamp-2 mt-0.5">{{ $prod->nama_produk }}</h4>
                    </div>
                    <div class="mt-3 flex items-center justify-between border-t border-slate-200/80 pt-2">
                        <span class="font-extrabold text-xs text-emerald-600">Rp {{ number_format($prod->harga_jual, 0, ',', '.') }}</span>
                        <span class="text-[10px] text-slate-500 font-semibold bg-white px-1.5 py-0.5 rounded border border-slate-200">Stok: {{ $prod->stok }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Column: Cart items & Checkout Payment Panel (5 Cols) -->
    <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-cart-shopping text-emerald-600"></i> Keranjang Belanja
                </h3>
                <button onclick="clearCart()" class="text-xs font-semibold text-rose-500 hover:underline">Kosongkan</button>
            </div>

            <!-- Cart Table Items -->
            <div class="max-h-[280px] overflow-y-auto pr-1">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[9px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-2 px-2">Item</th>
                            <th class="py-2 px-2 text-center">Qty</th>
                            <th class="py-2 px-2 text-right">Subtotal</th>
                            <th class="py-2 px-2 text-center">#</th>
                        </tr>
                    </thead>
                    <tbody id="cart_tbody" class="divide-y divide-slate-100">
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">Keranjang kosong. Scan barcode produk untuk memulai!</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total & Payment Form -->
        <div class="border-t border-slate-100 pt-4 space-y-3">
            <div class="flex justify-between items-center text-slate-700 text-sm">
                <span class="font-semibold">Total Tagihan:</span>
                <span id="grand_total_display" class="text-2xl font-black text-emerald-600">Rp 0</span>
            </div>

            <div class="space-y-2">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Metode Bayar</label>
                        <select id="metode_pembayaran" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-medium focus:border-emerald-500 focus:bg-white">
                            <option value="cash">Tunai (Cash)</option>
                            <option value="qris">QRIS</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Uang Diterima (Rp)</label>
                        <input type="number" id="bayar_input" oninput="calculateChange()" placeholder="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 font-mono font-bold focus:border-emerald-500 focus:bg-white">
                    </div>
                </div>

                <div class="flex justify-between items-center px-3.5 py-2.5 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-xs text-slate-500 font-medium">Kembalian:</span>
                    <span id="kembalian_display" class="text-sm font-mono font-bold text-slate-800">Rp 0</span>
                </div>
            </div>

            <button onclick="processCheckout()" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/20 text-sm transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-print"></i> Bayar & Cetak Struk
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cart = [];
    const barcodeInput = document.getElementById('barcode_scanner_input');

    // Auto focus ke input scanner saat halaman dimuat & klik di mana saja
    window.addEventListener('load', () => barcodeInput.focus());
    document.addEventListener('click', (e) => {
        if (!['INPUT', 'SELECT', 'BUTTON', 'TEXTAREA'].includes(e.target.tagName)) {
            barcodeInput.focus();
        }
    });

    // Scanner keydown & input listener
    let scanTimeout;
    barcodeInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            processScanInput();
        }
    });

    // Jika scanner tidak mengirimkan 'Enter', otomatis deteksi setelah jeda ketik cepat (150ms)
    barcodeInput.addEventListener('input', function() {
        clearTimeout(scanTimeout);
        scanTimeout = setTimeout(() => {
            if (this.value.trim().length >= 5) {
                processScanInput();
            }
        }, 200);
    });

    function processScanInput() {
        const barcode = barcodeInput.value.trim();
        if (barcode !== '') {
            fetchProductByBarcode(barcode);
            barcodeInput.value = '';
        }
    }

    function fetchProductByBarcode(barcode) {
        fetch(`/pos/barcode/${barcode}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error('Produk dengan barcode ' + barcode + ' tidak ditemukan!');
                }
                return res.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    addToCart(data.data);
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                alert(err.message);
                barcodeInput.value = '';
                barcodeInput.focus();
            });
    }

    function addToCart(product) {
        const existing = cart.find(item => item.id === product.id);
        if (existing) {
            if (existing.qty + 1 > product.stok) {
                alert(`Stok produk tidak mencukupi! Sisa stok: ${product.stok}`);
                return;
            }
            existing.qty++;
        } else {
            cart.push({
                id: product.id,
                nama_produk: product.nama_produk,
                harga_jual: product.harga_jual,
                qty: 1,
                stok: product.stok
            });
        }
        renderCart();
    }

    function updateQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (item) {
            if (delta > 0 && item.qty + delta > item.stok) {
                alert(`Stok maksimal tercapai (${item.stok})`);
                return;
            }
            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
        }
        renderCart();
    }

    function removeItem(id) {
        cart = cart.filter(i => i.id !== id);
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function renderCart() {
        const tbody = document.getElementById('cart_tbody');
        if (cart.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="py-8 text-center text-slate-400">Keranjang kosong. Scan barcode produk untuk memulai!</td></tr>`;
            document.getElementById('grand_total_display').innerText = 'Rp 0';
            calculateChange();
            return;
        }

        let html = '';
        let total = 0;

        cart.forEach(item => {
            const subtotal = item.harga_jual * item.qty;
            total += subtotal;
            html += `
                <tr class="hover:bg-slate-50">
                    <td class="py-2 px-2 font-bold text-slate-800">${item.nama_produk}</td>
                    <td class="py-2 px-2 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <button onclick="updateQty(${item.id}, -1)" class="w-5 h-5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded flex items-center justify-center text-xs font-bold">-</button>
                            <span class="font-mono px-1">${item.qty}</span>
                            <button onclick="updateQty(${item.id}, 1)" class="w-5 h-5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded flex items-center justify-center text-xs font-bold">+</button>
                        </div>
                    </td>
                    <td class="py-2 px-2 text-right font-mono font-bold text-emerald-600">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    <td class="py-2 px-2 text-center">
                        <button onclick="removeItem(${item.id})" class="text-rose-500 hover:text-rose-700"><i class="fa-solid fa-xmark"></i></button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
        document.getElementById('grand_total_display').innerText = 'Rp ' + total.toLocaleString('id-ID');
        calculateChange();
    }

    function calculateChange() {
        const total = cart.reduce((sum, item) => sum + (item.harga_jual * item.qty), 0);
        const bayar = parseFloat(document.getElementById('bayar_input').value) || 0;
        const kembali = bayar - total;
        const display = document.getElementById('kembalian_display');

        if (kembali >= 0) {
            display.innerText = 'Rp ' + kembali.toLocaleString('id-ID');
            display.classList.remove('text-rose-600');
            display.classList.add('text-slate-800');
        } else {
            display.innerText = 'Kurang Rp ' + Math.abs(kembali).toLocaleString('id-ID');
            display.classList.remove('text-slate-800');
            display.classList.add('text-rose-600');
        }
    }

    function processCheckout() {
        if (cart.length === 0) {
            alert("Keranjang masih kosong!");
            return;
        }

        const bayar = parseFloat(document.getElementById('bayar_input').value) || 0;
        const total = cart.reduce((sum, item) => sum + (item.harga_jual * item.qty), 0);

        if (bayar < total) {
            alert("Uang pembayaran belum cukup!");
            return;
        }

        const payload = {
            items: cart.map(i => ({ id: i.id, qty: i.qty })),
            bayar: bayar,
            metode_pembayaran: document.getElementById('metode_pembayaran').value,
            _token: '{{ csrf_token() }}'
        };

        fetch('/pos/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                window.open(`/pos/receipt/${data.transaction_id}`, '_blank', 'width=400,height=600');
                clearCart();
                document.getElementById('bayar_input').value = '';
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(err => alert("Terjadi kesalahan saat memproses checkout!"));
    }

    let posQrScanner;
    function triggerCamera() {
        const div = document.getElementById('cam_reader');
        div.classList.toggle('hidden');
        if (!div.classList.contains('hidden')) {
            posQrScanner = new Html5Qrcode("cam_reader");
            posQrScanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 220, height: 140 } },
                (text) => {
                    fetchProductByBarcode(text);
                    posQrScanner.stop();
                    div.classList.add('hidden');
                },
                (err) => {}
            );
        } else if (posQrScanner) {
            posQrScanner.stop();
        }
    }
</script>
@endpush
