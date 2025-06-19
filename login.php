<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // JANGAN escape password kalau mau diverifikasi hash

    // === CEK ADMIN ===
    $sql_admin = "SELECT * FROM admintb WHERE username='$username'";
    $result_admin = mysqli_query($conn, $sql_admin);
    if ($result_admin && mysqli_num_rows($result_admin) == 1) {
        $admin = mysqli_fetch_assoc($result_admin);
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_login'] = $username;
            header("Location: admin_dashboard.php");
            exit();
        }
    }

    // === CEK RESEPSIONIS ===
    $sql_recept = "SELECT * FROM receptb WHERE username='$username'";
    $result_recept = mysqli_query($conn, $sql_recept);
    if ($result_recept && mysqli_num_rows($result_recept) == 1) {
        $recept = mysqli_fetch_assoc($result_recept);
        if (password_verify($password, $recept['password'])) {
            $_SESSION['resepsionis_login'] = $username;
            header("Location: resepsionis_dashboard.php");
            exit();
        }
    }

    // === CEK DOKTER ===
    $sql_doctor = "SELECT * FROM doctb WHERE username='$username'";
    $result_doctor = mysqli_query($conn, $sql_doctor);
    if ($result_doctor && mysqli_num_rows($result_doctor) == 1) {
        $doctor = mysqli_fetch_assoc($result_doctor);
        if (password_verify($password, $doctor['password'])) {
            $_SESSION['dokter_login'] = $username;
            header("Location: dokter_dashboard.php");
            exit();
        }
    }

    // Jika semua gagal
    $error = "Username atau Password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login HospitalMS</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icon (Font Awesome) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            padding: 40px 30px;
            position: relative;
        }

        .login-card .icon-header {
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

        .login-card h2 {
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
            color: #333;
        }

        .form-control:focus {
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
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="icon-header">
        <i class="fas fa-user-lock"></i>
    </div>
    <h2>Login Pengguna</h2>

    <form method="post" action="">
        <div class="mb-3">
            <label class="form-label">Username:</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password:</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <div class="link">
        <p>Belum punya akun? <a href="register.php">Registrasi</a></p>
    </div>

    <?php if ($error != ''): ?>
        <p class="text-danger text-center mt-2"><?php echo $error; ?></p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
