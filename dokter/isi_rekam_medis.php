<?php
session_start();
include '../config.php';

if (!isset($_SESSION['dokter_login'])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['dokter_login'];
$dokter_result = mysqli_query($conn, "SELECT dokter_id FROM doctb WHERE username='$username' LIMIT 1");
$dokter_data = mysqli_fetch_assoc($dokter_result);
$dokter_id = $dokter_data['dokter_id'];

$query = "
    SELECT a.*
    FROM appointmenttb a
    LEFT JOIN prestb p
        ON a.pid = p.pid AND a.appdate = p.appdate AND a.apptime = p.apptime
    WHERE a.dokter_id = '$dokter_id' AND p.pid IS NULL
    ORDER BY a.appdate DESC, a.apptime DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Isi Rekam Medis</title>
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

<h2>📝 Daftar Janji Temu - Isi Rekam Medis</h2>

<?php if (mysqli_num_rows($result) > 0): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Pasien</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['fname'] . ' ' . $row['lname']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['appdate']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['apptime']) ?></td>
                    <td class="text-center">
                        <form action="isi_rekam_medis_form.php" method="get" style="display:inline;">
                            <input type="hidden" name="pid" value="<?= $row['pid'] ?>">
                            <input type="hidden" name="appdate" value="<?= $row['appdate'] ?>">
                            <input type="hidden" name="apptime" value="<?= $row['apptime'] ?>">
                            <button type="submit">📝 Isi Rekam Medis</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Tidak ada janji temu yang perlu diisi.</p>
<?php endif; ?>

<a href="../dokter_dashboard.php" class="back-link"> < Kembali</a>

</body>
</html>
