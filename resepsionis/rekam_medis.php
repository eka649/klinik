<?php

include '../config.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Rekam Medis Pasien</title>
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
            vertical-align: middle;
        }
        .table-flat td.text-start {
            text-align: left;
            white-space: pre-wrap;
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
    <h2>Rekam Medis Pasien</h2>

    <div class="table-responsive">
        <table class="table-flat">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pasien</th>
                    <th>Gender</th>
                    <th>Dokter</th>
                    <th>Spesialis</th>
                    <th>Penyakit</th>
                    <th>Alergi</th>
                    <th>Resep Obat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT pr.appdate, pr.fname AS pfname, pr.lname AS plname, pr.disease, pr.allergy, pr.prescription,
                                p.gender,
                                d.doctorname, d.spec
                         FROM prestb pr
                         LEFT JOIN patreg p ON pr.pid = p.pid
                         LEFT JOIN doctb d ON pr.dokter_id = d.dokter_id
                         ORDER BY pr.appdate DESC";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['appdate']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pfname'] . ' ' . $row['plname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['doctorname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['spec']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['disease']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['allergy']) . "</td>";
                        echo "<td class='text-start'>" . nl2br(htmlspecialchars($row['prescription'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Tidak ada data rekam medis.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <a href="../resepsionis_dashboard.php" >Kembali </a>
</div>

</body>
</html>
