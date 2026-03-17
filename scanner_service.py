import cv2
from pyzbar import pyzbar
import requests
import json
import time

def start_scanner():
    """
    Demonstrasi pemindai barcode/QR menggunakan Python & OpenCV.
    Skrip ini mendeteksi kode lewat kamera dan mencetaknya ke konsol.
    """
    print("[INFO] Memulai pemindai kamera Python...")
    cap = cv2.VideoCapture(0) # Gunakan kamera default

    if not cap.isOpened():
        print("[ERROR] Tidak dapat mengakses kamera.")
        return

    print("[INFO] Menunggu kode dipindai... (Tekan 'q' untuk berhenti)")

    # Inisialisasi latar belakang untuk deteksi gerak
    avg_frame = None

    while True:
        ret, frame = cap.read()
        if not ret:
            break

        # Olah frame untuk deteksi gerak dan low-light
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        
        # Perkuat kontur untuk tempat remang-remang (CLAHE)
        clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8))
        gray_enhanced = clahe.apply(gray)
        
        # Gunakan frame yang diperjelas untuk pemindaian
        gray_blur = cv2.GaussianBlur(gray_enhanced, (21, 21), 0)

        if avg_frame is None:
            avg_frame = gray_blur.copy().astype("float")
            continue

        cv2.accumulateWeighted(gray_blur, avg_frame, 0.5)
        frame_delta = cv2.absdiff(gray, cv2.convertScaleAbs(avg_frame))
        thresh = cv2.threshold(frame_delta, 25, 255, cv2.THRESH_BINARY)[1]
        thresh = cv2.dilate(thresh, None, iterations=2)
        
        # Cari kontur (objek bergerak)
        cnts, _ = cv2.findContours(thresh.copy(), cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        
        locking_target = False
        for c in cnts:
            if cv2.contourArea(c) < 500: # Abaikan gerakan kecil (noise)
                continue
            
            # Gambar kotak "Locking" (Kuning) untuk objek yang bergerak/mendekat
            (x, y, w, h) = cv2.boundingRect(c)
            cv2.rectangle(frame, (x, y), (x + w, y + h), (0, 255, 255), 1)
            cv2.putText(frame, "AI ANALYZING...", (x, y - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.35, (0, 255, 255), 1)
            locking_target = True

        # Cari barcode dan QR Code menggunakan frame yang telah diperkuat (gray_enhanced)
        # Kita ganti frame input pyzbar dengan gray_enhanced untuk akurasi di tempat gelap
        barcodes = pyzbar.decode(gray_enhanced)

        for barcode in barcodes:
            # Ekstrak data
            barcode_data = barcode.data.decode("utf-8")
            barcode_type = barcode.type
            
            # Gambar kotak "LOCKED" (Hijau Tebal)
            (x, y, w, h) = barcode.rect
            cv2.rectangle(frame, (x, y), (x + w, y + h), (0, 255, 0), 3)
            
            text = f"LOCKED: {barcode_data} ({barcode_type})"
            cv2.putText(frame, text, (x, y - 15), cv2.FONT_HERSHEY_SIMPLEX, 0.6, (0, 255, 0), 2)

            print(f"[SCAN] SUCCESS: {barcode_type} -> {barcode_data}")

        # Tampilkan status AI dan Mode
        status_color = (0, 255, 0) if barcodes else (0, 255, 255) if locking_target else (100, 100, 100)
        status_text = "SCANNER: LOCKED" if barcodes else "SCANNER: TARGETING" if locking_target else "SCANNER: SEARCHING"
        cv2.putText(frame, status_text, (20, 40), cv2.FONT_HERSHEY_SIMPLEX, 0.7, status_color, 2)
        cv2.putText(frame, "LOW-LIGHT OPTIMIZATION: ON", (20, frame.shape[0] - 20), cv2.FONT_HERSHEY_SIMPLEX, 0.4, (200, 200, 200), 1)

        # Jika ingin melihat hasil enhancement (untuk debugging remang-remang)
        # res = np.hstack((gray, gray_enhanced))
        # cv2.imshow("Enhanced", res)

        # Tampilkan window
        cv2.imshow("Harmoni AI Smart Scanner", frame)

        # Berhenti jika tombol 'q' ditekan
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    print("[INFO] Menutup kamera...")
    cap.release()
    cv2.destroyAllWindows()

if __name__ == "__main__":
    try:
        start_scanner()
    except ImportError:
        print("\n[PERINGATAN] Library yang diperlukan belum terinstal.")
        print("Jalankan: pip install opencv-python pyzbar requests")
    except Exception as e:
        print(f"[ERROR] Terjadi kesalahan: {e}")
