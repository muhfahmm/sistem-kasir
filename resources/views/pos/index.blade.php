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
            <div id="quick_catalog_grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
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
                        <select id="metode_pembayaran" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:border-emerald-500 focus:bg-white transition-all">
                            <option value="cash">Tunai (Cash)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1" id="bayar_input_label">Uang Diterima (Rp)</label>
                        <input type="number" id="bayar_input" oninput="calculateChange()" placeholder="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 font-extrabold focus:border-emerald-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="flex justify-between items-center px-3.5 py-2.5 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-xs text-slate-500 font-medium">Kembalian:</span>
                    <span id="kembalian_display" class="text-sm font-extrabold text-slate-800">Rp 0</span>
                </div>
            </div>

            <button id="checkout_btn" onclick="processCheckout()" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/20 text-sm transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-print"></i> Bayar & Cetak Struk
            </button>
        </div>
    </div>
</div>

<!-- Modal Tambah Produk Cepat -->
<div id="quick_product_modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg overflow-hidden transform transition-all">
        <div class="bg-slate-900 text-white px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-box-open text-emerald-400 text-lg"></i>
                <h3 class="font-bold text-sm tracking-wide">Produk Tidak Ditemukan</h3>
            </div>
            <button onclick="closeQuickProductModal()" class="text-slate-400 hover:text-white transition-colors text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="quick_product_form" onsubmit="submitQuickProduct(event)" class="p-6 space-y-4">
            <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs flex gap-3 items-center">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-base shrink-0"></i>
                <div>
                    <span class="font-bold">Barcode belum terdaftar!</span> Tambahkan detail produk ini untuk menyimpannya ke sistem dan langsung memasukkannya ke keranjang.
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Barcode <span class="text-rose-500">*</span></label>
                    <input type="text" id="modal_barcode" name="barcode" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 font-mono font-bold focus:border-emerald-500 focus:bg-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Produk <span class="text-rose-500">*</span></label>
                    <input type="text" id="modal_nama_produk" name="nama_produk" required placeholder="Contoh: Indomie Goreng 85g" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 font-medium focus:border-emerald-500 focus:bg-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-slate-700">Kategori <span class="text-rose-500">*</span></label>
                        <button type="button" onclick="openPosQuickCategoryModal()" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-plus text-[9px]"></i> Baru
                        </button>
                    </div>
                    <select id="modal_category_id" name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 font-medium focus:border-emerald-500 focus:bg-white focus:outline-none">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Satuan <span class="text-rose-500">*</span></label>
                    <select id="modal_unit_id" name="unit_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 font-medium focus:border-emerald-500 focus:bg-white focus:outline-none">
                        @foreach($units as $u)
                        <option value="{{ $u->id }}">{{ $u->nama_satuan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga Jual (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" id="modal_harga_jual" name="harga_jual" min="0" required placeholder="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 font-mono font-bold focus:border-emerald-500 focus:bg-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Stok Awal <span class="text-rose-500">*</span></label>
                    <input type="number" id="modal_stok" name="stok" min="1" value="10" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 font-mono font-bold focus:border-emerald-500 focus:bg-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Stok Min</label>
                    <input type="number" id="modal_stok_minimal" name="stok_minimal" min="0" value="5" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 font-mono focus:border-emerald-500 focus:bg-white focus:outline-none">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeQuickProductModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition-colors">
                    Batal
                </button>
                <button type="submit" id="submit_quick_prod_btn" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Simpan & Masukkan Keranjang
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Quick Category POS -->
<div id="posQuickCategoryModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-sm overflow-hidden">
        <div class="bg-slate-900 text-white px-5 py-3 flex justify-between items-center">
            <h3 class="font-bold text-xs tracking-wide flex items-center gap-2">
                <i class="fa-solid fa-folder-plus text-emerald-400"></i> Tambah Kategori Baru
            </h3>
            <button onclick="closePosQuickCategoryModal()" class="text-slate-400 hover:text-white transition-colors text-base">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form onsubmit="submitPosQuickCategory(event)" class="p-5 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kategori</label>
                <input type="text" id="pos_quick_nama_kategori" required placeholder="Contoh: Snack & Permen" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 font-medium focus:border-emerald-500 focus:bg-white focus:outline-none">
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closePosQuickCategoryModal()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                <button type="submit" id="submit_pos_cat_btn" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/20">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cart = [];
    const barcodeInput = document.getElementById('barcode_scanner_input');

    // Auto focus ke input scanner saat halaman dimuat & periksa scan dari halaman lain
    window.addEventListener('load', () => {
        barcodeInput.focus();

        const urlParams = new URLSearchParams(window.location.search);
        const autoScanBarcode = urlParams.get('scan');
        if (autoScanBarcode) {
            window.history.replaceState({}, document.title, window.location.pathname);
            fetchProductByBarcode(autoScanBarcode);
        }
    });
    document.addEventListener('click', (e) => {
        const modal = document.getElementById('quick_product_modal');
        const catModal = document.getElementById('posQuickCategoryModal');
        if ((modal && !modal.classList.contains('hidden')) || (catModal && !catModal.classList.contains('hidden'))) return;

        if (!['INPUT', 'SELECT', 'BUTTON', 'TEXTAREA'].includes(e.target.tagName)) {
            barcodeInput.focus();
        }
    });

    // Quick category handlers
    function openPosQuickCategoryModal() {
        document.getElementById('pos_quick_nama_kategori').value = '';
        document.getElementById('posQuickCategoryModal').classList.remove('hidden');
        setTimeout(() => document.getElementById('pos_quick_nama_kategori').focus(), 100);
    }

    function closePosQuickCategoryModal() {
        document.getElementById('posQuickCategoryModal').classList.add('hidden');
    }

    function submitPosQuickCategory(e) {
        e.preventDefault();
        const btn = document.getElementById('submit_pos_cat_btn');
        const nama = document.getElementById('pos_quick_nama_kategori').value.trim();
        if (!nama) return;

        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        fetch('{{ route("categories.quick-store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nama_kategori: nama })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = 'Simpan Kategori';
            if (data.status === 'success') {
                const select = document.getElementById('modal_category_id');
                const opt = document.createElement('option');
                opt.value = data.data.id;
                opt.text = data.data.nama_kategori;
                opt.selected = true;
                select.appendChild(opt);

                closePosQuickCategoryModal();
                alert('Kategori ' + data.data.nama_kategori + ' berhasil disimpan ke database!');
            } else {
                alert(data.message || 'Gagal menyimpan kategori!');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerText = 'Simpan Kategori';
            alert('Terjadi kesalahan saat menambahkan kategori.');
        });
    }

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
                if (res.status === 404) {
                    openQuickProductModal(barcode);
                    return null;
                }
                if (!res.ok) {
                    throw new Error('Gagal memeriksa barcode ' + barcode);
                }
                return res.json();
            })
            .then(data => {
                if (!data) return;
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

    function openQuickProductModal(barcode) {
        document.getElementById('modal_barcode').value = barcode;
        document.getElementById('modal_nama_produk').value = '';
        document.getElementById('modal_harga_jual').value = '';
        document.getElementById('modal_stok').value = '10';
        document.getElementById('modal_stok_minimal').value = '5';
        
        const modal = document.getElementById('quick_product_modal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('modal_nama_produk').focus();
        }, 100);
    }

    function closeQuickProductModal() {
        document.getElementById('quick_product_modal').classList.add('hidden');
        barcodeInput.value = '';
        barcodeInput.focus();
    }

    function submitQuickProduct(e) {
        e.preventDefault();
        const btn = document.getElementById('submit_quick_prod_btn');
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        const payload = {
            barcode: document.getElementById('modal_barcode').value,
            nama_produk: document.getElementById('modal_nama_produk').value,
            category_id: document.getElementById('modal_category_id').value,
            unit_id: document.getElementById('modal_unit_id').value,
            harga_jual: document.getElementById('modal_harga_jual').value,
            stok: document.getElementById('modal_stok').value,
            stok_minimal: document.getElementById('modal_stok_minimal').value,
            _token: '{{ csrf_token() }}'
        };

        fetch('/pos/quick-product', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-plus"></i> Simpan & Masukkan Keranjang';

            if (data.status === 'success') {
                closeQuickProductModal();
                addToCart(data.data);
                appendProductToCatalog(data.data);
            } else {
                alert(data.message || 'Gagal menyimpan produk!');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-plus"></i> Simpan & Masukkan Keranjang';
            alert('Terjadi kesalahan saat menambahkan produk!');
        });
    }

    function appendProductToCatalog(prod) {
        const grid = document.getElementById('quick_catalog_grid');
        if (!grid) return;
        const card = document.createElement('div');
        card.onclick = () => addToCart(prod);
        card.className = "p-3 bg-slate-50 hover:bg-emerald-50/50 border border-slate-200 hover:border-emerald-300 rounded-xl cursor-pointer transition-all flex flex-col justify-between group shadow-sm";
        card.innerHTML = `
            <div>
                <span class="text-[10px] font-mono text-slate-400 group-hover:text-emerald-700">${prod.barcode}</span>
                <h4 class="font-bold text-xs text-slate-800 line-clamp-2 mt-0.5">${prod.nama_produk}</h4>
            </div>
            <div class="mt-3 flex items-center justify-between border-t border-slate-200/80 pt-2">
                <span class="font-extrabold text-xs text-emerald-600">Rp ${Number(prod.harga_jual).toLocaleString('id-ID')}</span>
                <span class="text-[10px] text-slate-500 font-semibold bg-white px-1.5 py-0.5 rounded border border-slate-200">Stok: ${prod.stok}</span>
            </div>
        `;
        grid.prepend(card);
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
                harga_jual: parseFloat(product.harga_jual),
                qty: 1,
                stok: parseInt(product.stok)
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
                            <span class="font-bold px-1">${item.qty}</span>
                            <button onclick="updateQty(${item.id}, 1)" class="w-5 h-5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded flex items-center justify-center text-xs font-bold">+</button>
                        </div>
                    </td>
                    <td class="py-2 px-2 text-right font-extrabold text-emerald-600">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    <td class="py-2 px-2 text-center">
                        <button onclick="removeItem(${item.id})" class="text-rose-500 hover:text-rose-700"><i class="fa-solid fa-xmark"></i></button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
        document.getElementById('grand_total_display').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    function calculateChange() {
        const total = cart.reduce((sum, item) => sum + (item.harga_jual * item.qty), 0);
        const bayarInput = document.getElementById('bayar_input');

        const bayar = parseFloat(bayarInput.value) || 0;
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

        const method = document.getElementById('metode_pembayaran').value;
        const total = cart.reduce((sum, item) => sum + (item.harga_jual * item.qty), 0);
        let bayar = parseFloat(document.getElementById('bayar_input').value) || 0;

        if (method === 'qris' || method === 'transfer') {
            bayar = total;
            document.getElementById('bayar_input').value = total;
        }

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
                alert(data.message || "Gagal memproses transaksi!");
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

