<?php
// Include database connection file
include("koneksi.php");

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $id_dosen = $_POST['id_dosen'];
    $nama_dosen = $_POST['nama_dosen'];
    $email_dosen = $_POST['email_dosen'];
    $password_dosen = $_POST['password_dosen'];

    // Update data dosen in database
    $sql_update = "UPDATE dosen SET nama_dosen = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssi", $nama_dosen, $email_dosen, $password_dosen, $id_dosen);

    if ($stmt->execute()) {
        // Redirect to a success page or the previous page
        header('Location: dosen.php'); // Redirect to dosen.php after successful update
        exit();
    } else {
        // Handle error if update fails
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed directly without POST submission
    header('Location: dosen.php');
    exit();
}
?>
