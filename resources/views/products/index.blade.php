@extends('layouts.app')

@section('title', 'Manajemen Produk & Barcode')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Manajemen Produk & Stok</h2>
            <p class="text-xs text-slate-500">Tambah produk baru secara manual atau scan dengan Barcode Reader.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openModal()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2 text-sm">
                <i class="fa-solid fa-plus"></i> Tambah Produk Baru
            </button>
        </div>
    </div>

    <!-- Search & Table -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <form method="GET" action="{{ route('products.index') }}" class="mb-5 flex items-center gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama produk atau kode barcode..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-xl text-xs transition-all">Cari</button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Barcode / SKU</th>
                        <th class="py-3 px-4">Nama Produk</th>
                        <th class="py-3 px-4">Kategori & Satuan</th>
                        <th class="py-3 px-4">Harga Jual</th>
                        <th class="py-3 px-4">Stok Unit</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 border border-slate-200 rounded-lg text-emerald-700">
                                <i class="fa-solid fa-barcode text-xs"></i> {{ $p->barcode }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">{{ $p->nama_produk }}</td>
                        <td class="py-3.5 px-4 text-slate-500">
                            <span class="text-slate-700 font-semibold">{{ $p->category->nama_kategori }}</span> / {{ $p->unit->nama_satuan }}
                        </td>
                        <td class="py-3.5 px-4 font-extrabold text-emerald-600 text-sm">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-4">
                            @if($p->stok <= $p->stok_minimal)
                                <span class="px-2.5 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700 font-bold">
                                    {{ $p->stok }} {{ $p->unit->nama_satuan }} (Min: {{ $p->stok_minimal }})
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold">
                                    {{ $p->stok }} {{ $p->unit->nama_satuan }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button type="button" onclick='openEditProductModal(@json($p))' class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Edit Produk">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus Produk">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada produk ditemukan. Tambahkan produk pertama Anda!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Modal Form Tambah Produk (Tanpa HPP) -->
<div id="productModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center hidden">
    <div class="bg-white border border-slate-200 w-full max-w-xl rounded-2xl p-6 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-box-open text-emerald-600"></i> Tambah Produk Baru
            </h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Kode Barcode / SKU</label>
                <input type="text" id="barcode_input" name="barcode" required placeholder="Arahkan scanner fisik atau ketik..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-mono text-sm focus:border-emerald-500 focus:bg-white focus:outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Nama Produk</label>
                <input type="text" name="nama_produk" required placeholder="Contoh: Air Mineral Aqua 600ml" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-sm focus:border-emerald-500 focus:bg-white focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500">Kategori</label>
                        <button type="button" onclick="openQuickCategoryModal()" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-plus text-[9px]"></i> Baru
                        </button>
                    </div>
                    <select id="prod_category_id" name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-xs focus:border-emerald-500 focus:bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Satuan</label>
                    <select name="unit_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-xs focus:border-emerald-500 focus:bg-white">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->nama_satuan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Harga Jual Kasir (Rp)</label>
                <input type="number" name="harga_jual" required placeholder="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-bold text-sm focus:border-emerald-500 focus:bg-white focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Stok Awal</label>
                    <input type="number" name="stok" required value="10" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-xs focus:border-emerald-500 focus:bg-white">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Stok Minimal (Alert)</label>
                    <input type="number" name="stok_minimal" required value="5" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-xs focus:border-emerald-500 focus:bg-white">
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/20">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Quick Tambah Kategori -->
<div id="quickCategoryModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center hidden">
    <div class="bg-white border border-slate-200 w-full max-w-sm rounded-2xl p-5 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-folder-plus text-emerald-600"></i> Kategori Baru
            </h3>
            <button onclick="closeQuickCategoryModal()" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form onsubmit="submitQuickCategory(event)" class="space-y-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Nama Kategori</label>
                <input type="text" id="quick_nama_kategori" required placeholder="Contoh: Snack & Permen" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 text-xs focus:border-emerald-500 focus:bg-white focus:outline-none">
            </div>

            <div class="flex justify-end gap-2 border-t border-slate-100 pt-3">
                <button type="button" onclick="closeQuickCategoryModal()" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                <button type="submit" id="submit_quick_cat_btn" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/20">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Form Edit Produk -->
<div id="editProductModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center hidden">
    <div class="bg-white border border-slate-200 w-full max-w-xl rounded-2xl p-6 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-emerald-600"></i> Edit Produk
            </h3>
            <button onclick="closeEditProductModal()" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form id="editProductForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Kode Barcode / SKU</label>
                <input type="text" id="edit_barcode" name="barcode" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-mono text-sm focus:border-emerald-500 focus:bg-white focus:outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Nama Produk</label>
                <input type="text" id="edit_nama_produk" name="nama_produk" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-sm focus:border-emerald-500 focus:bg-white focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Kategori</label>
                    <select id="edit_category_id" name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-xs focus:border-emerald-500 focus:bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Satuan</label>
                    <select id="edit_unit_id" name="unit_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-xs focus:border-emerald-500 focus:bg-white">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->nama_satuan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Harga Jual Kasir (Rp)</label>
                <input type="number" id="edit_harga_jual" name="harga_jual" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-bold text-sm focus:border-emerald-500 focus:bg-white focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Stok Unit</label>
                    <input type="number" id="edit_stok" name="stok" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-xs focus:border-emerald-500 focus:bg-white">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Stok Minimal (Alert)</label>
                    <input type="number" id="edit_stok_minimal" name="stok_minimal" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-xs focus:border-emerald-500 focus:bg-white">
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeEditProductModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/20">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal() {
        document.getElementById('productModal').classList.remove('hidden');
        setTimeout(() => document.getElementById('barcode_input').focus(), 100);
    }

    function closeModal() {
        document.getElementById('productModal').classList.add('hidden');
        if (html5QrCode) {
            html5QrCode.stop().catch(err => console.log(err));
        }
    }

    function openEditProductModal(product) {
        document.getElementById('editProductForm').action = '/products/' + product.id;
        document.getElementById('edit_barcode').value = product.barcode;
        document.getElementById('edit_nama_produk').value = product.nama_produk;
        document.getElementById('edit_category_id').value = product.category_id;
        document.getElementById('edit_unit_id').value = product.unit_id;
        document.getElementById('edit_harga_jual').value = product.harga_jual;
        document.getElementById('edit_stok').value = product.stok;
        document.getElementById('edit_stok_minimal').value = product.stok_minimal;

        document.getElementById('editProductModal').classList.remove('hidden');
    }

    function closeEditProductModal() {
        document.getElementById('editProductModal').classList.add('hidden');
    }

    function openQuickCategoryModal() {
        document.getElementById('quick_nama_kategori').value = '';
        document.getElementById('quickCategoryModal').classList.remove('hidden');
        setTimeout(() => document.getElementById('quick_nama_kategori').focus(), 100);
    }

    function closeQuickCategoryModal() {
        document.getElementById('quickCategoryModal').classList.add('hidden');
    }

    function submitQuickCategory(e) {
        e.preventDefault();
        const btn = document.getElementById('submit_quick_cat_btn');
        const nama = document.getElementById('quick_nama_kategori').value.trim();
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
                const catSelect = document.getElementById('prod_category_id');
                const option = document.createElement('option');
                option.value = data.data.id;
                option.text = data.data.nama_kategori;
                option.selected = true;
                catSelect.appendChild(option);

                const editCatSelect = document.getElementById('edit_category_id');
                const option2 = document.createElement('option');
                option2.value = data.data.id;
                option2.text = data.data.nama_kategori;
                editCatSelect.appendChild(option2);

                closeQuickCategoryModal();
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
</script>
@endpush

