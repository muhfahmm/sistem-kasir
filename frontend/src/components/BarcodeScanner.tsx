"use client"

import React, { useEffect, useRef, useState } from 'react'
import { Html5Qrcode, Html5QrcodeSupportedFormats } from 'html5-qrcode'
import { X, Camera, RefreshCw, Sun, Zap } from 'lucide-react'

interface BarcodeScannerProps {
  onScan: (decodedText: string) => void
  onClose: () => void
}

export default function BarcodeScanner({ onScan, onClose }: BarcodeScannerProps) {
  const [isCameraStarted, setIsCameraStarted] = useState(false)
  const [torchEnabled, setTorchEnabled] = useState(false)
  const [hasTorch, setHasTorch] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const qrCodeRef = useRef<Html5Qrcode | null>(null)
  const containerId = "reader-container"

  useEffect(() => {
    // Initialize the lower-level Html5Qrcode class for custom UI
    qrCodeRef.current = new Html5Qrcode(containerId)

    const startCamera = async () => {
      try {
        const config = { 
          fps: 25, // Increased FPS for faster detection
          qrbox: { width: 280, height: 280 },
          aspectRatio: 1.0,
          videoConstraints: {
            facingMode: "environment",
          }
        }
        
        await qrCodeRef.current?.start(
          { facingMode: "environment" },
          config,
          (decodedText) => {
            playSuccessSound()
            onScan(decodedText)
            stopCamera().then(onClose)
          },
          (errorMessage) => {
            // Internal loop
          }
        )
        
        setIsCameraStarted(true)
        
        // Check if torch (flashlight) is supported
        // In Html5Qrcode, we can get the track from the video element or via getRunningTrack if available in newer versions
        // The most reliable way for Html5Qrcode is via the stream track
        try {
            const track = (qrCodeRef.current as any).getRunningTrack()
            if (track && 'getCapabilities' in track) {
                const capabilities = track.getCapabilities()
                if (capabilities.torch) {
                    setHasTorch(true)
                }
            }
        } catch (e) {
            console.warn("Torch check failed:", e)
        }

      } catch (err: any) {
        console.error("Camera start error:", err)
        setError("Gagal mengakses kamera. Standar privasi browser mungkin memblokir akses.")
      }
    }

    const playSuccessSound = () => {
        try {
            const audioCtx = new (window.AudioContext || (window as any).webkitAudioContext)()
            const oscillator = audioCtx.createOscillator()
            const gainNode = audioCtx.createGain()
            
            oscillator.type = 'sine'
            oscillator.frequency.setValueAtTime(880, audioCtx.currentTime) // A5 note
            oscillator.connect(gainNode)
            gainNode.connect(audioCtx.destination)
            
            gainNode.gain.setValueAtTime(0, audioCtx.currentTime)
            gainNode.gain.linearRampToValueAtTime(0.1, audioCtx.currentTime + 0.01)
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1)
            
            oscillator.start()
            oscillator.stop(audioCtx.currentTime + 0.1)
        } catch (e) { console.error(e) }
    }

    startCamera()

    return () => {
      stopCamera()
    }
  }, [onScan, onClose])

  const stopCamera = async () => {
    if (qrCodeRef.current && qrCodeRef.current.isScanning) {
      try {
        await qrCodeRef.current.stop()
      } catch (err) {
        console.error("Stop error:", err)
      }
    }
  }

  const toggleTorch = async () => {
    if (qrCodeRef.current && hasTorch) {
        try {
            const nextState = !torchEnabled
            const track = (qrCodeRef.current as any).getRunningTrack()
            if (track) {
                await track.applyConstraints({
                    advanced: [{ torch: nextState }]
                })
                setTorchEnabled(nextState)
            }
        } catch (err) {
            console.error("Torch error:", err)
        }
    }
  }

  return (
    <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-xl animate-in fade-in duration-300">
      <div className="relative bg-white dark:bg-slate-900 w-full max-w-lg rounded-[3rem] p-1 shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        {/* Decorative inner container */}
        <div className="bg-white dark:bg-slate-900 rounded-[2.8rem] p-8">
            <div className="flex items-center justify-between mb-8">
            <div>
                <h2 className="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Scanner Aktif</h2>
                <div className="flex items-center gap-2 mt-1">
                    <div className="w-2 h-2 bg-emerald-500 rounded-full animate-pulse" />
                    <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ready to Scan</span>
                </div>
            </div>
            <div className="flex items-center gap-3">
                {hasTorch && (
                    <button 
                        onClick={toggleTorch}
                        className={`p-3 rounded-2xl transition-all active:scale-95 ${torchEnabled ? 'bg-yellow-400 text-white shadow-lg shadow-yellow-400/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-400'}`}
                        title="Toggle Flashlight"
                    >
                        <Zap className={`w-6 h-6 ${torchEnabled ? 'fill-current' : ''}`} />
                    </button>
                )}
                <button 
                    onClick={onClose}
                    className="p-3 bg-slate-100 dark:bg-slate-800 hover:bg-red-50 dark:hover:bg-red-900/20 text-slate-400 hover:text-red-500 rounded-2xl transition-all active:scale-95"
                >
                    <X className="w-6 h-6" />
                </button>
            </div>
            </div>
            
            <div className="relative aspect-square overflow-hidden rounded-[2rem] bg-slate-950 border-4 border-slate-100 dark:border-slate-800 shadow-inner">
                <div id={containerId} className="w-full h-full" />
                
                {/* Custom Overlay UI */}
                {!isCameraStarted && !error && (
                    <div className="absolute inset-0 flex flex-col items-center justify-center text-slate-500 gap-4">
                        <RefreshCw className="w-10 h-10 animate-spin text-blue-500" />
                        <span className="text-sm font-bold uppercase tracking-widest">Memuat Kamera...</span>
                    </div>
                )}

                {error && (
                    <div className="absolute inset-0 flex flex-col items-center justify-center p-8 text-center gap-4 bg-slate-950/80 backdrop-blur-sm">
                        <div className="w-16 h-16 bg-red-500/10 rounded-2xl flex items-center justify-center text-red-500 mb-2">
                             <Camera className="w-8 h-8" />
                        </div>
                        <p className="text-sm font-bold text-white uppercase tracking-tight leading-relaxed">{error}</p>
                        <button 
                            onClick={() => window.location.reload()}
                            className="mt-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-blue-500/20 active:scale-95"
                        >
                            Klik untuk Reload
                        </button>
                    </div>
                )}

                {isCameraStarted && (
                    <div className="absolute inset-0 pointer-events-none">
                         {/* Low light enhancement indicator */}
                         <div className="absolute top-24 left-1/2 -translate-x-1/2 px-3 py-1 bg-slate-900/50 text-[8px] font-black text-blue-300 uppercase tracking-widest rounded-full flex items-center gap-2 backdrop-blur-sm">
                            <Sun className="w-3 h-3 text-yellow-400 animate-pulse" />
                            Smart Low-Light Filter Active
                         </div>

                         {/* Animated targeting box that feels like AI lock-on */}
                         <div className="absolute inset-0 border-[3rem] border-slate-950/40" />
                         
                         {/* Target Reticle */}
                         <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 border-2 border-dashed border-blue-500/20 rounded-3xl" />
                         
                         {/* Scanning frame corner decorations */}
                         <div className="absolute top-1/2 left-1/2 -translate-x-[140px] -translate-y-[140px] w-12 h-12 border-t-4 border-l-4 border-blue-500 rounded-tl-xl shadow-[0_0_10px_rgba(59,130,246,0.5)]" />
                         <div className="absolute top-1/2 left-1/2 translate-x-[92px] -translate-y-[140px] w-12 h-12 border-t-4 border-r-4 border-blue-500 rounded-tr-xl shadow-[0_0_10px_rgba(59,130,246,0.5)]" />
                         <div className="absolute top-1/2 left-1/2 -translate-x-[140px] translate-y-[92px] w-12 h-12 border-b-4 border-l-4 border-blue-500 rounded-bl-xl shadow-[0_0_10px_rgba(59,130,246,0.5)]" />
                         <div className="absolute top-1/2 left-1/2 translate-x-[92px] translate-y-[92px] w-12 h-12 border-b-4 border-r-4 border-blue-500 rounded-br-xl shadow-[0_0_10px_rgba(59,130,246,0.5)]" />
                         
                         {/* AI Scanning Status */}
                         <div className="absolute top-[4rem] left-1/2 -translate-x-1/2 px-4 py-1.5 bg-blue-600/90 text-[9px] font-black text-white uppercase tracking-[0.2em] rounded-full flex items-center gap-2 backdrop-blur-md">
                            <span className="w-1.5 h-1.5 bg-white rounded-full animate-ping" />
                            AI Target Locking Active
                         </div>

                         {/* Animated scanning line */}
                         <div className="absolute top-0 left-0 w-full h-1 bg-blue-500/50 shadow-[0_0_20px_rgba(59,130,246,1)] animate-scan-y" />
                         
                         {/* Decorative AI Data */}
                         <div className="absolute bottom-[4rem] left-8 text-[8px] font-mono text-blue-400 opacity-50 space-y-1">
                            <div>REC: 00:01:24</div>
                            <div>RESOLUTION: AUTO</div>
                            <div>DIST: 0.45m</div>
                         </div>
                    </div>
                )}
            </div>
            
            <div className="mt-8 flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800/50">
                <div className="w-10 h-10 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-600">
                    <Camera className="w-5 h-5" />
                </div>
                <p className="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest leading-relaxed">
                    Arahkan Barcode atau QR Code ke dalam bingkai tengah untuk pemindaian otomatis.
                </p>
            </div>
        </div>
      </div>

      <style jsx global>{`
        @keyframes scan-y {
            0%, 100% { top: 10% }
            50% { top: 90% }
        }
        .animate-scan-y {
            animation: scan-y 3s ease-in-out infinite;
        }
        #reader-container video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 1.5rem;
            filter: brightness(1.4) contrast(1.3) saturate(1.2); /* High Low-Light Enhancement */
            transition: filter 0.3s ease;
        }
      `}</style>
    </div>
  )
}
