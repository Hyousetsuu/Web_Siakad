<?php
// Ensure method used is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection file
    include("koneksi.php");

    // Retrieve data from POST
    $fakultas = isset($_POST['fakultas']) ? $_POST['fakultas'] : '';
    $prodi = isset($_POST['prodi']) ? $_POST['prodi'] : '';
    $nama_matkul = isset($_POST['nama_matkul']) ? $_POST['nama_matkul'] : '';
    $hari = isset($_POST['hari']) ? $_POST['hari'] : '';
    $jam = isset($_POST['jam']) ? $_POST['jam'] : '';
    $dosen = isset($_POST['dosen']) ? $_POST['dosen'] : '';

    // Check if data is empty
    if (empty($fakultas) || empty($prodi) || empty($nama_matkul) || empty($hari) || empty($jam) || empty($dosen)) {
        $message = "Semua data harus diisi!";
        header("Location: matakuliah.php?message=" . urlencode($message));
        exit();
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO matakuliah (fakultas, prodi, nama_matkul, hari, jam, dosen) VALUES (?, ?, ?, ?, ?, ?)");
        
        // Bind parameters
        $stmt->bind_param("ssssss", $fakultas, $prodi, $nama_matkul, $hari, $jam, $dosen);

        // Execute statement
        if ($stmt->execute()) {
            $message = "Mata kuliah berhasil ditambahkan";
            header("Location: matakuliah.php?message=" . urlencode($message));
        } else {
            $message = "Error: " . $stmt->error;
            header("Location: matakuliah.php?message=" . urlencode($message));
        }

        // Close statement
        $stmt->close();
        exit();
    }
} else {
    // If method used is not POST, show error message
    $message = "Metode yang digunakan bukan POST";
    header("Location: matakuliah.php?message=" . urlencode($message));
    exit();
}

// Close connection
$conn->close();
?>
