<?php
include 'config.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // aman!
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $role     = $_POST['role'];

    if (empty($username) || empty($_POST['password']) || empty($role) || empty($nama)) {
        $error = "Semua field wajib diisi.";
    } else {
        switch ($role) {
            case 'admin':
                $sql = "INSERT INTO admintb (username, password) VALUES ('$username', '$password')";
                break;
            case 'resepsionis':
                $sql = "INSERT INTO receptb (username, password) VALUES ('$username', '$password')";
                break;
            case 'dokter':
                $sql = "INSERT INTO doctb (username, password, doctorname) VALUES ('$username', '$password', '$nama')";
                break;
            default:
                $error = "Role tidak valid.";
                $sql = null;
        }

        if (isset($sql) && mysqli_query($conn, $sql)) {
            $success = "Registrasi berhasil!";
        } else {
            $error = "Registrasi gagal: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun - HospitalMS</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            height: 120vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            background: #fff;
            padding: 30px 20px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 500px;
        }

        .icon-header {
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

    <h2>Registrasi Pengguna Baru</h2>

    <form method="post" action="">
        <div class="mb-3">
            <label class="form-label">Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Lengkap:</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role:</label>
            <select name="role" class="form-select" required>
                <option value="">--Pilih Role--</option>
                <option value="admin">Admin</option>
                <option value="resepsionis">Resepsionis</option>
                <option value="dokter">Dokter</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Daftar</button>
    </form>

    <div class="link">
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>

    <?php if ($success): ?>
        <p class="text-success text-center mt-2"><?php echo $success; ?></p>
    <?php elseif ($error): ?>
        <p class="text-danger text-center mt-2"><?php echo $error; ?></p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

