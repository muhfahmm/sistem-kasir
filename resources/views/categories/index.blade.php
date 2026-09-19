@extends('layouts.app')

@section('title', 'Manajemen Kategori Produk')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Manajemen Kategori Produk</h2>
            <p class="text-xs text-slate-500">Kelola daftar kategori barang untuk mengelompokkan produk di toko.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openCategoryModal()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2 text-sm">
                <i class="fa-solid fa-plus"></i> Tambah Kategori Baru
            </button>
        </div>
    </div>

    <!-- Table Kategori -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Nama Kategori</th>
                        <th class="py-3 px-4">Slug URL</th>
                        <th class="py-3 px-4">Jumlah Produk</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $index => $cat)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3.5 px-4 font-mono text-slate-400">{{ $categories->firstItem() + $index }}</td>
                        <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">
                            <span class="inline-flex items-center gap-2">
                                <i class="fa-solid fa-folder text-emerald-600 text-sm"></i> {{ $cat->nama_kategori }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-500">{{ $cat->slug }}</td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-700 font-bold">
                                {{ $cat->products_count }} Produk
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button type="button" onclick='openEditCategoryModal(@json($cat))' class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Edit Kategori">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Seluruh produk yang terkait juga mungkin akan terpengaruh.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus Kategori">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">Belum ada kategori ditambahkan. Klik tombol di atas untuk membuat kategori!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>

<!-- Modal Form Tambah Kategori -->
<div id="categoryModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center hidden">
    <div class="bg-white border border-slate-200 w-full max-w-md rounded-2xl p-6 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-folder-plus text-emerald-600"></i> Tambah Kategori Baru
            </h3>
            <button onclick="closeCategoryModal()" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_kategori" required placeholder="Contoh: Perawatan Diri, Elektronik, Bumbu Dapur" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-sm focus:border-emerald-500 focus:bg-white focus:outline-none">
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeCategoryModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/20">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Form Edit Kategori -->
<div id="editCategoryModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center hidden">
    <div class="bg-white border border-slate-200 w-full max-w-md rounded-2xl p-6 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-emerald-600"></i> Edit Kategori
            </h3>
            <button onclick="closeEditCategoryModal()" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form id="editCategoryForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_nama_kategori" name="nama_kategori" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 text-sm focus:border-emerald-500 focus:bg-white focus:outline-none">
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeEditCategoryModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/20">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openCategoryModal() {
        document.getElementById('categoryModal').classList.remove('hidden');
    }

    function closeCategoryModal() {
        document.getElementById('categoryModal').classList.add('hidden');
    }

    function openEditCategoryModal(category) {
        document.getElementById('editCategoryForm').action = '/categories/' + category.id;
        document.getElementById('edit_nama_kategori').value = category.nama_kategori;
        document.getElementById('editCategoryModal').classList.remove('hidden');
    }

    function closeEditCategoryModal() {
        document.getElementById('editCategoryModal').classList.add('hidden');
    }
</script>
@endpush
