<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Premium</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <!-- Menggunakan path relatif atau absolute dari config -->
    <?php 
    // Pastikan $base_url tersedia. Jika file ini di-include dari modules, biasanya config sudah di-load.
    // Jika belum, defensi-nya:
    if(!isset($base_url)) {
        $base_url = "http://localhost/website%20sistem%20kasir/app";
    }
    ?>
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/style.css?v=<?= time() ?>">
    
    <!-- GLOBAL STYLE GUARD FOR LIGHT MODE -->
    <style>
        html[data-theme="light"] body,
        html[data-theme="light"] input, 
        html[data-theme="light"] select, 
        html[data-theme="light"] textarea,
        html[data-theme="light"] .form-control {
            color: #000000 !important; 
            caret-color: #000000 !important;
        }
        html[data-theme="light"] .form-control {
            background-color: #ffffff !important;
            color: #000000 !important;
            border-color: #ccc !important;
        }
        html[data-theme="light"] ::placeholder { color: #666 !important; opacity: 0.8; }
        html[data-theme="light"] .glass-panel {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 1px solid rgba(0,0,0,0.1) !important;
            color: #000 !important;
        }
        html[data-theme="light"] h1, html[data-theme="light"] h2, 
        html[data-theme="light"] h3, html[data-theme="light"] h4, 
        html[data-theme="light"] h5 { color: #000 !important; }
    </style>
    
    <!-- html5-qrcode Library untuk Barcode/QR Scanner -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <!-- Theme Init (Prevent Flash) -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>
    <div class="wrapper">
