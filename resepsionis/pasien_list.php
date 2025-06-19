<?php
include '../config.php'; // koneksi ke database
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            font-weight: 600;
            color: #333;
        }

        .table-flat {
            border-collapse: collapse;
            border-spacing: 0;
            background-color: #ffffff;
            width: 100%;
        }

        .table-flat thead {
            background-color: #f0f0f0;
            color: #333;
        }

        .table-flat th,
        .table-flat td {
            padding: 12px 16px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .table-flat tbody tr:hover {
            background-color: #f9f9f9;
        }

        .btn-flat {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            transition: background-color 0.2s ease;
            text-decoration: none;
        }

        .btn-flat:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4"> Daftar Pasien</h2>

    <div class="table-responsive">
        <table class="table-flat">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>No. Kontak</th>
                    <th>Jenis Kelamin</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Pastikan koneksi $conn sudah tersedia sebelum baris ini
                $query = "SELECT * FROM patreg";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($pasien = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $pasien['pid'] . "</td>";
                        echo "<td>" . htmlspecialchars($pasien['fname'] . ' ' . $pasien['lname']) . "</td>";
                        echo "<td>" . htmlspecialchars($pasien['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($pasien['contact']) . "</td>";
                        echo "<td>" . htmlspecialchars($pasien['gender']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada data pasien.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

     <p class="back-link"><a href="../resepsionis_dashboard.php">< Kembali </a></p>
</div>

</body>
</html>
