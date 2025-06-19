<?php

include '../config.php';


// Inisialisasi variabel untuk form tambah/edit
$edit_mode = false;
$edit_id = 0;
$username = $doctorname = $email = $spec = "";
$error = "";
$success = "";

// Jika klik tombol hapus
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM doctb WHERE dokter_id = $delete_id");
    header("Location: data_dokter.php");
    exit;
}

// Jika ingin edit data, ambil data dokter yang diedit
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $edit_mode = true;
    $res = mysqli_query($conn, "SELECT * FROM doctb WHERE dokter_id = $edit_id LIMIT 1");
    if ($row = mysqli_fetch_assoc($res)) {
        $username = $row['username'];
        $doctorname = $row['doctorname'];
        $email = $row['email'];
        $spec = $row['spec'];
    } else {
        $error = "Data dokter tidak ditemukan.";
        $edit_mode = false;
    }
}

// Proses submit form tambah atau edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $doctorname = trim($_POST['doctorname']);
    $email = trim($_POST['email']);
    $spec = trim($_POST['spec']);
    $password = trim($_POST['password']); // password wajib diisi saat tambah, opsional saat edit

    if (empty($username) || empty($doctorname) || empty($email) || empty($spec)) {
        $error = "Semua field kecuali password harus diisi.";
    } else {
        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
            // Update data dokter
            $edit_id = intval($_POST['edit_id']);

            if (!empty($password)) {
                // update password baru (hash)
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE doctb SET username=?, doctorname=?, email=?, spec=?, password=? WHERE dokter_id=?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "sssssi", $username, $doctorname, $email, $spec, $hashed_password, $edit_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                // update tanpa password
                $query = "UPDATE doctb SET username=?, doctorname=?, email=?, spec=? WHERE dokter_id=?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ssssi", $username, $doctorname, $email, $spec, $edit_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            $success = "Data dokter berhasil diperbarui.";
            header("Location: data_dokter.php");
            exit;
        } else {
            // Tambah data dokter baru, password wajib
            if (empty($password)) {
                $error = "Password harus diisi untuk dokter baru.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO doctb (username, password, doctorname, email, spec) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "sssss", $username, $hashed_password, $doctorname, $email, $spec);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $success = "Dokter baru berhasil ditambahkan.";
                header("Location: data_dokter.php");
                exit;
            }
        }
    }
}

// Ambil semua data dokter untuk ditampilkan
$query = "SELECT dokter_id, username, doctorname, email, spec FROM doctb ORDER BY dokter_id ASC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Manajemen Dokter - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container my-4">
    <h2 class="mb-4"> Manajemen Dokter</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <?= $edit_mode ? "✏️ Edit Dokter (ID: $edit_id)" : "➕ Tambah Dokter Baru" ?>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <input type="hidden" name="edit_id" value="<?= $edit_mode ? $edit_id : '' ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input required type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>">
                </div>
                <div class="mb-3">
                    <label for="doctorname" class="form-label">Nama Dokter</label>
                    <input required type="text" id="doctorname" name="doctorname" class="form-control" value="<?= htmlspecialchars($doctorname) ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input required type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
                </div>
                <div class="mb-3">
                    <label for="spec" class="form-label">Spesialisasi</label>
                    <input required type="text" id="spec" name="spec" class="form-control" value="<?= htmlspecialchars($spec) ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <?= $edit_mode ? "Password (kosongkan jika tidak diubah)" : "Password" ?>
                    </label>
                    <input <?= $edit_mode ? '' : 'required' ?> type="password" id="password" name="password" class="form-control" autocomplete="new-password" >
                </div>
                <button type="submit" class="btn btn-success"><?= $edit_mode ? "Update" : "Tambah" ?></button>
                <?php if ($edit_mode): ?>
                    <a href="data_dokter.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <h4>Daftar Dokter</h4>

    <?php if (mysqli_num_rows($result) > 0): ?>
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-primary">
            <tr>
                <th>ID Dokter</th>
                <th>Username</th>
                <th>Nama Dokter</th>
                <th>Email</th>
                <th>Spesialisasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['dokter_id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['doctorname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['spec']) ?></td>
                <td>
                    <a href="data_dokter.php?edit_id=<?= $row['dokter_id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                    <a href="data_dokter.php?delete_id=<?= $row['dokter_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus dokter ini?');">🗑️ Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Tidak ada data dokter.</p>
    <?php endif; ?>

    <a href="../admin_dashboard.php" class="btn btn-secondary mt-3"> < Kembali </a>
</div>

</body>
</html>
