<?php

include '../config.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Janji Temu</title>
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
            width: 100%;
            background-color: #fff;
        }

        .table-flat thead {
            background-color: #f0f0f0;
            color: #333;
        }

        .table-flat th, .table-flat td {
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

        .status-pending {
            color: #ffc107;
            font-weight: 500;
        }

        .status-confirmed {
            color: #28a745;
            font-weight: 500;
        }

        .status-cancelled {
            color: #dc3545;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4"> Janji Temu</h2>

    <div class="table-responsive">
        <table class="table-flat">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pasien</th>
                    <th>Dokter</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT a.dokter_id, a.appdate, a.apptime, a.userStatus,
                                 p.fname AS pasien_fname, p.lname AS pasien_lname,
                                 d.doctorname AS dokter_nama
                          FROM appointmenttb a
                          JOIN patreg p ON a.pid = p.pid
                          JOIN doctb d ON a.dokter_id = d.dokter_id
                          ORDER BY a.appdate DESC";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $statusClass = '';
                        if ($row['userStatus'] === 'Pending') {
                            $statusClass = 'status-pending';
                        } elseif ($row['userStatus'] === 'Confirmed') {
                            $statusClass = 'status-confirmed';
                        } elseif ($row['userStatus'] === 'Cancelled') {
                            $statusClass = 'status-cancelled';
                        }

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['dokter_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pasien_fname'] . ' ' . $row['pasien_lname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['dokter_nama']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['appdate']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['apptime']) . "</td>";
                        echo "<td class='{$statusClass}'>" . htmlspecialchars($row['userStatus']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Tidak ada janji temu.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <a href="../resepsionis_dashboard.php"> < Kembali </a>
</div>

</body>
</html>


