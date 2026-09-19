<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran {{ $transaction->no_nota }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 58mm;
            margin: 0 auto;
            padding: 10px;
            font-size: 11px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 6px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        @media print {
            @page { margin: 0; }
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <div class="bold" style="font-size: 14px;">{{ $setting->nama_toko ?? 'POS Minimarket' }}</div>
        <div>{{ $setting->alamat ?? 'Jl. Merdeka No. 123' }}</div>
        <div>Telp: {{ $setting->telepon ?? '081234567890' }}</div>
    </div>

    <div class="line"></div>

    <div>
        <div>Nota  : {{ $transaction->no_nota }}</div>
        <div>Kasir : {{ $transaction->user->name ?? 'Kasir' }}</div>
        <div>Tgl   : {{ $transaction->created_at->format('d/m/Y H:i') }}</div>
    </div>

    <div class="line"></div>

    <table>
        @foreach($transaction->details as $item)
        <tr>
            <td colspan="3" class="bold">{{ $item->product->nama_produk }}</td>
        </tr>
        <tr>
            <td>{{ $item->jumlah }} x {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
            <td></td>
            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td>Total</td>
            <td class="text-right bold">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Bayar ({{ strtoupper($transaction->metode_pembayaran) }})</td>
            <td class="text-right">Rp {{ number_format($transaction->bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="text-right">Rp {{ number_format($transaction->kembali, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="text-center" style="margin-top: 10px;">
        {{ $setting->footer_nota ?? 'Terima Kasih Telah Berbelanja!' }}
    </div>
</body>
</html>
