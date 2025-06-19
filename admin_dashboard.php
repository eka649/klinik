<?php
session_start();
include 'config.php';

// Cek login admin
if (!isset($_SESSION['admin_login'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
 <style>
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 40px;
        color: #2c3e50;
    }

    h2 {
        font-size: 1.8rem;
        margin-bottom: 30px;
        color: #34495e;
    }

    .menu-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-width: 400px;
    }

    .menu-list li a {
        display: block;
        padding: 14px 20px;
        background-color: #ffffff;
        color: #2c3e50;
        text-decoration: none;
        font-weight: 500;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: background-color 0.2s, border-color 0.2s;
    }

    .menu-list li a:hover {
        background-color: #e9f2ff;
        border-color: #b0d4ff;
    }

    .menu-list li a.logout {
        background-color: #ffe9e9;
        color:rgb(216, 34, 14);
        border-color:rgb(236, 163, 170);
    }

    .menu-list li a.logout:hover {
        background-color: #fddede;
        border-color:rgb(240, 79, 95);
    }

    .desc {
        margin-top: 30px;
        font-size: 1rem;
        color: #555;
    }
</style>

    </style>
</head>
<body>

<h2>Selamat Datang, Admin <?= htmlspecialchars($_SESSION['admin_login']) ?></h2>

<ul class="menu-list">
    <li><a href="admin/data_dokter.php"> Manajemen Dokter</a></li>
    <li><a href="admin/data_pasien.php"> Data Pasien</a></li>
    <li><a href="admin/data_janji_temu.php">Data Janji Temu</a></li>
    <li><a href="admin/rekam_medis.php"> Rekam Medis</a></li>
    <li><a href="admin/data_pembayaran.php"> Pembayaran Konsultasi</a></li>
    <li><a href="logout.php" class="logout"> Logout</a></li>
</ul>

<p class="desc">Silakan pilih menu untuk mengelola data sistem rumah sakit.</p>

</body>
</html>
