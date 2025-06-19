<?php
session_start();
include 'config.php';

if (!isset($_SESSION['pasien_login'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['pasien_login'];

// Ambil data pasien
$query = mysqli_query($conn, "SELECT * FROM patreg WHERE email='$username' LIMIT 1");
$pasien = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f5;
            padding: 40px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-container {
            max-width: 900px;
            margin: auto;
            background-color: #f1f3f5;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        h2 {
            color: #212529;
            font-weight: 600;
            margin-bottom: 30px;
        }
        .menu-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        .menu-item {
            flex: 0 0 auto;
            min-width: 220px;
            padding: 18px;
            border-radius: 8px;
            background-color: #e9ecef;
            color:rgb(0, 0, 0);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            border: 1px solid transparent;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .menu-item:hover {
            background-color:rgb(21, 255, 9);
            color: white;
        }
        .menu-item.logout {
            background-color: #ffe2e2;
            color: #dc3545;
        }
        .menu-item.logout:hover {
            background-color: #dc3545;
            color: white;
        }
        .desc {
            margin-top: 40px;
            font-size: 1rem;
            color:rgb(86, 113, 136);
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>Selamat Datang, <?= htmlspecialchars($pasien['fname'] . ' ' . $pasien['lname']) ?></h2>

    <div class="menu-list">
        <a class="menu-item" href="pasien/profil.php">🤵 Profil saya</a>
        <a class="menu-item" href="pasien/janji_temu_saya.php">📅 Janji Temu Saya</a>
        <a class="menu-item" href="pasien/rekam_medis_saya.php">📋 Rekam Medis Saya</a>
        <a class="menu-item logout" href="logoutpasien.php">🚪 Logout</a>
    </div>

    <p class="desc">Silakan pilih menu untuk melihat atau mengatur data kesehatan Anda.</p>
</div>

</body>
</html>
