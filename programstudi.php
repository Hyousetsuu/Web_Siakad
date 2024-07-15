<?php
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
$role = $_SESSION['role'];

$stmt = $conn->prepare("SELECT nama, profile_pic FROM login WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($nama, $profile_pic);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Studi - Siakad</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="programstudi.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <h2>Siakad</h2>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="fakulltas.php"><i class="fas fa-building"></i> Fakultas</a></li>
            <li><a href="programstudi.php" class="active"><i class="fas fa-graduation-cap"></i> Program Studi</a></li>
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
                <img src="<?php echo $profile_pic ? htmlspecialchars($profile_pic) : 'default-profile.png'; ?>" alt="User" width="30" height="30">
                <div>
                    <h4><?php echo htmlspecialchars($nama); ?></h4>
                    <small><?php echo ucfirst(htmlspecialchars($role)); ?></small>
                </div>
            </div>
        </header>
        <main>
            <div class="content-container card">
                <h2>Program Studi</h2>
                <button class="add" onclick="openModal()">Tambah Data Program Studi</button>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Fakultas</th>
                            <th>Nama Program Studi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Koneksi ke database
                        include("koneksi.php");
                        
                        // Ambil data program studi beserta nama fakultas
                        $sql = "SELECT program_studi.id, program_studi.nama_program_studi, fakultas.nama_fakultas, program_studi.id_fakultas 
                                FROM program_studi 
                                INNER JOIN fakultas ON program_studi.id_fakultas = fakultas.id";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Inisialisasi nomor urut
                            $no = 1;
                            // Output data setiap baris
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$no}</td>
                                        <td>" . htmlspecialchars($row['nama_fakultas']) . "</td>
                                        <td>" . htmlspecialchars($row['nama_program_studi']) . "</td>
                                        <td>
                                            <div class='action-buttons'>
                                                <button class='add' type='button' onclick=\"openModal(true, {$row['id']}, '" . htmlspecialchars($row['nama_program_studi']) . "', '{$row['id_fakultas']}')\">Edit</button>
                                                <form action='deleteProgramStudi.php' method='post' onsubmit=\"return confirm('Apakah Anda yakin ingin menghapus data ini?')\">
                                                    <input type='hidden' name='program_studi_id' value='{$row['id']}'>
                                                    <button class='add' type='submit' name='delete'>Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>";
                                // Increment nomor urut
                                $no++;
                            }                        
                        } else {
                            echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <div class="empty-state">
                    Tidak ada data program studi lain saat ini.
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for Adding and Editing Program Studi -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modal-title">Tambah Data Program Studi</h2>
            <form id="form-program-studi" action="editProgramStudi.php" method="post">
                <input type="hidden" id="program-studi-id" name="program_studi_id">
                <label for="nama_program_studi">Nama Program Studi:</label>
                <input type="text" id="nama_program_studi" name="nama_program_studi" required>
                
                <label for="fakultas">Fakultas:</label>
                <select id="fakultas" name="fakultas" required>
                    <?php
                    include("koneksi.php");
                    $sql_fakultas = "SELECT id, nama_fakultas FROM fakultas";
                    $result_fakultas = $conn->query($sql_fakultas);

                    if ($result_fakultas->num_rows > 0) {
                        while($row_fakultas = $result_fakultas->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row_fakultas['id']) . "'>" . htmlspecialchars($row_fakultas['nama_fakultas']) . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>Tidak ada fakultas tersedia</option>";
                    }
                    $conn->close();
                    ?>
                </select>
                
                <button class="submit" type="submit" id="submit-button">Submit</button>
            </form>
        </div>
    </div>

    <script>
    function openModal(edit = false, id = null, name = '', id_fakultas = null) {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('form-program-studi').action = 'editProgramStudi.php';
        document.getElementById('program-studi-id').value = edit ? id : '';
        document.getElementById('nama_program_studi').value = edit ? name : '';
        document.getElementById('modal-title').innerText = edit ? 'Edit Data Program Studi' : 'Tambah Data Program Studi';
        document.getElementById('submit-button').innerText = edit ? 'Update' : 'Tambah';

        // Set selected option for fakultas dropdown
        if (edit) {
            document.getElementById('fakultas').value = id_fakultas;
        }
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }

    document.getElementById('sidebar-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('hidden');
        document.querySelector('.main-content').classList.toggle('shifted');
    });

    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    </script>
    <button class="tombol" onclick="scrollToTop()">
        <span class="svgIcon"><i class="fas fa-arrow-up"></i></span>
    </button>
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
</
