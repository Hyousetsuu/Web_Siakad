<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect if not logged in or not an admin
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

// Fetch mahasiswa data
$sql = "SELECT mahasiswa.id, mahasiswa.nim, fakultas.nama_fakultas, program_studi.nama_program_studi, mahasiswa.nama 
        FROM mahasiswa 
        INNER JOIN program_studi ON mahasiswa.program_studi = program_studi.id 
        INNER JOIN fakultas ON program_studi.id_fakultas = fakultas.id";
$result = $conn->query($sql);

// Fetch fakultas and program studi data
$sql_fakultas = "SELECT id, nama_fakultas FROM fakultas";
$result_fakultas = $conn->query($sql_fakultas);

$sql_program_studi = "SELECT id, nama_program_studi FROM program_studi";
$result_program_studi = $conn->query($sql_program_studi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa - Siakad</title>
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
            <li><a href="programstudi.php"><i class="fas fa-graduation-cap"></i> Program Studi</a></li>
            <li><a href="matakuliah.php"><i class="fas fa-book"></i> Mata Kuliah</a></li>
            <li><a href="dosen.php"><i class="fas fa-chalkboard-teacher"></i> Daftar Dosen</a></li>
            <li><a href="mahasiswa.php" class="active"><i class="fas fa-user-graduate"></i> Mahasiswa</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <button id="sidebar-toggle"><i class="fas fa-bars"></i></button>
            <div class="user-wrapper">
                <img src="<?php echo $profile_pic ?: 'default-profile.png'; ?>" alt="User" width="30" height="30">
                <div>
                    <h4><?php echo $nama; ?></h4>
                    <small><?php echo ucfirst($role); ?></small>
                </div>
            </div>
        </header>
        
        <main>
            <div class="content-container card">
                <h2>Daftar Mahasiswa</h2>
                <button class="add" onclick="openModal()">Tambah Data Mahasiswa</button>
                
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Fakultas</th>
                            <th>Nama Program Studi</th>
                            <th>Nama Mahasiswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$no}</td>
                                        <td>{$row['nim']}</td>
                                        <td>{$row['nama_fakultas']}</td>
                                        <td>{$row['nama_program_studi']}</td>
                                        <td>{$row['nama']}</td>
                                        <td>
                                            <div class='action-buttons'>
                                                <button class='add' type='button' onclick=\"openModal(true, {$row['id']}, '{$row['nama']}', '{$row['nim']}')\">Edit</button>
                                                <form action='deleteMahasiswa.php' method='post' onsubmit=\"return confirm('Apakah Anda yakin ingin menghapus data ini?')\">
                                                    <input type='hidden' name='mahasiswa_id' value='{$row['id']}'>
                                                    <button class='add' type='submit' name='delete'>Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <div class="empty-state">
                    Tidak ada data mahasiswa saat ini.
                </div>
            </div>
        </main>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modal-title">Tambah Data Mahasiswa</h2>
            <form id="form-mahasiswa" action="addMahasiswa.php" method="post">
                <input type="hidden" id="mahasiswa-id" name="mahasiswa_id">
                
                <label for="fakultas">Nama Fakultas:</label>
                <select id="fakultas" name="fakultas" required>
                    <option value="">Pilih Fakultas</option>
                    <?php
                    if ($result_fakultas->num_rows > 0) {
                        while ($row_fakultas = $result_fakultas->fetch_assoc()) {
                            echo "<option value='{$row_fakultas['id']}'>{$row_fakultas['nama_fakultas']}</option>";
                        }
                    }
                    ?>
                </select>

                <label for="program_studi">Nama Program Studi:</label>
                <select id="program_studi" name="program_studi" required>
                    <option value="">Pilih Program Studi</option>
                    <?php
                    if ($result_program_studi->num_rows > 0) {
                        while ($row_program_studi = $result_program_studi->fetch_assoc()) {
                            echo "<option value='{$row_program_studi['id']}'>{$row_program_studi['nama_program_studi']}</option>";
                        }
                    }
                    ?>
                </select>

                <label for="nama_mahasiswa">Nama Mahasiswa:</label>
                <input type="text" id="nama_mahasiswa" name="nama_mahasiswa" required>

                <label for="nim">NIM:</label>
                <input type="text" id="nim" name="nim" required>

                <button class="submit" type="submit" id="submit-button">Submit</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(edit = false, id = null, name = '', nim = '') {
            document.getElementById('modal').style.display = 'block';
            document.getElementById('form-mahasiswa').action = edit ? 'editMahasiswa.php' : 'addMahasiswa.php';
            document.getElementById('mahasiswa-id').value = edit ? id : '';
            document.getElementById('nama_mahasiswa').value = edit ? name : '';
            document.getElementById('nim').value = edit ? nim : '';
            document.getElementById('modal-title').innerText = edit ? 'Edit Data Mahasiswa' : 'Tambah Data Mahasiswa';
            document.getElementById('submit-button').innerText = edit ? 'Update' : 'Tambah';
        }

        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
            document.querySelector('.main-content').classList.toggle('shifted');
        });

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

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
</html>
