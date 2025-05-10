<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 - Page Not Found | HijauLoka</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style type="text/css">
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8faf5;
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }
    
    .container {
        max-width: 800px;
        text-align: center;
        padding: 40px 20px;
        background-color: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }
    
    .plant-decoration {
        position: absolute;
        opacity: 0.1;
        z-index: 0;
    }
    
    .plant-top-left {
        top: -50px;
        left: -50px;
        transform: rotate(45deg);
        font-size: 120px;
        color: #2e7d32;
    }
    
    .plant-bottom-right {
        bottom: -50px;
        right: -50px;
        transform: rotate(-135deg);
        font-size: 120px;
        color: #2e7d32;
    }
    
    .content {
        position: relative;
        z-index: 1;
    }
    
    .error-code {
        font-size: 120px;
        font-weight: 700;
        color: #2e7d32;
        line-height: 1;
        margin-bottom: 20px;
    }
    
    h1 {
        font-size: 28px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
    }
    
    p {
        font-size: 16px;
        color: #666;
        margin-bottom: 30px;
        line-height: 1.6;
    }
    
    .plant-icon {
        font-size: 80px;
        color: #4caf50;
        margin-bottom: 30px;
        animation: sway 3s ease-in-out infinite;
    }
    
    @keyframes sway {
        0%, 100% { transform: rotate(-5deg); }
        50% { transform: rotate(5deg); }
    }
    
    .btn {
        display: inline-block;
        background-color: #2e7d32;
        color: white;
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
    }
    
    .btn:hover {
        background-color: #1b5e20;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(46, 125, 50, 0.3);
    }
    
    .btn i {
        margin-right: 8px;
    }
    
    .footer-text {
        margin-top: 40px;
        font-size: 14px;
        color: #888;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="plant-decoration plant-top-left">
            <i class="fas fa-leaf"></i>
        </div>
        <div class="plant-decoration plant-bottom-right">
            <i class="fas fa-seedling"></i>
        </div>
        
        <div class="content">
            <div class="plant-icon">
                <i class="fas fa-seedling"></i>
            </div>
            
            <div class="error-code">404</div>
            
            <h1>Oops! Halaman Tidak Ditemukan</h1>
            
            <p>Sepertinya halaman yang Anda cari telah dipindahkan atau tidak ada. 
            Mungkin seperti tanaman yang belum ditanam di kebun kami.</p>
            
            <a href="/hijaulokauser/home" class="btn">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
            
            <p class="footer-text">
                &copy; <?= date('Y') ?> HijauLoka - Temukan Tanaman Hias Terbaik untuk Rumah Anda
            </p>
        </div>
    </div>
</body>
</html>