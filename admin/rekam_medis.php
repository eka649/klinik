<?php

include '../config.php';

// Cek login admin

$error = "";
$edit_mode = false;
$presID = null;

// Variabel default untuk form
$pid = $dokter_id = $fname = $lname = $appdate = $apptime = $disease = $allergy = $prescription = "";

// Proses DELETE
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM prestb WHERE presID = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        header("Location: rekam_medis.php");
        exit();
    } else {
        $error = "Gagal menghapus data atau data tidak ditemukan.";
    }
}

// Jika edit mode aktif (ada ?edit=id)
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $presID = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM prestb WHERE presID = ?");
    $stmt->bind_param("i", $presID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $pid = $row['pid'];
        $dokter_id = $row['dokter_id'];
        $fname = $row['fname'];
        $lname = $row['lname'];
        $appdate = $row['appdate'];
        $apptime = $row['apptime'];
        $disease = $row['disease'];
        $allergy = $row['allergy'];
        $prescription = $row['prescription'];
    } else {
        $error = "Data rekam medis tidak ditemukan.";
        $edit_mode = false;
    }
}

// Proses form submit (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $presID_post = $_POST['presID'] ?? null;
    $pid_post = $_POST['pid'];
    $dokter_id_post = $_POST['dokter_id'];
    $fname_post = $_POST['fname'];
    $lname_post = $_POST['lname'];
    $appdate_post = $_POST['appdate'];
    $apptime_post = $_POST['apptime'];
    $disease_post = $_POST['disease'];
    $allergy_post = $_POST['allergy'];
    $prescription_post = $_POST['prescription'];

    if ($presID_post) {
        // Update
        $stmt = $conn->prepare("UPDATE prestb SET pid=?, dokter_id=?, fname=?, lname=?, appdate=?, apptime=?, disease=?, allergy=?, prescription=? WHERE presID=?");
        $stmt->bind_param("iisssssssi", $pid_post, $dokter_id_post, $fname_post, $lname_post, $appdate_post, $apptime_post, $disease_post, $allergy_post, $prescription_post, $presID_post);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            header("Location: rekam_medis.php");
            exit();
        } else {
            $error = "Update gagal atau tidak ada perubahan.";
        }
    } else {
        // Insert baru
        $stmt = $conn->prepare("INSERT INTO prestb (pid, dokter_id, fname, lname, appdate, apptime, disease, allergy, prescription) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssssss", $pid_post, $dokter_id_post, $fname_post, $lname_post, $appdate_post, $apptime_post, $disease_post, $allergy_post, $prescription_post);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            header("Location: rekam_medis.php");
            exit();
        } else {
            $error = "Gagal menambah data rekam medis.";
        }
    }
}

// Ambil semua data rekam medis untuk tabel
$query = "SELECT presID, pid, dokter_id, fname, lname, appdate, apptime, disease, allergy, prescription FROM prestb ORDER BY appdate DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Data Rekam Medis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        textarea { resize: vertical; }
        .form-label { font-weight: 600; }
        .form-section { margin-bottom: 2rem; }
    </style>
</head>
<body>
<div class="container my-4">
    <h2 class="mb-4">Data Rekam Medis</h2>
    <a href="../admin_dashboard.php" class="btn btn-outline-primary mb-4">⬅️ Kembali ke Dashboard Admin</a>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="form-section">
        <h3><?= $edit_mode ? "✏️ Edit" : "➕ Tambah" ?> Rekam Medis</h3>
        <form method="post" action="">
            <input type="hidden" name="presID" value="<?= htmlspecialchars($presID) ?>">

            <div class="row g-3">
                <div class="col-md-3">
                    <label for="pid" class="form-label">ID Pasien</label>
                    <input type="number" class="form-control" name="pid" id="pid" value="<?= ($pid) ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="dokter_id" class="form-label">ID Dokter</label>
                    <input type="number" class="form-control" name="dokter_id" id="dokter_id" value="<?= ($dokter_id) ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="fname" class="form-label">Nama Depan Pasien</label>
                    <input type="text" class="form-control" name="fname" id="fname" value="<?= ($fname) ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="lname" class="form-label">Nama Belakang Pasien</label>
                    <input type="text" class="form-control" name="lname" id="lname" value="<?= ($lname) ?>" required>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <label for="appdate" class="form-label">Tanggal Rekam Medis</label>
                    <input type="date" class="form-control" name="appdate" id="appdate" value="<?= ($appdate) ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="apptime" class="form-label">Waktu Rekam Medis</label>
                    <input type="time" class="form-control" name="apptime" id="apptime" value="<?= ($apptime) ?>" required>
                </div>
            </div>

            <div class="mt-3">
                <label for="disease" class="form-label">Diagnosa Penyakit</label>
                <textarea name="disease" id="disease" rows="3" class="form-control" required><?= ($disease) ?></textarea>
            </div>

            <div class="mt-3">
                <label for="allergy" class="form-label">Alergi</label>
                <textarea name="allergy" id="allergy" rows="2" class="form-control"><?= ($allergy) ?></textarea>
            </div>

            <div class="mt-3">
                <label for="prescription" class="form-label">Resep Obat</label>
                <textarea name="prescription" id="prescription" rows="4" class="form-control"><?= ($prescription) ?></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><?= $edit_mode ? "Update" : "Simpan" ?></button>
                <?php if ($edit_mode): ?>
                    <a href="rekam_medis.php" class="btn btn-secondary ms-2">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <hr>

    <div class="table-responsive">
        <h3>📋 Daftar Rekam Medis</h3>
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>ID Pasien</th>
                    <th>ID Dokter</th>
                    <th>Nama Depan</th>
                    <th>Nama Belakang</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Diagnosa</th>
                    <th>Alergi</th>
                    <th>Resep</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= ($row['presID']) ?></td>
                        <td><?= ($row['pid']) ?></td>
                        <td><?=($row['dokter_id']) ?></td>
                        <td><?= ($row['fname']) ?></td>
                        <td><?= ($row['lname']) ?></td>
                        <td><?= ($row['appdate']) ?></td>
                        <td><?= ($row['apptime']) ?></td>
                        <td><?= ($row['disease']) ?></td>
                        <td><?= ($row['allergy']) ?></td>
                        <td><?= ($row['prescription']) ?></td>
                        <td>
                            <a href="?edit=<?= ($row['presID']) ?>" class="btn btn-sm btn-warning mb-1">Edit</a><br>
                            <a href="?delete=<?= ($row['presID']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus rekam medis ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="11">Tidak ada data rekam medis.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
