<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIAKAD</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="heading">Login</div>
        <form action="login.php" method="post" class="form">
            <input required class="input" type="email" name="email" id="email" placeholder="E-mail">
            <input required class="input" type="password" name="password" id="password" placeholder="Password">
            <input class="login-button" type="submit" name="login" value="Login">
        </form>
    </div>
    <div id="custom-alert" class="custom-alert">
        <span id="alert-message"></span>
    </div>
</body>
<script>
function showAlert(message, type) {
    var alertBox = document.createElement('div');
    alertBox.className = 'alert ' + type;
    alertBox.innerHTML = message + '<button onclick="closeAlert(this)">OK</button>';

    document.body.appendChild(alertBox);
    
    setTimeout(function() {
        alertBox.classList.add('show');
    }, 10);
}

function closeAlert(button) {
    var alertBox = button.parentElement;
    alertBox.classList.remove('show');
    setTimeout(function() {
        document.body.removeChild(alertBox);
    }, 300);
}
</script>
</html>

<?php
session_start();
include('koneksi.php');

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, password, role FROM login WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $db_password, $db_role);
            $stmt->fetch();

            if (password_verify($password, $db_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = $db_role;

                $login_time = date('Y-m-d H:i:s');
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $page_accessed = 'dashboard.php';

                $stmt_insert = $conn->prepare("INSERT INTO login_history (user_id, login_time, ip_address, page_accessed) VALUES (?, ?, ?, ?)");
                $stmt_insert->bind_param("isss", $user_id, $login_time, $ip_address, $page_accessed);
                $stmt_insert->execute();
                $stmt_insert->close();

                if ($db_role === 'admin') {
                    echo '<script>showAlert("Login successful. Redirecting to admin dashboard.", "success"); setTimeout(function() { window.location.href = "dashboard.php"; }, 2000);</script>';
                } else {
                    echo '<script>showAlert("Login successful. Redirecting to user dashboard.", "success"); setTimeout(function() { window.location.href = "user.php"; }, 2000);</script>';
                }
                exit();
            } else {
                echo '<script>showAlert("Invalid password.", "error");</script>';
            }
        } else {
            echo '<script>showAlert("Invalid email.", "error");</script>';
        }

        $stmt->close();
    } else {
        echo '<script>showAlert("All fields are required.", "error");</script>';
    }
}
?>
