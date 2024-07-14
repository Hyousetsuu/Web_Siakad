<?php
// Include database connection file
include("koneksi.php");

// Check if ID is set in URL parameter
if (isset($_GET['id'])) {
    $id_dosen = $_GET['id'];

    // Query to fetch dosen details by ID
    $sql = "SELECT * FROM dosen WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_dosen);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Display form for editing dosen details
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Dosen - Siakad</title>
            <link rel="stylesheet" href="dashboard.css">
        </head>
        <body>
            <h2>Edit Dosen</h2>
            <form method="post" action="updateDosen.php">
                <input type="hidden" name="id_dosen" value="<?php echo $row['id']; ?>">
                <label for="nama_dosen">Nama Dosen:</label>
                <input type="text" id="nama_dosen" name="nama_dosen" value="<?php echo isset($row['nama_dosen']) ? $row['nama_dosen'] : ''; ?>" required>

                <label for="email_dosen">Email:</label>
                <input type="email" id="email_dosen" name="email_dosen" value="<?php echo isset($row['email_dosen']) ? $row['email_dosen'] : ''; ?>" required>

                <label for="password_dosen">Password:</label>
                <input type="password" id="password_dosen" name="password_dosen" value="<?php echo isset($row['password_dosen']) ? $row['password_dosen'] : ''; ?>" required>

                <!-- Additional fields as needed (e.g., program_studi, fakultas) -->

                <input type="submit" value="Simpan">
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Dosen with ID {$id_dosen} not found.";
    }

    $stmt->close();
} else {
    echo "ID parameter is missing.";
}
?>
