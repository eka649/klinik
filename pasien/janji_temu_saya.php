<?php
session_start();
include '../config.php';

if (!isset($_SESSION['pasien_login'])) {
    header("Location: ../pasien_login.php");
    exit();
}

$email = $_SESSION['pasien_login'];
$error = '';
$success = '';

// Handle form submit
if (isset($_POST['buat_janji'])) {
    $doctor_id = $_POST['doctor_id'];
    $appdate = $_POST['appdate'];
    $apptime = $_POST['apptime'];

    // Ambil data pasien dari email
    $pat_query = mysqli_query($conn, "SELECT * FROM patreg WHERE email='$email'");
    $pat = mysqli_fetch_assoc($pat_query);

    if ($pat) {
        $fname = $pat['fname'];
        $lname = $pat['lname'];
        $gender = $pat['gender'];
        $contact = $pat['contact'];

        // Ambil biaya dokter
        $doc_query = mysqli_query($conn, "SELECT * FROM doctb WHERE dokter_id='$doctor_id'");
        $doc = mysqli_fetch_assoc($doc_query);
        $docFees = $doc['docFees'];

        // Simpan ke appointmenttb
        $pid = $pat['pid']; // Ambil pid pasien

        $insert = mysqli_query($conn, "INSERT INTO appointmenttb 
            (pid, dokter_id, fname, lname, gender, email, contact, appdate, apptime, userStatus, doctorStatus)
            VALUES ('$pid', '$doctor_id', '$fname', '$lname', '$gender', '$email', '$contact', '$appdate', '$apptime', 1, NULL)
        ");

       if ($insert) {
        $_SESSION['success_msg'] = "✅ Janji temu berhasil dibuat!";
         header("Location: janji_temu_saya.php");
        exit();
        } else {
        $_SESSION['error_msg'] = "❌ Gagal menyimpan janji temu.";
        header("Location: janji_temu_saya.php");
        exit();
        }
    }

}

// Ambil semua janji temu berdasarkan email
$query = "
    SELECT a.dokter_id, a.appdate, a.apptime, a.doctorStatus, d.doctorname, d.spec 
    FROM appointmenttb a
    JOIN doctb d ON a.dokter_id = d.dokter_id
    WHERE a.email = '$email'
    ORDER BY a.appdate DESC, a.apptime DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Janji Temu Saya</title>
  <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Janji Temu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
        }

        h2 {
            color: #212529;
            margin-bottom: 20px;
            font-weight: 600;
        }

        a {
            color: #0d6efd;
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-block;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn-flat {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-flat:hover {
            background-color: #0b5ed7;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal form {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            max-width: 400px;
        }

        label {
            font-weight: 500;
            margin-top: 10px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            border: 1px solid #ced4da;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-top: 30px;
            border: 1px solid #dee2e6;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f1f3f5;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        p {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h2> Daftar Janji Temu</h2>
   

    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php elseif ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Tombol Buat Janji -->
    <button class="btn-flat" onclick="document.getElementById('formModal').style.display='block'">
        ➕ Buat Janji Temu Baru
    </button>

    <!-- Modal Form -->
    <div id="formModal" class="modal">
        <form method="post" action="">
            <h4 class="mb-3">Buat Janji Temu Baru</h4>

            <label>Dokter:</label>
            <select name="doctor_id" required>
                <option value="">-- Pilih Dokter --</option>
                <?php
                $dokter = mysqli_query($conn, "SELECT dokter_id, doctorname, spec FROM doctb");
                while ($d = mysqli_fetch_assoc($dokter)) {
                    echo "<option value='{$d['dokter_id']}'>{$d['doctorname']} - {$d['spec']}</option>";
                }
                ?>
            </select>

            <label>Tanggal Janji:</label>
            <input type="date" name="appdate" required>

            <label>Waktu Janji:</label>
            <input type="time" name="apptime" required>

            <div class="mt-3">
                <button class="btn-flat" type="submit" name="buat_janji">Simpan</button>
                <button class="btn-flat btn-secondary" type="button" onclick="document.getElementById('formModal').style.display='none'">Tutup</button>
            </div>
        </form>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>Dokter</th>
                <th>Spesialis</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo ($row['doctorname']); ?></td>
                    <td><?php echo ($row['spec']); ?></td>
                    <td><?php echo ($row['appdate']); ?></td>
                    <td><?php echo ($row['apptime']); ?></td>
                    <td>
                        <?php
                        $status = $row['doctorStatus'];
                        if (!isset($status)) {
                            echo "⏳ Menunggu";
                        } elseif ($status == 1) {
                            echo "✅ Dikonfirmasi";
                        } elseif ($status == 0) {
                            echo "❌ Dibatalkan";
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Anda belum memiliki janji temu.</p>
    <?php endif; ?>
 <a href="../pasien_dashboard.php">⬅ Kembali ke Dashboard</a>
</body>
</html>

