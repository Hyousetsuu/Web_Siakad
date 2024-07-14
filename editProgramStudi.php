    <?php
    include("koneksi.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['nama_program_studi'], $_POST['fakultas']) && !empty($_POST['nama_program_studi'])) {
            $nama_program_studi = $_POST['nama_program_studi'];
            $id_fakultas = $_POST['fakultas'];

            if (isset($_POST['program_studi_id']) && !empty($_POST['program_studi_id'])) {
                // Update existing program studi
                $program_studi_id = $_POST['program_studi_id'];
                $stmt = $conn->prepare("UPDATE program_studi SET nama_program_studi = ?, id_fakultas = ? WHERE id = ?");
                $stmt->bind_param("sii", $nama_program_studi, $id_fakultas, $program_studi_id);
            } else {
                // Insert new program studi
                $stmt = $conn->prepare("INSERT INTO program_studi (nama_program_studi, id_fakultas) VALUES (?, ?)");
                $stmt->bind_param("si", $nama_program_studi, $id_fakultas);
            }

            if ($stmt->execute()) {
                // Redirect back to programstudi.php with success message
                header('Location: programstudi.php?success=1');
                exit();
            } else {
                // Handle error
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Nama Program Studi atau Fakultas tidak boleh kosong.";
        }
    }

    $conn->close();
    ?>



            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Edit Program Studi - Siakad</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
                <link rel="stylesheet" href="abc.css">
            </head>
            <body>
                <div class="main-content">
                    <header>
                        <h2>Edit Data Program Studi</h2>
                    </header>
                    <main>
                    <form id="form-program-studi" action="editProgramStudi.php" method="post">
        <input type="hidden" id="program-studi-id" name="program_studi_id">
        <label for="nama_program_studi">Nama Program Studi:</label>
        <input type="text" id="nama_program_studi" name="nama_program_studi" required>
        <button type="submit" id="submit-button">Submit</button>
    </form>

                    </main>
                </div>
            </body>
            </html>
