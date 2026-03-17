"use client"

import React from 'react'
import { X, Printer, CheckCircle2, MapPin, Phone, Printer as PrinterIcon } from 'lucide-react'

interface ReceiptModalProps {
  isOpen: boolean
  onClose: () => void
  items: any[]
  subtotal: number
  tax: number
  total: number
  orderId: string
}

export default function ReceiptModal({ isOpen, onClose, items, subtotal, tax, total, orderId }: ReceiptModalProps) {
  if (!isOpen) return null

  const handlePrint = () => {
    window.print()
  }

  const today = new Date().toLocaleString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })

  return (
    <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md animate-in fade-in duration-500">
      <div className="bg-white dark:bg-slate-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300 flex flex-col max-h-[95vh]">
        
        {/* Header - No Print */}
        <div className="p-6 flex items-center justify-between border-b border-slate-100 dark:border-slate-800 shrink-0 print:hidden">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
               <CheckCircle2 className="w-6 h-6" />
            </div>
            <div>
              <h2 className="text-lg font-black text-slate-800 dark:text-white tracking-tight leading-none uppercase">Cetak Struk</h2>
              <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Siap untuk dicetak</p>
            </div>
          </div>
          <button onClick={onClose} className="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
            <X className="w-5 h-5 text-slate-400" />
          </button>
        </div>

        {/* Receipt Workspace */}
        <div className="flex-1 overflow-y-auto p-10 bg-slate-100 dark:bg-slate-950/40 custom-scrollbar print:bg-white print:p-0">
          
          {/* THE THERMAL RECEIPT */}
          <div id="receipt-content" className="mx-auto w-[320px] bg-white dark:bg-white text-black p-8 shadow-xl relative print:shadow-none print:w-full">
            
            {/* Top Jagged Edge Effect */}
            <div className="absolute -top-2 left-0 w-full flex gap-1 px-1 print:hidden">
               {Array.from({length: 16}).map((_, i) => (
                 <div key={i} className="flex-1 h-4 bg-slate-100 dark:bg-slate-950/40 rounded-full" />
               ))}
            </div>

            {/* Store Logo & Info */}
            <div className="text-center mb-8 border-b-2 border-black pb-6">
              <div className="inline-flex w-16 h-16 bg-black rounded-2xl items-center justify-center text-white mb-4">
                 <PrinterIcon className="w-10 h-10" />
              </div>
              <h1 className="text-2xl font-black tracking-tighter uppercase leading-none mb-1">GRADASI</h1>
              <h2 className="text-lg font-bold tracking-[0.2em] uppercase leading-none mb-4">FOTOCOPY</h2>
              
              <div className="space-y-1 text-[10px] font-mono font-bold uppercase tracking-tight opacity-70">
                <div className="flex items-center justify-center gap-1">
                   <MapPin className="w-3 h-3" />
                   <span>Jl. Raya Pendidikan No. 45, Kota Anda</span>
                </div>
                <div className="flex items-center justify-center gap-1">
                   <Phone className="w-3 h-3" />
                   <span>+62 812 3456 7890</span>
                </div>
              </div>
            </div>

            {/* Meta Data */}
            <div className="font-mono text-[11px] font-bold space-y-1 mb-6 border-b border-black border-dashed pb-4 text-slate-600">
               <div className="flex justify-between">
                  <span>TANGGAL :</span>
                  <span>{today}</span>
               </div>
               <div className="flex justify-between">
                  <span>INVOICE :</span>
                  <span>#{orderId.split('-')[1]?.substring(0, 8)}</span>
               </div>
               <div className="flex justify-between">
                  <span>KASIR   :</span>
                  <span>ADMIN / FAHIM</span>
               </div>
            </div>

            {/* Items */}
            <div className="space-y-4 mb-8">
              {items.map((item, index) => (
                <div key={index} className="space-y-1">
                  <div className="text-xs font-black uppercase leading-tight">{item.name}</div>
                  <div className="flex justify-between items-end font-mono text-[11px] font-bold">
                    <span className="opacity-60">{item.quantity} x {item.price.toLocaleString('id-ID')}</span>
                    <span>{(item.price * item.quantity).toLocaleString('id-ID')}</span>
                  </div>
                </div>
              ))}
            </div>

            {/* Financials */}
            <div className="border-t-2 border-black pt-4 space-y-2 mb-8">
               <div className="flex justify-between items-center text-[11px] font-mono font-bold uppercase">
                  <span>SUBTOTAL</span>
                  <span>Rp {subtotal.toLocaleString('id-ID')}</span>
               </div>
               <div className="flex justify-between items-center text-[11px] font-mono font-bold uppercase">
                  <span>PAJAK (10%)</span>
                  <span>Rp {tax.toLocaleString('id-ID')}</span>
               </div>
               <div className="flex justify-between items-center pt-3 border-t border-black border-dashed">
                  <span className="text-sm font-black uppercase">TOTAL BERSIH</span>
                  <span className="text-xl font-black tracking-tighter">
                    Rp {total.toLocaleString('id-ID')}
                  </span>
               </div>
            </div>

            {/* Footer */}
            <div className="text-center pt-6 border-t border-black space-y-4">
              <div className="inline-block p-1 border-2 border-black">
                 <img 
                    src={`https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=${orderId}&margin=0`} 
                    alt="qr" 
                    className="w-16 h-16"
                  />
              </div>
              <div>
                <p className="text-[10px] font-black uppercase tracking-[0.2em] mb-1">TERIMA KASIH</p>
                <p className="text-[9px] font-mono font-bold opacity-60 italic">"Layanan kami, kepuasan Anda"</p>
              </div>
            </div>

            {/* Bottom Jagged Edge Effect */}
            <div className="absolute -bottom-2 left-0 w-full flex gap-1 px-1 print:hidden">
               {Array.from({length: 16}).map((_, i) => (
                 <div key={i} className="flex-1 h-4 bg-slate-100 dark:bg-slate-950/40 rounded-full" />
               ))}
            </div>
          </div>
        </div>

        {/* Actions - No Print */}
        <div className="p-8 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 flex gap-4 shrink-0 print:hidden">
           <button 
             onClick={handlePrint}
             className="flex-1 flex items-center justify-center gap-3 bg-slate-900 dark:bg-slate-800 text-white py-5 rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-slate-900/20"
           >
              <Printer className="w-6 h-6" />
              <span className="uppercase tracking-widest text-sm">CETAK SEKARANG</span>
           </button>
           <button 
             onClick={onClose}
             className="w-20 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 rounded-[2rem] flex items-center justify-center font-bold transition-all active:scale-95"
             title="Tutup"
           >
              <X className="w-6 h-6" />
           </button>
        </div>

      </div>

      <style jsx global>{`
        @media print {
          @page {
            margin: 0;
            size: 80mm 200mm; /* Typical thermal paper size */
          }
          body * {
            visibility: hidden;
            background: white !important;
          }
          #receipt-content, #receipt-content * {
            visibility: visible;
            color: black !important;
          }
          #receipt-content {
            position: fixed;
            left: 0;
            top: 0;
            width: 80mm; /* Standard receipt width */
            margin: 0;
            padding: 10mm;
            box-shadow: none !important;
            border: none !important;
          }
          .custom-scrollbar::-webkit-scrollbar {
            display: none;
          }
        }
      `}</style>
    </div>
  )
}
