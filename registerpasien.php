<?php
include 'config.php';
$pesan = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    // Validasi
    if ($password != $cpassword) {
        $pesan = "❌ Password dan Konfirmasi Password tidak cocok.";
    } else {
        // Cek apakah email sudah terdaftar
        $cek = mysqli_query($conn, "SELECT * FROM patreg WHERE email='$email'");
        if (mysqli_num_rows($cek) > 0) {
            $pesan = "⚠️ Email sudah terdaftar.";
        } else {
            // Simpan ke database
            $sql = "INSERT INTO patreg (fname, lname, gender, email, contact, password, cpassword)
                    VALUES ('$fname', '$lname', '$gender', '$email', '$contact', '$password', '$cpassword')";

            if (mysqli_query($conn, $sql)) {
                $pesan = "✅ Registrasi berhasil. Silakan login.";
            } else {
                $pesan = "❌ Gagal menyimpan data: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pasien</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            height: 110vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            max-width: 850px;
            width: 90%;
            padding: 40px 30px;
            position: relative;
        }

        .register-card .icon-header {
            width: 60px;
            height: 60px;
            background-color: #0d6efd;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-weight: 600;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="icon-header">
        <i class="fas fa-user-plus"></i>
    </div>
    <h2>Registrasi Pasien</h2>

    <form method="post" action="">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Depan:</label>
                <input type="text" name="fname" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Belakang:</label>
                <input type="text" name="lname" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Jenis Kelamin:</label>
                <select name="gender" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">No. Kontak:</label>
                <input type="text" name="contact" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Konfirmasi Password:</label>
                <input type="password" name="cpassword" class="form-control" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Daftar</button>
    </form>

    <div class="link">
        <p>Sudah punya akun? <a href="loginpasien.php">Login di sini</a></p>
    </div>

    <?php if ($pesan != ''): ?>
        <p class="text-center mt-2" style="color: <?= strpos($pesan, '✅') !== false ? 'green' : 'red' ?>;"><?php echo $pesan; ?></p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
