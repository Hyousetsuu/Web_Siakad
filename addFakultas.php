<?php
// Koneksi ke database
include("koneksi.php");

// Ambil data yang dikirimkan dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_fakultas = $_POST['nama_fakultas'];

    // Insert data fakultas ke dalam database
    $sql = "INSERT INTO fakultas (nama_fakultas) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nama_fakultas);

    if ($stmt->execute()) {
        // Redirect ke halaman fakultas.php setelah berhasil ditambahkan
        header('Location: fakulltas.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
