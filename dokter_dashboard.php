<?php
session_start();
include 'config.php';

// Cek apakah sudah login sebagai dokter
if (!isset($_SESSION['dokter_login'])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['dokter_login'];

// Ambil data dokter dari database
$query = "SELECT * FROM doctb WHERE username='$username' LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) == 1) {
    $dokter = mysqli_fetch_assoc($result);
} else {
    echo "<p style='color:red;'>Data dokter tidak ditemukan.</p>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <style>
    body {
        background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        padding: 0;
    }
    .dashboard {
        max-width: 850px;
        margin: 60px auto;
        padding: 30px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.2);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
    }
    .dashboard h2 {
        font-weight: 600;
        margin-bottom: 30px;
        color: #ffffff;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
    }
    .text-muted {
        color:rgb(255, 0, 0) !important;
    }
    .menu-card {
        text-align: center;
        padding: 30px 20px;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transition: 0.3s ease-in-out;
        backdrop-filter: blur(10px);
    }
    .menu-card:hover {
        background: rgba(255, 255, 255, 0.35);
        transform: scale(1.05);
    }
    .menu-card i {
        font-size: 32px;
        color: #ffffff;
        margin-bottom: 12px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    .menu-card h5 {
        margin: 0;
        font-weight: 500;
        color:rgb(46, 41, 41);
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    .logout {
        background: rgba(255, 0, 0, 0.15);
        border: 1px solid rgba(255, 0, 0, 0.3);
        color: #ffffff;
    }
    .logout:hover {
        background: rgba(255, 0, 0, 0.25);
    }
    .logout i {
        color: #ffffff;
    }
</style>

</head>
<body>

<div class="dashboard text-center">
    <h2>🩺 Selamat Datang, Dokter <?= htmlspecialchars($dokter['doctorname']) ?></h2>
    <p class="text-muted mb-4">PANTAU SEMUA KEGIATAN ANDA SECARA BERKALA</p>

    <div class="row g-3">
        <div class="col-md-6">
            <a href="dokter/janji_temu.php" class="text-decoration-none">
                <div class="menu-card">
                    <i class="fas fa-calendar-alt"></i>
                    <h5>Jadwal Janji Temu</h5>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="dokter/isi_rekam_medis.php" class="text-decoration-none">
                <div class="menu-card">
                    <i class="fas fa-file-medical"></i>
                    <h5>Isi Rekam Medis</h5>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="dokter/rekam_medis.php" class="text-decoration-none">
                <div class="menu-card">
                    <i class="fas fa-folder-open"></i>
                    <h5>Riwayat Rekam Medis</h5>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="logout.php" class="text-decoration-none">
                <div class="menu-card logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <h5>Logout</h5>
                </div>
            </a>
        </div>
    </div>
</div>

</body>
</html>
