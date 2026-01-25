<?php
session_start();
require '../config/db.php';

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = false;
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['nama_admin'] = $row['nama_lengkap'];
            header("Location: index.php");
            exit;
        }
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Desa Merak Batin</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/merakbatin.jpeg">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/merakbatin.jpeg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; /* Abu-abu sangat muda */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start; 
            padding-top: 100px; /* Menjaga posisi tetap di atas */
            height: 100vh;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #dee2e6;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 550px; /* Lebih lebar sesuai keinginan Anda */
            padding:40px;
        }

        .brand-section {
            text-align: center;
            margin-bottom: 5px;
        }

        .brand-section i {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 5px;
            display: block;
        }

        .brand-section h3 {
            font-weight: 700;
            color: #212529;
            margin-bottom: 5px;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            padding: 14px;
            border-radius: 10px;
            background-color: #fdfdfd;
        }

        .btn-login {
            background-color: #0d6efd;
            border: none;
            padding: 14px;
            font-weight: 700;
            border-radius: 10px;
            margin-top: 10px;
        }

        .footer-text {
            text-align: center;
            margin-top: 5px;
            font-size: 0.8rem;
            color: #adb5bd;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-section">
        <i class="bi bi-shield-lock"></i>
        <h3>Admin</h3>
        <p class="text-muted">Sistem Informasi Desa Merak Batin</p>
    </div>

    <?php if ($error) : ?>
        <div class="alert alert-danger border-0 small py-3 text-center mb-4" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i> Username atau password salah!
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-4">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
        </div>
        
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>

        <div class="d-grid">
            <button type="submit" name="login" class="btn btn-primary btn-login">
                Masuk ke Panel Kontrol
            </button>
        </div>
    </form>

    <div class="footer-text">
        &copy; 2026 Desa Merak Batin &bull; KKN Tematik Universitas Lampung
    </div>
</div>

</body>
</html>