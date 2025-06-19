<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $pid = $_POST['pid'];
    $dokter_id = $_POST['dokter_id'];
    $appdate = $_POST['appdate'];
    $apptime = $_POST['apptime'];
    $biaya = $_POST['biaya'];
    $status = $_POST['status'];
    $tanggal_bayar = ($status === 'Lunas') ? date('Y-m-d H:i:s') : null;

    $sql = "INSERT INTO pembayaran (pid, dokter_id, appdate, apptime, biaya, status, tanggal_bayar)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iissdss", $pid, $dokter_id, $appdate, $apptime, $biaya, $status, $tanggal_bayar);
    mysqli_stmt_execute($stmt);

    header("Location: pembayaran_konsultasi.php?sukses=1");
    exit();
} else {
    header("Location: pembayaran_konsultasi.php");
    exit();
}
