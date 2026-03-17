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
  Package,
  Users,
  TrendingUp,
  ArrowUpDown,
  Camera
} from 'lucide-react'
import Link from 'next/link'
import { ThemeToggle } from "@/components/theme-toggle"
import BarcodeScanner from "@/components/BarcodeScanner"

export default function AdminProductsPage() {
  const [products, setProducts] = React.useState<any[]>([])
  const [categories, setCategories] = React.useState<any[]>([])
  const [loading, setLoading] = React.useState(true)
  const [searchTerm, setSearchTerm] = React.useState('')
  const [isModalOpen, setIsModalOpen] = React.useState(false)
  const [editingProduct, setEditingProduct] = React.useState<any>(null)
  const [isScannerOpen, setIsScannerOpen] = React.useState(false)
  const [scannerTarget, setScannerTarget] = React.useState<'form' | 'dashboard'>('form')
  
  // Form State
  const [formData, setFormData] = React.useState({
    name: '',
    sku: '',
    category_id: '',
    price: '',
    cost_price: '',
    stock_quantity: '',
    description: '',
    is_active: true
  })

  React.useEffect(() => {
    fetchProducts()
    fetchCategories()
  }, [])

  const fetchProducts = async () => {
    try {
      const res = await fetch('http://127.0.0.1:8000/api/products')
      const data = await res.json()
      setProducts(data)
      setLoading(false)
    } catch (err) {
      console.error("Failed to fetch products:", err)
      setLoading(false)
    }
  }

  const fetchCategories = async () => {
    try {
      const res = await fetch('http://127.0.0.1:8000/api/categories')
      const data = await res.json()
      setCategories(data)
    } catch (err) {
      console.error("Failed to fetch categories:", err)
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    const url = editingProduct 
      ? `http://127.0.0.1:8000/api/products/${editingProduct.id}`
      : 'http://127.0.0.1:8000/api/products'
    const method = editingProduct ? 'PUT' : 'POST'

    try {
      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          ...formData,
          price: Number(formData.price),
          cost_price: Number(formData.cost_price) || 0,
          stock_quantity: Number(formData.stock_quantity) || 0,
          category_id: formData.category_id ? Number(formData.category_id) : null
        })
      })
      if (res.ok) {
        setIsModalOpen(false)
        setEditingProduct(null)
        resetForm()
        fetchProducts()
      } else {
        const errorData = await res.json()
        alert(JSON.stringify(errorData.errors || errorData.message))
      }
    } catch (err) {
      console.error("Failed to save product:", err)
    }
  }

  const resetForm = () => {
    setFormData({
      name: '',
      sku: '',
      category_id: '',
      price: '',
      cost_price: '',
      stock_quantity: '',
      description: '',
      is_active: true
    })
  }

  const openEditModal = (product: any) => {
    setEditingProduct(product)
    setFormData({
      name: product.name,
      sku: product.sku,
      category_id: product.category_id?.toString() || '',
      price: product.price.toString(),
      cost_price: product.cost_price?.toString() || '',
      stock_quantity: product.stock_quantity.toString(),
      description: product.description || '',
      is_active: !!product.is_active
    })
    setIsModalOpen(true)
  }

  const deleteProduct = async (id: number) => {
    if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) return
    
    try {
      const res = await fetch(`http://127.0.0.1:8000/api/products/${id}`, {
        method: 'DELETE',
      })
      if (res.ok) {
        fetchProducts()
      }
    } catch (err) {
      console.error("Failed to delete product:", err)
    }
  }

  const handleAdminScan = (code: string) => {
    const existingProduct = products.find(p => p.sku === code)
    if (existingProduct) {
      openEditModal(existingProduct)
    } else {
      setEditingProduct(null)
      resetForm()
      setFormData(prev => ({ ...prev, sku: code }))
      setIsModalOpen(true)
    }
    setIsScannerOpen(false)
  }

  const filteredProducts = products.filter(p => 
    p.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    p.sku.toLowerCase().includes(searchTerm.toLowerCase())
  )

  return (
    <div className="flex h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-500 overflow-hidden font-sans">
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

      <div className="flex-1 flex flex-col overflow-hidden">
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
          <div className="flex items-center gap-3">
            <button 
              onClick={() => {
                setScannerTarget('dashboard')
                setIsScannerOpen(true)
              }}
              className="flex items-center gap-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-2xl font-bold transition-all border border-slate-200 dark:border-slate-700 shadow-sm active:scale-95"
            >
              <Camera className="w-5 h-5 text-blue-500" />
              <span>Scan SKU</span>
            </button>
            <button 
              onClick={() => {
                setScannerTarget('form')
                setEditingProduct(null);
                resetForm();
                setIsModalOpen(true);
              }}
              className="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-blue-500/20 active:scale-95"
            >
              <Plus className="w-5 h-5" />
              <span>Tambah Produk</span>
            </button>
          </div>
        </header>

        <main className="flex-1 overflow-y-auto p-10">
          <div className="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
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

            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="bg-slate-50/50 dark:bg-slate-800/30">
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Info Produk</th>
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Kategori</th>
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Harga</th>
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Stok</th>
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Status</th>
                    <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 text-right">Aksi</th>
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
                        <div className="flex items-center justify-end gap-2">
                          <button 
                            onClick={() => openEditModal(product)}
                            className="p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-blue-500 rounded-lg transition-all"
                          >
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

      {/* Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onClick={() => setIsModalOpen(false)} />
          <div className="relative bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[2.5rem] p-10 shadow-2xl border border-slate-200 dark:border-slate-800 animate-in fade-in zoom-in duration-300 max-h-[90vh] overflow-y-auto custom-scrollbar">
            <h2 className="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-8">
              {editingProduct ? 'Edit Produk' : 'Tambah Produk Baru'}
            </h2>
            <form onSubmit={handleSubmit} className="grid grid-cols-2 gap-8">
              <div className="col-span-2">
                <label className="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Produk</label>
                <input 
                  type="text" 
                  className="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500/20 outline-none"
                  value={formData.name}
                  onChange={(e) => setFormData({...formData, name: e.target.value})}
                  required
                />
              </div>

              <div>
                <label className="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">SKU (Kode Produk)</label>
                <div className="flex gap-2">
                  <input 
                    type="text" 
                    className="flex-1 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500/20 outline-none"
                    value={formData.sku}
                    onChange={(e) => setFormData({...formData, sku: e.target.value})}
                    required
                  />
                  <button 
                    type="button"
                    onClick={() => setIsScannerOpen(true)}
                    className="p-4 bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-blue-500 rounded-2xl transition-all shadow-sm"
                    title="Scan Barcode/QR"
                  >
                    <Camera className="w-6 h-6" />
                  </button>
                </div>
              </div>

              <div>
                <label className="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
                <select 
                  className="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500/20 outline-none appearance-none"
                  value={formData.category_id}
                  onChange={(e) => setFormData({...formData, category_id: e.target.value})}
                >
                  <option value="">Pilih Kategori</option>
                  {categories.map(cat => (
                    <option key={cat.id} value={cat.id}>{cat.name}</option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Harga Jual (Rp)</label>
                <input 
                  type="number" 
                  className="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500/20 outline-none"
                  value={formData.price}
                  onChange={(e) => setFormData({...formData, price: e.target.value})}
                  required
                />
              </div>

              <div>
                <label className="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Harga Modal (Rp)</label>
                <input 
                  type="number" 
                  className="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500/20 outline-none"
                  value={formData.cost_price}
                  onChange={(e) => setFormData({...formData, cost_price: e.target.value})}
                />
              </div>

              <div>
                <label className="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Stok Awal</label>
                <input 
                  type="number" 
                  className="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500/20 outline-none"
                  value={formData.stock_quantity}
                  onChange={(e) => setFormData({...formData, stock_quantity: e.target.value})}
                  required
                />
              </div>

              <div className="flex items-end pb-4">
                <label className="flex items-center gap-3 cursor-pointer group">
                  <input 
                    type="checkbox" 
                    className="w-6 h-6 rounded-lg border-none bg-slate-100 dark:bg-slate-800 text-blue-600 focus:ring-blue-500/20"
                    checked={formData.is_active}
                    onChange={(e) => setFormData({...formData, is_active: e.target.checked})}
                  />
                  <span className="text-sm font-bold text-slate-600 dark:text-slate-300 group-hover:text-blue-500 transition-colors uppercase tracking-tight">Produk Aktif</span>
                </label>
              </div>

              <div className="col-span-2">
                <label className="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi (Opsional)</label>
                <textarea 
                  className="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500/20 outline-none h-24"
                  value={formData.description}
                  onChange={(e) => setFormData({...formData, description: e.target.value})}
                />
              </div>

              <div className="col-span-2 flex gap-4 pt-4">
                <button 
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="flex-1 py-4 px-6 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                >
                  Batal
                </button>
                <button 
                  type="submit"
                  className="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-2xl font-bold transition-all shadow-lg shadow-blue-500/20"
                >
                  Simpan Produk
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Barcode Scanner Overlay */}
      {isScannerOpen && (
        <BarcodeScanner 
          onScan={(code) => {
            if (scannerTarget === 'dashboard') {
              handleAdminScan(code)
            } else {
              setFormData({ ...formData, sku: code })
              setIsScannerOpen(false)
            }
          }}
          onClose={() => setIsScannerOpen(false)}
        />
      )}
    </div>
  )
}
