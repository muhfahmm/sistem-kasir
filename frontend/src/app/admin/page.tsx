"use client"

import React from "react"
import Link from "next/link"
import { 
  LayoutDashboard, 
  Package, 
  Users, 
  TrendingUp, 
  ShoppingCart, 
  AlertCircle, 
  ArrowLeft,
  ShieldCheck,
  LayoutGrid
} from "lucide-react"
import { ThemeToggle } from "@/components/theme-toggle"
import clsx from "clsx"

export default function AdminDashboard() {
  const [products, setProducts] = React.useState<any[]>([])
  const [transactions, setTransactions] = React.useState<any[]>([])
  const [topProducts, setTopProducts] = React.useState<any[]>([])
  const [dashStats, setDashStats] = React.useState({ omzet_today: 0, transactions_count: 0 })
  const [loading, setLoading] = React.useState(true)

  React.useEffect(() => {
    // Fetch Products
    const fetchProducts = fetch('http://127.0.0.1:8000/api/products').then(res => res.json());
    // Fetch Transactions & Stats
    const fetchStats = fetch('http://127.0.0.1:8000/api/transactions').then(res => res.json());

    Promise.all([fetchProducts, fetchStats])
      .then(([productsData, statsData]) => {
        setProducts(productsData)
        setTransactions(statsData.recent_transactions || [])
        setDashStats(statsData.stats || { omzet_today: 0, transactions_count: 0 })
        setTopProducts(statsData.top_products || [])
        setLoading(false)
      })
      .catch(err => {
        console.error("Dashboard fetch error:", err)
        setLoading(false)
      })
  }, [])

  const lowStockCount = products.filter(p => p.stock_quantity < 10).length

  const stats = [
    { 
      label: "Omzet Hari Ini", 
      value: `Rp ${Number(dashStats.omzet_today).toLocaleString('id-ID')}`, 
      icon: <TrendingUp className="text-white" />, 
      trend: "+100%", 
      color: "from-emerald-500 to-teal-600",
      chart: "M5 15 L15 10 L25 12 L35 5 L45 8" 
    },
    { 
      label: "Pesanan Selesai", 
      value: dashStats.transactions_count.toString(), 
      icon: <ShoppingCart className="text-white" />, 
      trend: "Real-time", 
      color: "from-blue-500 to-indigo-600",
      chart: "M5 12 L15 15 L25 8 L35 10 L45 5"
    },
    { 
      label: "Katalog Produk", 
      value: products.length.toString(), 
      icon: <Package className="text-white" />, 
      trend: "Total", 
      color: "from-orange-500 to-amber-600",
      chart: "M5 10 L15 10 L25 10 L35 10 L45 10"
    },
    { 
      label: "Stok Perlu Re-fill", 
      value: lowStockCount.toString(), 
      icon: <AlertCircle className="text-white" />, 
      trend: "Kritis", 
      color: "from-rose-500 to-red-600",
      chart: "M5 5 L15 12 L25 5 L35 15 L45 8"
    },
  ]

  return (
    <div className="flex h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-500 font-sans overflow-hidden">
      {/* Sidebar */}
      <div className="w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 p-6 flex flex-col gap-8">
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
          <Link href="/admin" className="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 transition-all">
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
        
        <div className="mt-auto px-4">
           <ThemeToggle />
        </div>
      </div>

      {/* Main Content */}
      <main className="flex-1 p-12 overflow-y-auto">
        <div className="max-w-6xl mx-auto">
          <div className="flex items-center justify-between mb-12">
            <div>
              <h1 className="text-4xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Dashboard Overview</h1>
              <p className="text-slate-400 dark:text-slate-500 font-medium">Statistik penjualan real-time hari ini.</p>
            </div>
          </div>

          {/* Stats Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            {stats.map((stat, i) => (
              <div key={i} className="relative group overflow-hidden bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-500">
                <div className={clsx(
                  "absolute top-0 right-0 w-32 h-32 bg-gradient-to-br opacity-[0.03] group-hover:opacity-[0.08] transition-opacity duration-500 rounded-bl-full",
                  stat.color
                )} />
                
                <div className="flex items-start justify-between mb-8">
                  <div className={clsx(
                    "p-4 rounded-2xl shadow-lg transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3 bg-gradient-to-br shadow-blue-500/10 text-white",
                    stat.color
                  )}>
                    {stat.icon}
                  </div>
                  <div className="text-right">
                    <span className={clsx(
                      "text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest",
                      stat.trend.startsWith("+") ? "bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600" : "bg-blue-50 dark:bg-blue-500/10 text-blue-600"
                    )}>{stat.trend}</span>
                  </div>
                </div>

                <div className="relative z-10">
                  <p className="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-2">{stat.label}</p>
                  <h3 className="text-3xl font-black text-slate-800 dark:text-white tracking-tighter">
                    {loading ? "..." : stat.value}
                  </h3>
                </div>

                {/* Micro Chart */}
                <div className="mt-6 pt-6 border-t border-slate-50 dark:border-slate-800/50">
                  <svg className="w-full h-8 overflow-visible" preserveAspectRatio="none">
                    <path 
                      d={stat.chart} 
                      fill="none" 
                      stroke="currentColor" 
                      strokeWidth="3" 
                      strokeLinecap="round"
                      className={clsx(
                        "opacity-20 group-hover:opacity-100 transition-all duration-1000",
                        stat.trend.startsWith("+") ? "text-emerald-500" : "text-blue-500"
                      )}
                    />
                  </svg>
                </div>
              </div>
            ))}
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Recent Transactions */}
            <div className="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all">
              <div className="flex items-center justify-between mb-8">
                <h2 className="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Transaksi Terbaru</h2>
                <button className="text-blue-600 dark:text-blue-400 text-sm font-bold hover:underline transition-colors">Lihat Semua</button>
              </div>
              
              <div className="space-y-6">
                {transactions.length === 0 ? (
                  <div className="text-center py-10 text-slate-400 font-bold uppercase tracking-widest">Belum ada transaksi</div>
                ) : transactions.map((tx) => (
                  <div key={tx.id} className="flex items-center justify-between group p-3 rounded-3xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <div className="flex items-center gap-4">
                      <div className="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-xl flex items-center justify-center font-black text-slate-400 dark:text-slate-500">
                        #{tx.order_id.split('-')[1]?.substring(0, 4) || tx.id}
                      </div>
                      <div>
                        <h4 className="font-bold text-slate-800 dark:text-slate-100">{tx.customer_name}</h4>
                        <p className="text-slate-400 dark:text-slate-500 text-[10px] font-medium uppercase">
                          {new Date(tx.created_at).toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' })} • {tx.items_count || tx.items?.length || 0} Items
                        </p>
                      </div>
                    </div>
                    <div className="text-right">
                      <p className="font-black text-slate-800 dark:text-slate-100">Rp {Number(tx.total).toLocaleString('id-ID')}</p>
                      <span className="text-[10px] font-black uppercase text-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 rounded-lg border border-emerald-500/10 transition-all group-hover:bg-emerald-500 group-hover:text-white">PAID</span>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Top Products */}
            <div className="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all">
              <h2 className="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-8">Produk Terlaris 🔥</h2>
              <div className="space-y-8">
                {topProducts.length === 0 ? (
                  <div className="text-center py-10 text-slate-400 font-bold uppercase tracking-widest text-[10px]">Menunggu Penjualan</div>
                ) : topProducts.map((tp, i) => (
                  <div key={i} className="flex flex-col gap-2">
                    <div className="flex items-center justify-between">
                       <span className="font-bold text-slate-800 dark:text-slate-100 text-sm line-clamp-1">{tp.product?.name}</span>
                       <span className="text-xs font-black text-blue-600 dark:text-blue-400">{tp.total_sold} Unit</span>
                    </div>
                    <div className="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                      <div 
                        className="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-1000" 
                        style={{ width: `${Math.min(100, (tp.total_sold / topProducts[0].total_sold) * 100)}%` }} 
                      />
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  )
}
