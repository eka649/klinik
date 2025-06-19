<?php
session_start();
if (!isset($_SESSION['resepsionis_login'])) {

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard Resepsionis</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      background-color: #f1f3f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
    }

    .sidebar {
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      width: 230px;
      background: #ffffff;
      border-right: 1px solid #dee2e6;
      padding: 20px 10px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .sidebar h4 {
      font-weight: 600;
      margin-bottom: 25px;
      color: #333;
      text-align: center;
      font-size: 1.1rem;
    }

    .sidebar a {
      text-decoration: none;
      color: #333;
      font-weight: 500;
      padding: 10px 14px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background 0.2s ease-in-out;
    }

    .sidebar a:hover {
      background-color: #e9ecef;
    }

    .sidebar a.text-danger:hover {
      background-color: #ffe3e3;
    }

    main {
      margin-left: 230px;
      padding: 30px 20px;
    }

    .dashboard-header h2 {
      font-weight: 600;
      color: #212529;
      font-size: 1.5rem;
      margin-bottom: 4px;
    }

    .dashboard-header p {
      color: #6c757d;
      font-size: 1rem;
    }

    .card-stat {
      background: #ffffff;
      border: 1px solid #dee2e6;
      border-radius: 10px;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-weight: 600;
      font-size: 1.1rem;
      color: #495057;
    }

    .card-stat h4 {
      margin: 0;
      font-size: 1rem;
      font-weight: 500;
    }

    .card-stat span {
      font-size: 2rem;
      font-weight: bold;
      color: #0d6efd;
    }

    @media (max-width: 768px) {
      main {
        margin-left: 0;
        padding: 20px;
      }

      .sidebar {
        position: relative;
        width: 100%;
        height: auto;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        padding: 10px 5px;
      }

      .sidebar h4 {
        display: none;
      }

      .sidebar a {
        font-size: 0.9rem;
        padding: 8px 10px;
      }
    }
  </style>
</head>
<body>

<nav class="sidebar">
  <h4>📋 Menu Resepsionis</h4>
  <a href="resepsionis/pasien_list.php"><i class="fas fa-users"></i> Daftar Pasien</a>
  <a href="resepsionis/appointment_list.php"><i class="fas fa-calendar-check"></i> Janji Temu</a>
  <a href="resepsionis/dokter_list.php"><i class="fas fa-user-md"></i> Dokter</a>
  <a href="resepsionis/rekam_medis.php"><i class="fas fa-notes-medical"></i> Rekam Medis</a>
  <a href="resepsionis/pembayaran_konsultasi.php"><i class="fas fa-credit-card"></i> Pembayaran Konsultasi</a>
  <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav>

<main>
  <header class="dashboard-header mb-4">
    <h2>Dashboard Resepsionis klinik sanjaya</h2>
    <p>Aktivitas data : </p>
  </header>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card-stat">
        <h4>Total Pasien</h4>
        <span><?= getCount("patreg") ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-stat">
        <h4>Total Dokter</h4>
        <span><?= getCount("doctb") ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-stat">
        <h4>Janji Temu Hari Ini</h4>
        <span><?= getTodaysAppointments() ?></span>
      </div>
    </div>
  </div>
</main>

</body>
</html>

<?php
// Fungsi ringkasan
function getCount($table) {
    include 'config.php';
    $sql = "SELECT COUNT(*) AS total FROM $table";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

function getTodaysAppointments() {
    include 'config.php';
    $today = date("Y-m-d");
    $sql = "SELECT COUNT(*) AS total FROM appointmenttb WHERE appdate = '$today'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}
?>
