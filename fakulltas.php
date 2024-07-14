<?php
include("koneksi.php");
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include('koneksi.php');

$user_id = $_SESSION['user_id'];


// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Initialize variables
$fakultas_id = null;
$nama_fakultas = '';
$role = $_SESSION['role'];
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
}

$stmt = $conn->prepare("SELECT nama, profile_pic FROM login WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($nama, $profile_pic);
$stmt->fetch();
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fakultas - Siakad</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="fakultas.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <h2>Siakad</h2>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="fakultas.php" class="active"><i class="fas fa-building"></i> Fakultas</a></li>
            <li><a href="programstudi.php"><i class="fas fa-graduation-cap"></i> Program Studi</a></li>
            <li><a href="matakuliah.php"><i class="fas fa-book"></i> Mata Kuliah</a></li>
            <li><a href="dosen.php"><i class="fas fa-chalkboard-teacher"></i> Daftar Dosen</a></li>
            <li><a href="mahasiswa.php"><i class="fas fa-user-graduate"></i> Mahasiswa</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
        <button id="sidebar-toggle"><i class="fas fa-bars"></i></button>
            <div class="user-wrapper">
                <img src="<?php echo $profile_pic ? $profile_pic : 'default-profile.png'; ?>" alt="User" width="30" height="30">
                <div>
                    <h4><?php echo $nama; ?></h4>
                    <small><?php echo ucfirst($role); ?></small>
                </div>
            </div>
        </header>

        <main>
        <div class="content-container card">
        <h2>Fakultas</h2>
            <button class="add" onclick="openModal()">Tambah Data Fakultas</button>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Fakultas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('koneksi.php');

                        // Ambil data fakultas
                        $sql = "SELECT id, nama_fakultas FROM fakultas";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$row['nama_fakultas']}</td>
                                    <td>
                                        <form action='' method='post' class='edit-form'>
                                            <input type='hidden' name='fakultas_id' value='{$row['id']}'>
                                            <button class='add' type='submit' name='edit'>Edit</button>
                                        </form>
                                        <form action='deleteFakultas.php' method='post' onsubmit='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")' class='delete-form'>
                                            <input type='hidden' name='fakultas_id' value='{$row['id']}'>
                                            <button class='add' type='submit' name='delete' class='delete-btn'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='3'>Tidak ada data</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <div class="empty-state">
                    Tidak ada data fakultas lain saat ini.
                </div>
            </div>

            <!-- Modal for Adding Fakultas -->
            <div id="modal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Tambah Data Fakultas</h2>
                    <form action="addFakultas.php" method="post">
                        <label for="nama_fakultas">Nama Fakultas:</label>
                        <input type="text" id="nama_fakultas" name="nama_fakultas" required>
                        <input type="submit" value="Tambah Fakultas">
                    </form>
                </div>
            </div>

            <!-- Modal for Editing Fakultas -->
            <?php if ($fakultas_id): ?>
            <div id="editModal" class="modal" style="display: block;">
                <div class="modal-content">
                    <span class="close" onclick="closeEditModal()">&times;</span>
                    <h2>Edit Data Fakultas</h2>
                    <form action="updateFakultas.php" method="post">
                        <input type="hidden" name="fakultas_id" value="<?php echo $fakultas_id; ?>">
                        <label for="nama_fakultas">Nama Fakultas:</label>
                        <input type="text" id="nama_fakultas" name="nama_fakultas" value="<?php echo $nama_fakultas; ?>" required>
                        <button type="submit" name="update">Update</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <button class="tombol" onclick="scrollToTop()">
        <span class="svgIcon"><i class="fas fa-arrow-up"></i></span>
    </button>

    <script>
        function openModal() {
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            location.reload(); // Reload to remove edit modal
        }

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
            document.querySelector('.main-content').classList.toggle('shifted');
        });
    </script>

</body>
<footer class="footer">
    <div class="footer-content">
        <p>💀&copy;2024 SIAKAD. All rights reserved.💀</p>
        <p>
            <a href="#">Privacy Policy</a> |
            <a href="#">Terms of Service</a> |
            <a href="#">Contact Us</a>
        </p>
    </div>
</footer>
</html>
