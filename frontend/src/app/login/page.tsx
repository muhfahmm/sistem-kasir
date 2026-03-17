"use client"

import React from "react"
import Link from "next/link"
import { useRouter } from "next/navigation"
import { ShieldCheck } from "lucide-react"
import { ThemeToggle } from "@/components/theme-toggle"

export default function LoginPage() {
  const router = useRouter()

  return (
    <div className="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 transition-colors duration-500 font-sans">
      <div className="absolute top-8 right-8">
        <ThemeToggle />
      </div>

      <div className="sm:mx-auto sm:w-full sm:max-w-md">
        <div className="flex justify-center">
          <div className="w-16 h-16 bg-blue-600 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-blue-500/20">
            <ShieldCheck className="w-8 h-8" />
          </div>
        </div>
        <h2 className="mt-6 text-center text-4xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">
          Admin Access
        </h2>
        <p className="mt-2 text-center text-sm text-slate-400 dark:text-slate-500 font-medium">
          Sistem Kasir Jajan v1.0
        </p>
      </div>

      <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div className="bg-white dark:bg-slate-900 py-10 px-8 shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 sm:rounded-[2.5rem] sm:px-12">
          <form className="space-y-6">
            <div>
              <label 
                htmlFor="email" 
                className="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2"
              >
                Email Address
              </label>
              <div className="mt-1">
                <input
                  id="email"
                  name="email"
                  type="email"
                  autoComplete="email"
                  required
                  className="appearance-none block w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 transition-all text-slate-700 dark:text-slate-200 font-bold outline-none"
                  placeholder="admin@kasir.com"
                />
              </div>
            </div>

            <div>
              <label 
                htmlFor="password" 
                className="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2"
              >
                Password
              </label>
              <div className="mt-1">
                <input
                  id="password"
                  name="password"
                  type="password"
                  autoComplete="current-password"
                  required
                  className="appearance-none block w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 transition-all text-slate-700 dark:text-slate-200 font-bold outline-none"
                  placeholder="••••••••"
                />
              </div>
            </div>

            <div className="pt-2">
              <button
                type="button"
                onClick={() => router.push("/admin")}
                className="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-2xl shadow-xl shadow-blue-500/30 text-lg font-black text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all active:scale-95 uppercase tracking-wider"
              >
                Sign In
              </button>
            </div>
          </form>
          
          <div className="mt-8 text-center">
            <Link href="/" className="text-sm font-bold text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
              ← Kembali ke POS
            </Link>
          </div>
        </div>
      </div>
    </div>
  )
}
