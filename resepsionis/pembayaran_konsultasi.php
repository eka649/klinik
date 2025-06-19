<?php
include '../config.php';

// Proses simpan pembayaran
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan'])) {
    $appID = $_POST['appID'];
    $biaya = $_POST['biaya'];
    $status = $_POST['status'];
    $tanggal_bayar = $_POST['tanggal_bayar'];

    // Ambil data dari janji temu
    $get = mysqli_query($conn, "SELECT * FROM appointmenttb WHERE appID = $appID");
    $janji = mysqli_fetch_assoc($get);

    // Simpan ke tabel pembayaran
    $insert = mysqli_query($conn, "INSERT INTO pembayaran 
        (pid, dokter_id, appdate, apptime, biaya, status, tanggal_bayar)
        VALUES (
            '{$janji['pid']}',
            '{$janji['dokter_id']}',
            '{$janji['appdate']}',
            '{$janji['apptime']}',
            '$biaya',
            '$status',
            '$tanggal_bayar'
        )");
}

// Ambil janji temu yang belum dibayar
$janji_belum_dibayar = mysqli_query($conn, "
    SELECT a.*, p.fname AS nama_pasien, d.doctorname AS nama_dokter, d.spec AS spesialis
    FROM appointmenttb a
    JOIN patreg p ON a.pid = p.pid
    JOIN doctb d ON a.dokter_id = d.dokter_id
    WHERE NOT EXISTS (
        SELECT 1 FROM pembayaran pm 
        WHERE pm.pid = a.pid AND pm.appdate = a.appdate AND pm.apptime = a.apptime
    )
    ORDER BY a.appdate DESC
");


// Ambil semua pembayaran
$pembayaran = mysqli_query($conn, "
    SELECT pm.*, p.fname AS nama_pasien, d.doctorname AS nama_dokter, d.spec AS spesialis
    FROM pembayaran pm
    JOIN patreg p ON pm.pid = p.pid
    JOIN doctb d ON pm.dokter_id = d.dokter_id
    ORDER BY pm.tanggal_bayar DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Pembayaran Konsultasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
        }
        h2 {
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        form.payment-form {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            border: 1px solid #dee2e6;
            transition: box-shadow 0.3s ease;
        }
        form.payment-form:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        form.payment-form p {
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            color: #495057;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .btn-flat-primary {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.25s ease;
        }
        .btn-flat-primary:hover {
            background-color: #2563eb;
        }
        .table-flat {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 6px rgba(0,0,0,0.1);
        }
        .table-flat thead {
            background-color: #e9ecef;
            color: #495057;
            font-weight: 600;
        }
        .table-flat th, .table-flat td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            text-align: center;
            vertical-align: middle;
        }
        .table-flat tbody tr:hover {
            background-color: #f1f3f5;
        }
        .badge-lunas {
            background-color: #22c55e;
            color: white;
            font-weight: 600;
            padding: 0.35em 0.65em;
            border-radius: 12px;
        }
        .badge-belum-lunas {
            background-color: #facc15;
            color: #212529;
            font-weight: 600;
            padding: 0.35em 0.65em;
            border-radius: 12px;
        }
        a.btn-back {
            display: inline-block;
            margin-top: 2rem;
            background-color: #6c757d;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.25s ease;
        }
        a.btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Masukkan Pembayaran Konsultasi</h2>

    <?php while ($row = mysqli_fetch_assoc($janji_belum_dibayar)): ?>
        <form method="post" class="payment-form">
            <input type="hidden" name="appID" value="<?= htmlspecialchars($row['appID']) ?>">
            
            <p>
                <strong>Pasien:</strong> <?= htmlspecialchars($row['nama_pasien']) ?> | 
                <strong>Dokter:</strong> <?= htmlspecialchars($row['nama_dokter']) ?> (<?= htmlspecialchars($row['spesialis']) ?>)
            </p>
            <p>
                <strong>Tanggal:</strong> <?= htmlspecialchars($row['appdate']) ?> | 
                <strong>Jam:</strong> <?= htmlspecialchars($row['apptime']) ?>
            </p>

            <div class="mb-3">
                <label class="form-label">Biaya:</label>
                <input type="number" name="biaya" class="form-control" required />
            </div>

            <div class="mb-3">
                <label class="form-label">Status:</label>
                <select name="status" class="form-select" required>
                    <option value="Lunas">Lunas</option>
                    <option value="Belum Lunas">Belum Lunas</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Bayar:</label>
                <input type="date" name="tanggal_bayar" class="form-control" required />
            </div>

            <button type="submit" name="simpan" class="btn-flat-primary"> Simpan</button>
        </form>
    <?php endwhile; ?>

    <hr />

    <h2 class="mb-3"> Data Pembayaran Konsultasi</h2>

    <div class="table-responsive">
        <table class="table-flat">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Spesialis</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Biaya</th>
                    <th>Status</th>
                    <th>Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($pembayaran)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                        <td><?= htmlspecialchars($row['nama_dokter']) ?></td>
                        <td><?= htmlspecialchars($row['spesialis']) ?></td>
                        <td><?= htmlspecialchars($row['appdate']) ?></td>
                        <td><?= htmlspecialchars($row['apptime']) ?></td>
                        <td>Rp<?= number_format($row['biaya'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($row['status'] === 'Lunas'): ?>
                                <span class="badge-lunas">Lunas</span>
                            <?php else: ?>
                                <span class="badge-belum-lunas">Belum Lunas</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['tanggal_bayar']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a href="../resepsionis_dashboard.php">< Kembali </a>
</div>

</body>
</html>
