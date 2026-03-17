"use client"

import React from 'react'
import { 
  Plus, 
  Search, 
  Edit2, 
  Trash2, 
  ChevronLeft,
  LayoutGrid,
  ShieldCheck,
  Settings,
  MoreVertical,
  ArrowUpDown,
  TrendingUp,
  Package,
  Users
} from 'lucide-react'
import Link from 'next/link'
import { ThemeToggle } from "@/components/theme-toggle"

export default function AdminProductsPage() {
  const [products, setProducts] = React.useState<any[]>([])
  const [loading, setLoading] = React.useState(true)
  const [searchTerm, setSearchTerm] = React.useState('')

  React.useEffect(() => {
    fetchProducts()
  }, [])

  const fetchProducts = async () => {
    try {
      const res = await fetch('http://localhost:8000/api/products')
      const data = await res.json()
      setProducts(data)
      setLoading(false)
    } catch (err) {
      console.error("Failed to fetch products:", err)
      setLoading(false)
    }
  }

  const deleteProduct = async (id: number) => {
    if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) return
    
    try {
      const res = await fetch(`http://localhost:8000/api/products/${id}`, {
        method: 'DELETE',
      })
      if (res.ok) {
        fetchProducts()
      }
    } catch (err) {
      console.error("Failed to delete product:", err)
    }
  }

  const filteredProducts = products.filter(p => 
    p.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    p.sku.toLowerCase().includes(searchTerm.toLowerCase())
  )

  return (
    <div className="flex h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-500 overflow-hidden font-sans">
      {/* Sidebar */}
      <aside className="w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col p-6 gap-8 z-20">
        <div className="flex items-center gap-3 px-2">
          <div className="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
            <ShieldCheck className="w-5 h-5" />
          </div>
          <span className="font-black text-slate-800 dark:text-white uppercase tracking-tighter text-lg">Harmoni Admin</span>
        </div>
        
        <nav className="flex flex-col gap-2">
          <Link href="/" className="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-300 transition-all">
            <LayoutGrid className="w-5 h-5" />
            <span className="font-bold">Point of Sale</span>
          </Link>
          <Link href="/admin" className="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-300 transition-all">
            <TrendingUp className="w-5 h-5" />
            <span className="font-bold">Dashboard Overview</span>
          </Link>
          <Link href="/admin/products" className="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 transition-all">
            <Package className="w-5 h-5" />
            <span className="font-bold">Manajemen Produk</span>
          </Link>
          <Link href="/admin/categories" className="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-300 transition-all">
            <LayoutGrid className="w-5 h-5" />
            <span className="font-bold">Manajemen Kategori</span>
          </Link>
          <div className="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all opacity-50 cursor-not-allowed">
            <Users className="w-5 h-5" />
            <span className="font-bold">Data Pegawai</span>
          </div>
        </nav>

        <div className="mt-auto px-4">
           <ThemeToggle />
        </div>
      </aside>

      {/* Main Content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Header */}
        <header className="h-24 bg-white dark:bg-slate-900/50 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-10 z-10">
          <div className="flex items-center gap-4">
            <Link href="/admin" className="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
              <ChevronLeft className="w-6 h-6 text-slate-400" />
            </Link>
            <div>
              <h1 className="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">Manajemen Produk</h1>
              <p className="text-sm text-slate-400 font-medium">Kelola inventaris dan menu Anda</p>
            </div>
          </div>
          <button className="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-blue-500/20 active:scale-95">
            <Plus className="w-5 h-5" />
            <span>Tambah Produk</span>
          </button>
        </header>

        {/* Content */}
        <main className="flex-1 overflow-y-auto p-10">
          <div className="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            {/* Table Filters */}
            <div className="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
              <div className="relative w-96">
                <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                <input 
                  type="text" 
                  placeholder="Cari SKU atau nama produk..."
                  className="w-full bg-slate-50 dark:bg-slate-800/50 border-none rounded-2xl py-3 pl-12 pr-4 text-sm font-medium focus:ring-2 focus:ring-blue-500/20 transition-all"
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                />
              </div>
              <div className="flex items-center gap-3">
                <button className="p-3 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
                  <ArrowUpDown className="w-5 h-5 text-slate-400" />
                </button>
              </div>
            </div>

            {/* Table */}
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="bg-slate-50/50 dark:bg-slate-800/30">
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Info Produk</th>
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Kategori</th>
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Harga</th>
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Stok</th>
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Status</th>
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Aksi</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                  {loading ? (
                    <tr>
                      <td colSpan={6} className="px-8 py-20 text-center text-slate-400 font-bold uppercase tracking-widest animate-pulse">Memuat data...</td>
                    </tr>
                  ) : filteredProducts.length === 0 ? (
                    <tr>
                      <td colSpan={6} className="px-8 py-20 text-center text-slate-400 font-bold uppercase tracking-widest">Produk tidak ditemukan</td>
                    </tr>
                  ) : filteredProducts.map((product) => (
                    <tr key={product.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all group">
                      <td className="px-8 py-6">
                        <div className="flex items-center gap-4">
                          <div className="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center">
                            <img 
                              src={`https://api.dicebear.com/7.x/shapes/svg?seed=${product.name}&backgroundColor=transparent`} 
                              alt="" 
                              className="w-8 h-8 opacity-60"
                            />
                          </div>
                          <div>
                            <div className="font-bold text-slate-800 dark:text-slate-100">{product.name}</div>
                            <div className="text-xs font-medium text-slate-400 uppercase tracking-tight">{product.sku}</div>
                          </div>
                        </div>
                      </td>
                      <td className="px-8 py-6">
                        <span className="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-lg text-xs font-bold uppercase">
                          {product.category?.name || 'Item'}
                        </span>
                      </td>
                      <td className="px-8 py-6">
                        <div className="font-black text-slate-800 dark:text-slate-100">Rp {Number(product.price).toLocaleString('id-ID')}</div>
                      </td>
                      <td className="px-8 py-6">
                        <div className={`font-bold ${product.stock_quantity < 10 ? 'text-red-500' : 'text-slate-500'}`}>
                          {product.stock_quantity} unit
                        </div>
                      </td>
                      <td className="px-8 py-6">
                        <div className="flex items-center gap-2">
                          <div className={`w-2 h-2 rounded-full ${product.is_active ? 'bg-emerald-500' : 'bg-slate-300'}`} />
                          <span className="text-xs font-bold text-slate-400 uppercase">Aktif</span>
                        </div>
                      </td>
                      <td className="px-8 py-6 text-right">
                        <div className="flex items-center gap-2">
                          <button className="p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-blue-500 rounded-lg transition-all">
                            <Edit2 className="w-4 h-4" />
                          </button>
                          <button 
                            onClick={() => deleteProduct(product.id)}
                            className="p-2 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-400 rounded-lg transition-all"
                          >
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
            
            {/* Pagination Placeholder */}
            <div className="p-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-sm text-slate-400 font-medium font-sans">
              <div>Menampilkan {filteredProducts.length} produk</div>
              <div className="flex items-center gap-2">
                <button className="px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">Sebelumnya</button>
                <button className="px-4 py-2 bg-blue-600 text-white rounded-xl font-bold">1</button>
                <button className="px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">Selanjutnya</button>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  )
}
