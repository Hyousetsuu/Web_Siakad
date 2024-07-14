<?php
include("koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fakultas = $_POST['fakultas'];
    $program_studi = $_POST['program_studi'];
    $nama_mata_kuliah = $_POST['matakuliah'];
    $jumlah_sks = $_POST['jumlah_sks'];
    $dosen = $_POST['dosen'];

    // Debugging information
    echo "Fakultas: $fakultas<br>";
    echo "Program Studi: $program_studi<br>";
    echo "Nama Mata Kuliah: $nama_mata_kuliah<br>";
    echo "Jumlah SKS: $jumlah_sks<br>";
    echo "Dosen: $dosen<br>";

    // Prepare the SQL statement
    $sql = "INSERT INTO matakuliah (id_program_studi, nama_mata_kuliah, jumlah_sks, id_dosen) 
            VALUES ('$program_studi', '$nama_mata_kuliah', '$jumlah_sks', '$dosen')";

    // Debugging information
    echo "SQL: $sql<br>";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        header("Location: matakuliah.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
