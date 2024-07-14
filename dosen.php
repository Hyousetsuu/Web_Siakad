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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Include database connection file
include("koneksi.php");

// Query to fetch data
$sql = "SELECT dosen.id, fakultas.nama_fakultas, program_studi.nama_program_studi, dosen.nama_dosen
        FROM dosen
        INNER JOIN program_studi ON dosen.id_program_studi = program_studi.id
        INNER JOIN fakultas ON program_studi.id_fakultas = fakultas.id";
$result = $conn->query($sql);

// Query to fetch fakultas data
$sql_fakultas = "SELECT id, nama_fakultas FROM fakultas";
$result_fakultas = $conn->query($sql_fakultas);

// Query to fetch program studi data
$sql_program_studi = "SELECT id, nama_program_studi FROM program_studi";
$result_program_studi = $conn->query($sql_program_studi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dosen - Siakad</title>
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
            <li><a href="dosen.php" class="active"><i class="fas fa-chalkboard-teacher"></i> Daftar Dosen</a></li>
            <li><a href="mahasiswa.php"><i class="fas fa-user-graduate"></i> Mahasiswa</a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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
                <h2>Daftar Dosen</h2>
                <button class="add" onclick="openModal()">Tambah Data Dosen</button>

                <!-- Modal for Adding Dosen -->
                <div id="modal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <h2>Tambah Data Dosen</h2>
                        <form id="form-dosen" action="tambahDosen.php" method="post">
                            <label for="fakultas">Nama Fakultas:</label>
                            <select id="fakultas" name="fakultas" required>
                                <option value="">Pilih Fakultas</option>
                                <?php
                                if ($result_fakultas->num_rows > 0) {
                                    while ($row_fakultas = $result_fakultas->fetch_assoc()) {
                                        echo "<option value='{$row_fakultas['id']}'>{$row_fakultas['nama_fakultas']}</option>";
                                    }
                                } else {
                                    echo "<option value='' disabled>Tidak ada fakultas tersedia</option>";
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
                                } else {
                                    echo "<option value='' disabled>Tidak ada program studi tersedia</option>";
                                }
                                ?>
                            </select>

                            <label for="nama_dosen">Nama Dosen:</label>
                            <input type="text" id="nama_dosen" name="nama_dosen" required>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>

                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required>

                            <input type="submit" value="Tambah Dosen">
                        </form>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Fakultas</th>
                            <th>Nama Program Studi</th>
                            <th>Nama Dosen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            // Output data setiap baris
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td>{$row['nama_fakultas']}</td>";
                                echo "<td>{$row['nama_program_studi']}</td>";
                                echo "<td>{$row['nama_dosen']}</td>";
                                echo "<td class='action-buttons'>";
                                echo "<button class='add' onclick=\"location.href='editdosen.php?id={$row['id']}'\">Edit</button>";
                                echo "<form action='deleteDosen.php?id={$row['id']}' method='post' style='display:inline;' onsubmit=\"return confirm('Anda yakin ingin menghapus data dosen ini?')\">";
                                echo "<button class='add' type='submit'>Hapus</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Tidak ada data dosen</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div class="empty-state">
                    Tidak ada data dosen lain saat ini.
                </div>
            </div>
        </main>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
            document.querySelector('.main-content').classList.toggle('shifted');
        });
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
