<?php
include '../config.php';

// ==== PROSES HAPUS PASIEN ====
if (isset($_GET['hapus'])) {
    $pid = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM patreg WHERE pid = '$pid'");
    header("Location: data_pasien.php");
    exit();
}

// ==== PROSES EDIT PASIEN ====
if (isset($_POST['edit_submit'])) {
    $pid = $_POST['pid'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];

    mysqli_query($conn, "UPDATE patreg SET
        fname='$fname',
        lname='$lname',
        gender='$gender',
        email='$email',
        contact='$contact'
        WHERE pid='$pid'
    ");

    header("Location: data_pasien.php");
    exit();
}

// ==== AMBIL DATA PASIEN UNTUK TAMPIL ====
$query = "SELECT * FROM patreg ORDER BY fname ASC";
$result = mysqli_query($conn, $query);

// ==== AMBIL DATA PASIEN UNTUK EDIT (jika ada parameter edit) ====
$edit_pasien = null;
if (isset($_GET['edit'])) {
    $pid_edit = $_GET['edit'];
    $edit_query = mysqli_query($conn, "SELECT * FROM patreg WHERE pid = '$pid_edit'");
    $edit_pasien = mysqli_fetch_assoc($edit_query);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pasien</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            padding: 30px;
        }

        h2 {
            color: #2c3e50;
        }

        table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
            margin-top: 15px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn-kembali {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            background-color: #2ecc71;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-kembali:hover {
            background-color: #27ae60;
        }

        form {
            background-color: #fff;
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            max-width: 500px;
        }

        form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #2c3e50;
        }

        form input, form select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            padding: 10px 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        form button:hover {
            background-color: #2980b9;
        }

        form a {
            color: #e74c3c;
        }

        form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Data Pasien</h2>

<table>
    <tr>
        <th>No</th>
        <th>Nama Lengkap</th>
        <th>Jenis Kelamin</th>
        <th>Email</th>
        <th>Kontak</th>
        <th>Aksi</th>
    </tr>

    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['fname'] . ' ' . $row['lname']) ?></td>
        <td><?= htmlspecialchars($row['gender']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['contact']) ?></td>
        <td>
            <a href="data_pasien.php?edit=<?= $row['pid'] ?>">✏️ Edit</a> |
            <a href="data_pasien.php?hapus=<?= $row['pid'] ?>" onclick="return confirm('Yakin ingin menghapus?')">🗑️ Hapus</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a href="../admin_dashboard.php" class="btn-kembali">← Kembali ke Dashboard</a>

<?php if ($edit_pasien): ?>
    <hr>
    <h3>✏️ Edit Data Pasien: <?= htmlspecialchars($edit_pasien['fname'] . ' ' . $edit_pasien['lname']) ?></h3>

    <form method="POST" action="data_pasien.php">
        <input type="hidden" name="pid" value="<?= $edit_pasien['pid'] ?>">
        
        <label>Nama Depan:</label>
        <input type="text" name="fname" value="<?= htmlspecialchars($edit_pasien['fname']) ?>" required>

        <label>Nama Belakang:</label>
        <input type="text" name="lname" value="<?= htmlspecialchars($edit_pasien['lname']) ?>" required>

        <label>Jenis Kelamin:</label>
        <select name="gender" required>
            <option value="Laki-laki" <?= $edit_pasien['gender'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="Perempuan" <?= $edit_pasien['gender'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
        </select>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($edit_pasien['email']) ?>" required>

        <label>Kontak:</label>
        <input type="text" name="contact" value="<?= htmlspecialchars($edit_pasien['contact']) ?>" required>

        <button type="submit" name="edit_submit">Simpan Perubahan</button>
        <a href="data_pasien.php">Batal</a>
    </form>
<?php endif; ?>

</body>
</html>

