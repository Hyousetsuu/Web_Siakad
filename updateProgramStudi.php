<?php
// Koneksi ke database
include("koneksi.php");

// Pastikan hanya admin yang dapat mengakses halaman ini
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Proses jika tombol update diklik
if (isset($_POST['update'])) {
    $program_studi_id = $_POST['program_studi_id'];
    $nama_program_studi = $_POST['nama_program_studi'];

    // Update data program studi ke database
    $sql = "UPDATE program_studi SET nama_program_studi = '$nama_program_studi' WHERE id = $program_studi_id";

    if ($conn->query($sql) === TRUE) {
        echo "Data program studi berhasil diperbarui.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
