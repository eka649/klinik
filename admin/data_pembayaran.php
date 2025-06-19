<?php
include '../config.php';

// Tambah data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $pid = $_POST['pid'];
    $dokter_id = $_POST['dokter_id'];
    $appdate = $_POST['appdate'];
    $apptime = $_POST['apptime'];
    $biaya = $_POST['biaya'];
    $status = $_POST['status'];
    $tanggal_bayar = $_POST['tanggal_bayar'];

    $insert = mysqli_query($conn, "INSERT INTO pembayaran 
        (pid, dokter_id, appdate, apptime, biaya, status, tanggal_bayar) 
        VALUES ('$pid', '$dokter_id', '$appdate', '$apptime', '$biaya', '$status', '$tanggal_bayar')");
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pembayaran WHERE pembayaran_id = $id");
}

// Ambil data
$pembayaran = mysqli_query($conn, "
    SELECT p.*, pa.fname AS nama_pasien, d.doctorname AS nama_dokter
    FROM pembayaran p
    JOIN patreg pa ON p.pid = pa.pid
    JOIN doctb d ON p.dokter_id = d.dokter_id
    ORDER BY p.tanggal_bayar DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pembayaran Konsultasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f9f9f9;
        }

        h2 {
            color: #2c3e50;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        form input, form select, form button {
            margin: 8px 4px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            background-color: #2ecc71;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #27ae60;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #3498db;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: #2980b9;
            margin-top: 20px;
            display: inline-block;
        }

        a:hover {
            text-decoration: underline;
        }

        .hapus-link {
            color: red;
        }
    </style>
</head>
<body>

<h2>Data Pembayaran Konsultasi</h2>

<!-- Form Tambah -->
<form method="post">
    <input type="text" name="pid" placeholder="ID Pasien" required>
    <input type="text" name="dokter_id" placeholder="ID Dokter" required>
    <input type="date" name="appdate" required>
    <input type="time" name="apptime" required>
    <input type="number" name="biaya" placeholder="Biaya" required>
    <select name="status" required>
        <option value="Lunas">Lunas</option>
        <option value="Belum Lunas">Belum Lunas</option>
    </select>
    <input type="date" name="tanggal_bayar" required>
    <button type="submit" name="tambah">Tambah</button>
</form>

<!-- Tabel Data -->
<table>
    <tr>
        <th>ID</th>
        <th>Pasien</th>
        <th>Dokter</th>
        <th>Tanggal</th>
        <th>Waktu</th>
        <th>Biaya</th>
        <th>Status</th>
        <th>Tanggal Bayar</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($pembayaran)): ?>
        <tr>
            <td><?= $row['pembayaran_id'] ?></td>
            <td><?= $row['nama_pasien'] ?></td>
            <td><?= $row['nama_dokter'] ?></td>
            <td><?= $row['appdate'] ?></td>
            <td><?= $row['apptime'] ?></td>
            <td>Rp <?= number_format($row['biaya'], 0, ',', '.') ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['tanggal_bayar'] ?></td>
            <td>
                <a class="hapus-link" href="?hapus=<?= $row['pembayaran_id'] ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<br>
<a href="../admin_dashboard.php"> < Kembali</a>

</body>
</html>
