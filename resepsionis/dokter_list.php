<?php

include '../config.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Daftar Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        h2 {
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .table-flat {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        .table-flat thead {
            background-color: #e9ecef;
            color: #495057;
        }
        .table-flat th, .table-flat td {
            padding: 12px 16px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }
        .table-flat tbody tr:hover {
            background-color: #f1f3f5;
        }
        .btn-flat {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s ease;
        }
        .btn-flat:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Daftar Dokter</h2>

    <div class="table-responsive">
        <table class="table-flat">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Spesialis</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM doctb ORDER BY doctorname ASC";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($dokter = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($dokter['dokter_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($dokter['doctorname']) . "</td>";
                        echo "<td>" . htmlspecialchars($dokter['spec']) . "</td>";
                        echo "<td>" . htmlspecialchars($dokter['email']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada data dokter.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <a href="../resepsionis_dashboard.php"> Kembali </a>
</div>

</body>
</html>
