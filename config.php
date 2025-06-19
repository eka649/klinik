<?php
$host     = "localhost";       // atau 127.0.0.1
$username = "root";            // sesuaikan dengan username MySQL kamu
$password = "";                // sesuaikan dengan password MySQL kamu
$database = "klinik"; 
$port = '3309' ;

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database, $port);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Optional: set charset ke utf8
mysqli_set_charset($conn, "utf8");
?>
