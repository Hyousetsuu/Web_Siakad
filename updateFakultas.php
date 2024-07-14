<?php
include("koneksi.php");

if (isset($_POST['update'])) {
    $fakultas_id = $_POST['fakultas_id'];
    $nama_fakultas = $_POST['nama_fakultas'];

    // Lakukan sanitasi data jika diperlukan
    $nama_fakultas = mysqli_real_escape_string($conn, $nama_fakultas);

    // Query untuk update data fakultas
    $sql = "UPDATE fakultas SET nama_fakultas = '$nama_fakultas' WHERE id = $fakultas_id";

    if ($conn->query($sql) === TRUE) {
        echo "Data fakultas berhasil diupdate.";
        // Redirect kembali ke halaman fakultas.php setelah update
        header('Location: fakulltas.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
