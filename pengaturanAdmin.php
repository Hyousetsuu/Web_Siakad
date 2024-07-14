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
$role = $_SESSION['role'];

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];

    if ($_FILES['profile_pic']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);
    } else {
        $target_file = $_POST['current_pic'];
    }

    $stmt = $conn->prepare("UPDATE login SET nama = ?, profile_pic = ? WHERE id = ?");
    $stmt->bind_param('ssi', $nama, $target_file, $user_id);
    $stmt->execute();
    $stmt->close();

    header('Location: pengaturanAdmin.php');
    exit();
}

$stmt = $conn->prepare("SELECT nama, profile_pic FROM login WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($nama, $profile_pic);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="pengaturanAdmin.css?v=<?php echo time();?>">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <h2>Siakad</h2>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="fakultas.php"><i class="fas fa-building"></i> Fakultas</a></li>
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
        <div class="content">
            <h2>Pengaturan Akun</h2>
            <form action="pengaturanAdmin.php" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>" required>
                </div>
                <div>
                    <label for="profile_pic">Foto Profil:</label>
                    <input type="file" id="profile_pic" name="profile_pic">
                    <input type="hidden" name="current_pic" value="<?php echo $profile_pic; ?>">
                    <img src="<?php echo $profile_pic ? $profile_pic : 'default-profile.png'; ?>" alt="Profile Picture" width="100" height="100">
                </div>
                <button type="submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
            document.querySelector('.main-content').classList.toggle('shifted');
        });
    </script>
</body>
<footer class="footer">
    <div class="footer-content">
        <p>&copy;2024 SIAKAD. All rights reserved.</p>
        <p>
            <a href="#">Privacy Policy</a> |
            <a href="#">Terms of Service</a> |
            <a href="#">Contact Us</a>
        </p>
    </div>
</footer>
</html>
``
