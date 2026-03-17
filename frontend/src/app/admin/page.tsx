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
  const stats = [
    { label: "Penjualan Hari Ini", value: "Rp 4.250.000", icon: <TrendingUp className="text-emerald-500" />, trend: "+12.5%", color: "bg-emerald-500" },
    { label: "Total Transaksi", value: "142", icon: <ShoppingCart className="text-blue-500" />, trend: "+8.2%", color: "bg-blue-500" },
    { label: "Total Produk", value: "45", icon: <Package className="text-orange-500" />, trend: "0%", color: "bg-orange-500" },
    { label: "Stok Menipis", value: "3", icon: <AlertCircle className="text-red-500" />, trend: "-2", color: "bg-red-500" },
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
              <div key={i} className="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300">
                <div className="flex items-center justify-between mb-4">
                  <div className="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl">{stat.icon}</div>
                  <span className={clsx(
                    "text-xs font-black px-2 py-1 rounded-lg",
                    stat.trend.startsWith("+") ? "bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600" : "bg-red-50 dark:bg-red-900/20 text-red-600"
                  )}>{stat.trend}</span>
                </div>
                <p className="text-slate-400 dark:text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">{stat.label}</p>
                <h3 className="text-2xl font-black text-slate-800 dark:text-white">{stat.value}</h3>
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
                {[1, 2, 3, 4, 5].map((item) => (
                  <div key={item} className="flex items-center justify-between group p-3 rounded-3xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <div className="flex items-center gap-4">
                      <div className="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-xl flex items-center justify-center font-black text-slate-400 dark:text-slate-500">#{1000 + item}</div>
                      <div>
                        <h4 className="font-bold text-slate-800 dark:text-slate-100">Pelanggan Umum</h4>
                        <p className="text-slate-400 dark:text-slate-500 text-xs font-medium">10:4{item} AM • 3 Items</p>
                      </div>
                    </div>
                    <div className="text-right">
                      <p className="font-black text-slate-800 dark:text-slate-100">Rp 45.000</p>
                      <span className="text-[10px] font-black uppercase text-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 rounded-lg">Sukses</span>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Top Products */}
            <div className="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all">
              <h2 className="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-8">Produk Terlaris</h2>
              <div className="space-y-8">
                {["Espresso", "Latte", "Brownies"].map((name, i) => (
                  <div key={i} className="flex flex-col gap-2">
                    <div className="flex items-center justify-between">
                       <span className="font-bold text-slate-800 dark:text-slate-100">{name}</span>
                       <span className="text-xs font-black text-blue-600 dark:text-blue-400">{85 - (i * 10)}%</span>
                    </div>
                    <div className="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                      <div 
                        className="h-full bg-blue-600 rounded-full transition-all duration-1000" 
                        style={{ width: `${85 - (i * 10)}%` }} 
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
