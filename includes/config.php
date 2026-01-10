<?php
// Konfigurasi error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konfigurasi Database - SESUAI DENGAN XAMPP ANDA
define('DB_HOST', 'localhost');  // atau '127.0.0.1'
define('DB_PORT', '3307');       // PORT MYSQL DARI XAMPP: 3307
define('DB_USER', 'root');       // user default XAMPP
define('DB_PASS', '');           // password default XAMPP (kosong)
define('DB_NAME', 'restaurant_aurelian'); // pastikan database sudah dibuat

// Konfigurasi Situs - PERBAIKAN PATH
// Cek apakah sedang di localhost atau server
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    // Jika di localhost dengan folder restaurant-aurelian
    define('SITE_NAME', 'Aurelian Lounge');
    define('SITE_URL', 'http://localhost/restaurant-aurelian/');
    define('SITE_EMAIL', 'info@aurelianlounge.com');
    define('ADMIN_EMAIL', 'admin@aurelianlounge.com');
    
    // Debug: Tampilkan info path
    // echo "<!-- SITE_URL: " . SITE_URL . " -->\n";
    // echo "<!-- Document Root: " . $_SERVER['DOCUMENT_ROOT'] . " -->\n";
} else {
    // Untuk server production
    define('SITE_NAME', 'Aurelian Lounge');
    define('SITE_URL', 'https://yourdomain.com/');
    define('SITE_EMAIL', 'info@aurelianlounge.com');
    define('ADMIN_EMAIL', 'admin@aurelianlounge.com');
}
?>