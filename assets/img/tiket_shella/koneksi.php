<?php
$servername = "localhost"; // Sesuaikan dengan server Anda
$username = "root"; // Jika menggunakan XAMPP, biasanya "root"
$password = ""; // Kosongkan jika tidak ada password
$dbname = "db_tiket_shella"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

