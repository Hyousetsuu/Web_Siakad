<?php
include("koneksi.php");

if (isset($_POST['delete'])) {
    $fakultas_id = $_POST['fakultas_id'];

    // Hapus data fakultas berdasarkan ID
    $sql = "DELETE FROM fakultas WHERE id = $fakultas_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect kembali ke halaman fakultas.php dengan pesan alert
        header('Location: fakulltas.php?message=Data+fakultas+berhasil+dihapus.');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
