<?php
session_start();
include '../config.php'; // Pastikan file koneksi sesuai path

// Cek apakah pasien sudah login
if (!isset($_SESSION['pid'])) {
    header("Location: ../loginpasien.php");
    exit();
}

$pid = $_SESSION['pid'];

// Ambil data pasien dari database

$query = "SELECT * FROM patreg WHERE pid = '$pid'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fdfbfb, #ebedee);
            padding: 40px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .profile-container {
            max-width: 700px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }
        h2 {
            text-align: center;
            color: #0d6efd;
            font-weight: 700;
            margin-bottom: 30px;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        .info-value {
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>Profil Saya</h2>

    <div class="mb-3">
        <span class="info-label">Nama Lengkap:</span><br>
        <span class="info-value"><?= htmlspecialchars($data['fname'] . ' ' . $data['lname']) ?></span>
    </div>

    <div class="mb-3">
        <span class="info-label">Jenis Kelamin:</span><br>
        <span class="info-value"><?= htmlspecialchars($data['gender']) ?></span>
    </div>

    <div class="mb-3">
        <span class="info-label">Email:</span><br>
        <span class="info-value"><?= htmlspecialchars($data['email']) ?></span>
    </div>

    <div class="mb-3">
        <span class="info-label">Nomor Kontak:</span><br>
        <span class="info-value"><?= htmlspecialchars($data['contact']) ?></span>
    </div>

    <a href="javascript:history.back()" class="btn btn-secondary mt-4">← Kembali</a>
</div>

</body>
</html>
