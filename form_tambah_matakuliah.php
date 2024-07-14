<?php
// Include database connection file
include("koneksi.php");

// Escape user inputs for security
$fakultas = $conn->real_escape_string($_POST['fakultas']);
$prodi = $conn->real_escape_string($_POST['prodi']);
$nama_matkul = $conn->real_escape_string($_POST['nama_matkul']);
$hari = $conn->real_escape_string($_POST['hari']);
$jam = $conn->real_escape_string($_POST['jam']);
$dosen = $conn->real_escape_string($_POST['dosen']);

// Insert data into database
$sql_insert = "INSERT INTO matakuliah (fakultas, prodi, nama_matkul, hari, jam, dosen)
               VALUES ('$fakultas', '$prodi', '$nama_matkul', '$hari', '$jam', '$dosen')";

if ($conn->query($sql_insert) === TRUE) {
    // Redirect back to matakuliah.php with success message
    header("Location: matakuliah.php?message=success");
    exit();
} else {
    echo "Error: " . $sql_insert . "<br>" . $conn->error;
}

// Close database connection
$conn->close();
?>
