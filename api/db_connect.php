<?php
// File: api/db_connect.php

// Konfigurasi Database
$db_host = 'localhost';
$db_user = 'root'; // User default XAMPP
$db_pass = ''; // Password default XAMPP kosong
$db_name = 'psas_db';

// Membuat Koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek Koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Mengatur karakter set
$conn->set_charset("utf8mb4");

?>