<?php
session_start();
include '../config.php';

// Cek apakah pasien sudah login
if (!isset($_SESSION['pasien_login'])) {
    header("Location: ../login_pasien.php");
    exit();
}

$pid = $_SESSION['pid']; // Ambil ID pasien dari sesi

// Ambil data rekam medis milik pasien ini
$query = "
    SELECT 
        p.appdate, p.apptime, p.disease, p.allergy, p.prescription,
        d.doctorname
    FROM prestb p
    JOIN doctb d ON p.dokter_id = d.dokter_id
    WHERE p.pid = '$pid'
    ORDER BY p.appdate DESC, p.apptime DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rekam Medis Saya</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f2f4f6;
            margin: 0;
            padding: 30px;
            color: #333;
        }

        h2 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        a:hover {
            color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 16px;
            border-bottom: 1px solid #e6e6e6;
            text-align: left;
            font-size: 0.95rem;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: 600;
        }

        tr:last-child td {
            border-bottom: none;
        }

        p {
            margin-top: 20px;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
                width: 100%;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 10px;
                background: white;
            }

            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }

            th {
                display: none;
            }
        }
    </style>
</head>
<body>
    <h2> Rekam Medis Saya</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Dokter</th>
                    <th>Diagnosa</th>
                    <th>Alergi</th>
                    <th>Resep</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td data-label="Tanggal"><?php echo htmlspecialchars($row['appdate']); ?></td>
                        <td data-label="Waktu"><?php echo htmlspecialchars($row['apptime']); ?></td>
                        <td data-label="Dokter"><?php echo htmlspecialchars($row['doctorname']); ?></td>
                        <td data-label="Diagnosa"><?php echo htmlspecialchars($row['disease']); ?></td>
                        <td data-label="Alergi"><?php echo htmlspecialchars($row['allergy']); ?></td>
                        <td data-label="Resep"><?php echo nl2br(htmlspecialchars($row['prescription'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Belum ada rekam medis yang tersedia.</p>
    <?php endif; ?>

    <p><a href="../pasien_dashboard.php">⬅ Kembali</a></p>
</body>
</html>
