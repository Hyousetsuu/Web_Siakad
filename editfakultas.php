<?php
include("koneksi.php");

// Proses edit data fakultas jika tombol 'edit' diklik
if (isset($_POST['edit'])) {
    $fakultas_id = $_POST['fakultas_id'];

    // Query untuk mengambil data fakultas berdasarkan ID
    $sql = "SELECT nama_fakultas FROM fakultas WHERE id = $fakultas_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama_fakultas = $row['nama_fakultas'];
    } else {
        echo "Data fakultas tidak ditemukan.";
        exit;
    }
} else {
    echo "ID fakultas tidak diterima.";
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fakultas - Siakad</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="abc.css">
</head>
<body>
    <div class="main-content">
        <header>
            <h2>Edit Data Fakultas</h2>
        </header>
        <main>
            <form action="updateFakultas.php" method="post">
                <input type="hidden" name="fakultas_id" value="<?php echo $fakultas_id; ?>">
                <label for="nama_fakultas">Nama Fakultas:</label>
                <input type="text" id="nama_fakultas" name="nama_fakultas" value="<?php echo $nama_fakultas; ?>" required>
                <button type="submit" name="update">Update</button>
            </form>
        </main>
    </div>
</body>
</html>
