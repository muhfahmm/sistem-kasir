"use client"

import React from "react"
import Link from "next/link"
import { 
  LayoutGrid, 
  Search, 
  Plus, 
  Minus, 
  Trash2, 
  ShoppingBag, 
  Settings, 
  LogOut, 
  ShieldCheck, 
  Store,
  CreditCard,
  TrendingUp,
  Package,
  Users
} from "lucide-react"
import { useCartStore } from "@/store/useCartStore"
import { ThemeToggle } from "@/components/theme-toggle"
import clsx from "clsx"

const POS_DATA = [
  { id: 1, name: "Espresso", price: 25000, category: "Minuman" },
  { id: 2, name: "Latte", price: 30000, category: "Minuman" },
  { id: 3, name: "Croissant", price: 20000, category: "Makanan" },
  { id: 4, name: "Brownies", price: 15000, category: "Makanan" },
  { id: 5, name: "Cappuccino", price: 28000, category: "Minuman" },
  { id: 6, name: "Americano", price: 27000, category: "Minuman" },
  { id: 7, name: "Muffin", price: 18000, category: "Makanan" },
  { id: 8, name: "Orange Juice", price: 22000, category: "Minuman" },
]

export default function PosPage() {
  const { items, addItem, removeItem, clearCart, total } = useCartStore()
  const [products, setProducts] = React.useState<any[]>([])
  const [loading, setLoading] = React.useState(true)

  React.useEffect(() => {
    fetch('http://localhost:8000/api/products')
      .then(res => res.json())
      .then(data => {
        setProducts(data)
        setLoading(false)
      })
      .catch(err => {
        console.error("Failed to fetch products:", err)
        setLoading(false)
      })

    fetch('http://localhost:8000/api/categories')
      .then(res => res.json())
      .then(data => {
        const sortedCategories = [{ id: 0, name: 'Semua' }, ...data];
        setCategoriesLocal(sortedCategories)
      })
  }, [])

  const [categoriesLocal, setCategoriesLocal] = React.useState<any[]>([{ id: 0, name: 'Semua' }])
  const [selectedCategory, setSelectedCategory] = React.useState(0)

  const filteredProducts = products.filter(p => 
    selectedCategory === 0 || p.category_id === selectedCategory
  )

  return (
    <div className="flex h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-500 overflow-hidden font-sans">
      {/* Sidebar ... */}
      <div className="w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col p-6 gap-8 z-20">
        <div className="flex items-center gap-3 px-2">
          <div className="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
            <Store className="w-5 h-5" />
          </div>
          <span className="font-black text-slate-800 dark:text-white uppercase tracking-tighter text-lg">Harmoni POS</span>
        </div>
        
        <nav className="flex flex-col gap-2">
          <Link href="/" className="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 transition-all">
            <LayoutGrid className="w-5 h-5" />
            <span className="font-bold">Point of Sale</span>
          </Link>
          <Link href="/admin" className="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-300 transition-all">
            <TrendingUp className="w-5 h-5" />
            <span className="font-bold">Dashboard Overview</span>
          </Link>
          <Link href="/admin/products" className="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-300 transition-all">
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
        {/* ... */}
        <div className="mt-auto px-2 space-y-4">
          <div className="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800/50">
            <p className="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Shift Aktif</p>
            <div className="flex items-center gap-2">
              <div className="w-2 h-2 bg-emerald-500 rounded-full animate-pulse" />
              <span className="text-xs font-bold text-slate-600 dark:text-slate-300">Kasir: Fahim</span>
            </div>
          </div>
          <div className="flex items-center justify-between px-2">
            <ThemeToggle />
            <Link href="/login" className="p-2 text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all">
              <LogOut className="w-6 h-6" />
            </Link>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Header */}
        <header className="h-24 px-8 flex items-center justify-between border-b border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 backdrop-blur-md">
          <div>
            <h1 className="text-2xl font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight">Sistem Kasir Jajan</h1>
            <p className="text-slate-400 dark:text-slate-500 text-sm font-medium">Tuesday, 16 March 2026</p>
          </div>
          
          <div className="flex items-center gap-4">
            <div className="relative">
              <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
              <input 
                type="text" 
                placeholder="Cari menu favorit..." 
                className="pl-12 pr-6 py-3 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl w-80 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 transition-all outline-none text-slate-700 dark:text-slate-200"
              />
            </div>
          </div>
        </header>

        {/* Categories & Products */}
        <main className="flex-1 overflow-y-auto p-8 custom-scrollbar">
          <div className="flex gap-4 mb-8 overflow-x-auto pb-2 custom-scrollbar">
            {categoriesLocal.map((cat) => (
              <button 
                key={cat.id}
                onClick={() => setSelectedCategory(cat.id)}
                className={clsx(
                  "px-6 py-3 rounded-2xl font-bold transition-all active:scale-95 whitespace-nowrap",
                  selectedCategory === cat.id
                    ? "bg-blue-600 text-white shadow-lg shadow-blue-500/20" 
                    : "bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800"
                )}
              >
                {cat.name}
              </button>
            ))}
          </div>

          <div className="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 md:gap-5">
            {loading ? (
              <div className="col-span-full py-20 text-center text-slate-400 font-bold uppercase tracking-widest animate-pulse">
                Memuat menu...
              </div>
            ) : filteredProducts.length === 0 ? (
              <div className="col-span-full py-20 text-center text-slate-400 font-bold uppercase tracking-widest">
                Tidak ada produk ditemukan
              </div>
            ) : filteredProducts.map((product) => (
              <div 
                key={product.id}
                className="group bg-white dark:bg-slate-900 rounded-3xl p-4 md:p-5 border border-slate-100 dark:border-slate-800 hover:border-blue-500/30 dark:hover:border-blue-400/30 transition-all cursor-pointer hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none relative overflow-hidden flex flex-col"
              >
                <div className="mb-4 relative">
                  <div className="aspect-square bg-slate-50 dark:bg-slate-800/50 rounded-2xl flex items-center justify-center group-hover:scale-105 transition-transform duration-500 overflow-hidden">
                    <img 
                      src={`https://api.dicebear.com/7.x/shapes/svg?seed=${product.name}&backgroundColor=transparent`} 
                      alt={product.name}
                      className="w-20 h-20 md:w-24 md:h-24 opacity-80 transition-opacity group-hover:opacity-100"
                    />
                  </div>
                  <button 
                    onClick={(e) => {
                      e.stopPropagation();
                      addItem(product);
                    }}
                    className="absolute bottom-2 right-2 w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/40 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300 hover:bg-blue-700 active:scale-95"
                  >
                    <Plus className="w-5 h-5" />
                  </button>
                </div>
                
                <div className="flex-1 flex flex-col justify-end">
                  <h3 className="font-bold text-slate-800 dark:text-slate-100 text-base md:text-md mb-0.5 line-clamp-1">{product.name}</h3>
                  <p className="text-slate-400 dark:text-slate-500 text-xs font-medium mb-3">{product.category?.name || 'Item'}</p>
                  <div className="flex items-center justify-between mt-auto">
                    <span className="text-blue-600 dark:text-blue-400 font-black text-lg">Rp {Number(product.price).toLocaleString('id-ID')}</span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </main>
      </div>

      {/* Cart Sidebar */}
      <div className="w-[28rem] bg-white dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800 flex flex-col p-8 overflow-hidden">
        <div className="flex items-center justify-between mb-8">
          <h2 className="text-2xl font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight">Pesanan</h2>
          <button 
            onClick={clearCart}
            className="p-3 text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all"
          >
            <Trash2 className="w-6 h-6" />
          </button>
        </div>

        <div className="flex-1 overflow-y-auto custom-scrollbar pr-2 flex flex-col gap-4">
          {items.length === 0 ? (
            <div className="flex-1 flex flex-col items-center justify-center text-slate-300 dark:text-slate-700 gap-4 grayscale opacity-50">
              <ShoppingBag className="w-16 h-16" />
              <p className="font-bold uppercase tracking-widest text-sm">Keranjang Kosong</p>
            </div>
          ) : (
            Array.from(items.values()).map((item) => (
              <div key={item.id} className="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-3xl group">
                <div className="w-16 h-16 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center shadow-sm">
                   <img 
                      src={`https://api.dicebear.com/7.x/shapes/svg?seed=${item.name}&backgroundColor=transparent`} 
                      alt={item.name}
                      className="w-10 h-10 opacity-60"
                    />
                </div>
                <div className="flex-1">
                  <h4 className="font-bold text-slate-800 dark:text-slate-100 leading-tight">{item.name}</h4>
                  <p className="text-blue-600 dark:text-blue-400 font-bold text-sm">Rp {item.price.toLocaleString('id-ID')}</p>
                </div>
                <div className="flex items-center bg-white dark:bg-slate-800 rounded-xl p-1 shadow-sm border border-slate-100 dark:border-slate-700">
                  <button 
                    onClick={() => removeItem(item.id)}
                    className="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors"
                  >
                    <Minus className="w-4 h-4" />
                  </button>
                  <span className="w-8 text-center font-black text-slate-700 dark:text-slate-200 text-sm">{item.quantity}</span>
                  <button 
                    onClick={() => addItem(item)}
                    className="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-colors"
                  >
                    <Plus className="w-4 h-4" />
                  </button>
                </div>
              </div>
            ))
          )}
        </div>

        <div className="mt-8 pt-8 border-t-2 border-dashed border-slate-100 dark:border-slate-800">
          <div className="flex flex-col gap-3 mb-8">
            <div className="flex justify-between text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-xs">
              <span>Subtotal</span>
              <span>Rp {total.toLocaleString('id-ID')}</span>
            </div>
            <div className="flex justify-between text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-xs">
              <span>Pajak (11%)</span>
              <span>Rp {(total * 0.11).toLocaleString('id-ID')}</span>
            </div>
            <div className="flex justify-between items-center mt-2 group">
              <span className="text-xl font-black text-slate-800 dark:text-slate-100 uppercase tracking-tighter group-hover:text-blue-600 transition-colors duration-300">Total Harga</span>
              <span className="text-3xl font-black text-blue-600 dark:text-blue-400 underline decoration-blue-500/30 underline-offset-8">
                Rp {(total * 1.11).toLocaleString('id-ID')}
              </span>
            </div>
          </div>
          
          <button className="btn-primary w-full group">
            <CreditCard className="w-6 h-6 group-hover:scale-110 transition-transform" />
            <span>Bayar Sekarang</span>
          </button>
        </div>
      </div>
    </div>
  )
}
