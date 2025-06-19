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

$pid = $_GET['pid'];
$appdate = $_GET['appdate'];
$apptime = $_GET['apptime'];

// Ambil info pasien
$pasien_query = mysqli_query($conn, "SELECT fname, lname FROM patreg WHERE pid='$pid' LIMIT 1");
$pasien = mysqli_fetch_assoc($pasien_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $disease = mysqli_real_escape_string($conn, $_POST['disease']);
    $allergy = mysqli_real_escape_string($conn, $_POST['allergy']);
    $prescription = mysqli_real_escape_string($conn, $_POST['prescription']);

    $insert = "INSERT INTO prestb (pid, dokter_id, fname, lname, appdate, apptime, disease, allergy, prescription)
               VALUES ('$pid', '$dokter_id', '{$pasien['fname']}', '{$pasien['lname']}', '$appdate', '$apptime', '$disease', '$allergy', '$prescription')";

    if (mysqli_query($conn, $insert)) {
        header("Location: isi_rekam_medis.php?success=1");
        exit();
    } else {
        $error = "Gagal menyimpan data!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Rekam Medis</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f8fa;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #34495e;
            margin-bottom: 20px;
            text-align: center;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        label {
            font-weight: 600;
            color: #2c3e50;
            display: block;
            margin-bottom: 8px;
            margin-top: 20px;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1.8px solid #bdc3c7;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
            resize: vertical;
        }
        input[type="text"]:focus,
        textarea:focus {
            border-color: #2980b9;
            outline: none;
        }
        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            font-size: 1.1rem;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #1c5980;
        }
        p.error-message {
            color: #e74c3c;
            font-weight: 600;
            text-align: center;
            margin-top: 20px;
        }
        p.back-link {
            text-align: center;
            margin-top: 30px;
        }
        p.back-link a {
            text-decoration: none;
            color: #2980b9;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        p.back-link a:hover {
            color: #1c5980;
        }
    </style>
</head>
<body>

    <h2>Isi Rekam Medis untuk <?= htmlspecialchars($pasien['fname'] . ' ' . $pasien['lname']) ?></h2>

    <form method="post">
        <label for="disease">Penyakit:</label>
        <input type="text" id="disease" name="disease" required>

        <label for="allergy">Alergi:</label>
        <input type="text" id="allergy" name="allergy" required>

        <label for="prescription">Resep:</label>
        <textarea id="prescription" name="prescription" rows="4" required></textarea>

        <button type="submit">Simpan</button>
    </form>

    <?php if (isset($error)): ?>
        <p class="error-message"><?= $error ?></p>
    <?php endif; ?>

    <p class="back-link"><a href="rekam_medis.php">< Kembali ke daftar</a></p>

</body>
</html>
