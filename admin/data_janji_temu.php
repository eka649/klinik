<?php

include '../config.php';

// Handle hapus janji temu
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $del = mysqli_query($conn, "DELETE FROM appointmenttb WHERE id = $delete_id");
    if ($del) {
        $msg = "Janji temu berhasil dihapus.";
    } else {
        $error = "Gagal menghapus janji temu.";
    }
}

// Handle update status janji temu
if (isset($_POST['update_status'])) {
    $id = intval($_POST['appointment_id']);
    $userStatus = intval($_POST['userStatus']);
    $doctorStatus = intval($_POST['doctorStatus']);

    $update = mysqli_query($conn, "UPDATE appointmenttb SET userStatus = $userStatus, doctorStatus = $doctorStatus WHERE appID = $id");

    if ($update) {
        $msg = "Status janji temu berhasil diperbarui.";
    } else {
        $error = "Gagal memperbarui status janji temu.";
    }
}

// Ambil data janji temu lengkap
$sql = "SELECT a.appID, a.appdate, a.apptime, a.userStatus, a.doctorStatus,
        p.fname AS pasien_fname, p.lname AS pasien_lname,
        d.doctorname, d.spec
        FROM appointmenttb a
        JOIN patreg p ON a.pid = p.pid
        JOIN doctb d ON a.dokter_id = d.dokter_id
        ORDER BY a.appdate DESC, a.apptime DESC";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Data Janji Temu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        form.inline { display: inline-block; margin: 0 0.25rem; }
        .table-responsive { max-height: 600px; overflow-y: auto; }
        button.update-btn { min-width: 75px; }
    </style>
</head>
<body>
<div class="container my-4">
    <h2 class="mb-4"> Data Janji Temu</h2>
    

    <?php if (isset($msg)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle text-center">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Nama Pasien</th>
                <th>Dokter</th>
                <th>Spesialis</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status Pasien</th>
                <th>Status Dokter</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['appID']) ?></td>
                    <td><?= htmlspecialchars($row['pasien_fname'] . ' ' . $row['pasien_lname']) ?></td>
                    <td><?= htmlspecialchars($row['doctorname']) ?></td>
                    <td><?= htmlspecialchars($row['spec']) ?></td>
                    <td><?= htmlspecialchars($row['appdate']) ?></td>
                    <td><?= htmlspecialchars($row['apptime']) ?></td>
                    <td>
                        <?php 
                        if ($row['userStatus'] === null) echo '<span class="text-secondary">⏳ Menunggu</span>';
                        elseif ($row['userStatus'] == 1) echo '<span class="text-success">✅ Aktif</span>';
                        else echo '<span class="text-danger">❌ Dibatalkan</span>';
                        ?>
                    </td>
                    <td>
                        <?php 
                        if ($row['doctorStatus'] === null) echo '<span class="text-secondary">⏳ Menunggu</span>';
                        elseif ($row['doctorStatus'] == 1) echo '<span class="text-success">✅ Aktif</span>';
                        else echo '<span class="text-danger">❌ Dibatalkan</span>';
                        ?>
                    </td>
                    <td>
                        <form class="inline" method="post" action="">
                            <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($row['appID']) ?>">
                            <select name="userStatus" class="form-select form-select-sm mb-1" required>
                                <option value="1" <?= $row['userStatus'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $row['userStatus'] == 0 ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>
                            <select name="doctorStatus" class="form-select form-select-sm mb-2" required>
                                <option value="1" <?= $row['doctorStatus'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $row['doctorStatus'] == 0 ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-success update-btn w-100">Update</button>
                        </form>

                        <form class="inline" method="get" action="" onsubmit="return confirm('Yakin ingin menghapus janji temu ini?');">
                            <input type="hidden" name="delete_id" value="<?= htmlspecialchars($row['appID']) ?>">
                            <button type="submit" class="btn btn-sm btn-danger mt-2 w-100">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="9">Tidak ada janji temu.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
    <a href="../admin_dashboard.php" class="btn btn-outline-primary mb-3"> < Kembali </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
