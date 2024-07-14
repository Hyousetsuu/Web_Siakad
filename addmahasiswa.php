<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include("koneksi.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_mahasiswa'];
    $nim = $_POST['nim'];
    $program_studi = $_POST['program_studi'];
    $fakultas = $_POST['fakultas'];

    if (isset($_POST['mahasiswa_id']) && !empty($_POST['mahasiswa_id'])) {
        // Update existing mahasiswa
        $mahasiswa_id = $_POST['mahasiswa_id'];
        $query = "UPDATE mahasiswa SET nama=?, nim=?, program_studi=?, fakultas=? WHERE id=?";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "ssiii", $nama, $nim, $program_studi, $fakultas, $mahasiswa_id);
            if (mysqli_stmt_execute($stmt)) {
                header('Location: mahasiswa.php?message=Data berhasil diperbarui');
                exit();
            } else {
                die("Error executing statement: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        } else {
            die("Error preparing statement: " . mysqli_error($conn));
        }
    } else {
        // Insert new mahasiswa
        $query = "INSERT INTO mahasiswa (nama, nim, program_studi, fakultas) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "ssii", $nama, $nim, $program_studi, $fakultas);
            if (mysqli_stmt_execute($stmt)) {
                header('Location: mahasiswa.php?message=Data berhasil ditambahkan');
                exit();
            } else {
                die("Error executing statement: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        } else {
            die("Error preparing statement: " . mysqli_error($conn));
        }
    }
} else {
    header('Location: mahasiswa.php');
    exit();
}
?>
