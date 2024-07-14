<?php

// Include database connection file
include("koneksi.php");

// Check if ID parameter exists
if (isset($_GET['id'])) {
    $id_dosen = $_GET['id'];
    $sql = "DELETE FROM dosen WHERE id = $id_dosen";
    
    if (mysqli_query($conn, $sql)) {
        header('Location: dosen.php?success_delete=1');
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    

    $stmt->close();
}

$conn->close();
?>
