import mysql.connector
import json
import sys
import os
from datetime import datetime

# Konfigurasi Database (Sesuai dengan project Anda)
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'db_kasir'
}

def analyze_sales():
    try:
        # 1. Connect to Database
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor(dictionary=True)

        print("Berhasil terhubung ke database...")

        # 2. Query Data Transaksi
        # Mengambil total penjualan per hari
        query = """
            SELECT 
                DATE(tanggal_transaksi) as tanggal, 
                COUNT(*) as total_transaksi, 
                SUM(total_harga) as total_pendapatan
            FROM transaksi
            GROUP BY DATE(tanggal_transaksi)
            ORDER BY tanggal DESC
            LIMIT 7
        """
        
        cursor.execute(query)
        results = cursor.fetchall()
        
        # 3. Process Data (Contoh sederhana: Hitung rata-rata)
        processed_data = []
        total_revenue = 0
        
        for row in results:
            # Convert date objects to string for JSON serialization
            row['tanggal'] = str(row['tanggal'])
            row['total_pendapatan'] = float(row['total_pendapatan'])
            total_revenue += row['total_pendapatan']
            processed_data.append(row)

        average_revenue = total_revenue / len(processed_data) if processed_data else 0

        # 4. Generate Insight
        insight = {
            "generated_at": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
            "data_period": "Last 7 Days",
            "average_daily_revenue": average_revenue,
            "total_revenue_period": total_revenue,
            "sales_trend": processed_data
        }

        # 5. Save to JSON (untuk dibaca oleh PHP)
        output_path = os.path.join(os.path.dirname(__file__), 'analytics_result.json')
        with open(output_path, 'w') as f:
            json.dump(insight, f, indent=4)
            
        print(f"Analisis selesai! Data disimpan ke {output_path}")

    except mysql.connector.Error as err:
        print(f"Error: {err}")
    finally:
        if 'conn' in locals() and conn.is_connected():
            cursor.close()
            conn.close()

if __name__ == "__main__":
    analyze_sales()
