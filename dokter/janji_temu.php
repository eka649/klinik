<?php
session_start();
include '../config.php';

// Cek apakah sudah login
if (!isset($_SESSION['dokter_login'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil username dokter dari session
$username = $_SESSION['dokter_login'];

// Ambil ID dokter dari tabel doctb
$dokter_query = mysqli_query($conn, "SELECT dokter_id FROM doctb WHERE username='$username' LIMIT 1");
if ($dokter_query && mysqli_num_rows($dokter_query) == 1) {
    $dokter_data = mysqli_fetch_assoc($dokter_query);
    $dokter_id = $dokter_data['dokter_id'];
} else {
    echo "<p style='color:red;'>Data dokter tidak ditemukan.</p>";
    exit();
}


// Proses konfirmasi atau tolak janji temu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['appointment_id'])) {
        $appointment_id = $_POST['appointment_id'];

        if (isset($_POST['confirm'])) {
            mysqli_query($conn, "UPDATE appointmenttb SET doctorStatus = 1 WHERE appID = '$appointment_id'");
        } elseif (isset($_POST['reject'])) {
            mysqli_query($conn, "UPDATE appointmenttb SET doctorStatus = 0 WHERE appID = '$appointment_id'");
        }
    }
}


// Ambil janji temu berdasarkan ID dokter
$query = "
    SELECT * FROM appointmenttb 
    WHERE dokter_id = '$dokter_id' 
    ORDER BY appdate DESC, apptime DESC
";
$result = mysqli_query($conn, $query);
?>

<?php
// Pastikan Bootstrap CSS sudah dimuat
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Jadwal Janji Temu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
    body {
        padding: 30px;
        background-color: #f4f4f4;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
    }

    h2 {
        margin-bottom: 25px;
        color: #333;
        font-weight: 600;
        font-size: 1.7rem;
        border-bottom: 2px solid #ccc;
        padding-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    thead {
        background-color: #f7f7f7;
    }

    th {
        text-align: left;
        padding: 14px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #e0e0e0;
    }

    td {
        padding: 12px 16px;
        font-size: 0.9rem;
        color: #333;
        border-bottom: 1px solid #eee;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background-color: #f9f9f9;
    }

    form button {
        border: none;
        background: none;
        font-weight: bold;
        cursor: pointer;
        font-size: 1rem;
        padding: 4px 6px;
        border-radius: 4px;
        transition: background 0.2s ease;
    }

    form button[name="confirm"] {
        color: #198754;
    }

    form button[name="reject"] {
        color: #dc3545;
    }

    a {
        color: #333;
        text-decoration: none;
        font-weight: 500;
    }

    a:hover {
        text-decoration: underline;
    }

    p {
        margin-top: 30px;
        font-size: 1rem;
        color: #555;
    }
</style>


</head>
<body>

<h2>📅 Jadwal Janji Temu Saya</h2>

<?php if (mysqli_num_rows($result) > 0): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Pasien</th>
                <th>Jenis Kelamin</th>
                <th>Email</th>
                <th>Kontak</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status Pasien</th>
                <th>Status Dokter</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['fname'] . ' ' . $row['lname']) ?></td>
                    <td><?= htmlspecialchars($row['gender']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['contact']) ?></td>
                    <td><?= htmlspecialchars($row['appdate']) ?></td>
                    <td><?= htmlspecialchars($row['apptime']) ?></td>
                    <td><?= $row['userStatus'] == 1 ? 'Aktif' : 'Dibatalkan' ?></td>
                    <td>
                        <?php if (is_null($row['doctorStatus'])): ?>
                            <form method="post" action="">
                                <input type="hidden" name="appointment_id" value="<?= $row['appID'] ?>">
                                <button type="submit" name="confirm" title="Konfirmasi">✔️</button>
                                <button type="submit" name="reject" title="Tolak">❌</button>
                            </form>
                        <?php elseif ($row['doctorStatus'] == 1): ?>
                            ✅ Dikonfirmasi
                        <?php else: ?>
                            ❌ Dibatalkan
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Tidak ada janji temu.</p>
<?php endif; ?>

<p><a href="../dokter_dashboard.php"> < Kembali ke daftar</a></p>

</body>
</html>
