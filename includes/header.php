<?php
include 'config/db.php';
$profil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM profil WHERE id=1"));

// Cek apakah di halaman home atau bukan
$current_page = basename($_SERVER['PHP_SELF']);
$is_home = ($current_page == 'index.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Merak Batin - Portal Resmi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --dark-blue: #0f172a; 
            --accent-red: #be123c; /* Merah Elegan (Rose/Crimson) */
            --glass: rgba(15, 23, 42, 0.8);
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }

        /* Navbar Styling */
        .navbar {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .navbar.scrolled {
            background: var(--dark-blue) !important;
            padding: 12px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }
        .navbar.not-home {
            background: var(--dark-blue) !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 600;
            font-size: 0.95rem;
            margin: 0 12px;
            transition: 0.3s;
        }
        .nav-link:hover, .nav-link.active { color: #fff !important; }
        .navbar-brand { font-weight: 800; letter-spacing: -0.5px; color: #fff !important; }
        
        /* Utility Classes */
        .section-title::after {
            content: '';
            width: 50px;
            height: 4px;
            background: var(--accent-red);
            display: block;
            margin: 15px auto;
            border-radius: 10px;
        }
        .btn-red { background: var(--accent-red); color: white; border-radius: 50px; font-weight: 700; transition: 0.3s; }
        .btn-red:hover { background: #9f1239; color: white; transform: translateY(-3px); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top<?php echo !$is_home ? ' not-home' : ''; ?>" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/img/logo/<?php echo $profil['logo'] ?? 'default.png'; ?>" width="45" class="me-3">
            <div class="lh-1">
                <span class="fs-5 d-block">MERAK BATIN</span>
                <small class="fw-normal opacity-75" style="font-size: 0.7rem;">Kecamatan Natar</small>
            </div>
        </a>
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navRes">
            <i class="bi bi-list fs-2"></i>
        </button>
        <div class="collapse navbar-collapse" id="navRes">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Profil Desa</a>
                    <ul class="dropdown-menu shadow-lg border-0">
                        <li><a class="dropdown-item py-2" href="profil.php#visimisi">Visi & Misi</a></li>
                        <li><a class="dropdown-item py-2" href="profil.php#sejarah">Sejarah Desa</a></li>
                        <li><a class="dropdown-item py-2" href="profil.php#geografis">Letak Geografis</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="berita.php">Berita</a></li>
                <li class="nav-item"><a class="nav-link" href="layanan.php">Layanan</a></li>
                <li class="nav-item"><a class="nav-link" href="kontak.php">Kontak</a></li>
                <li class="nav-item"><a class="nav-link" href="galeri.php">Galeri</a></li>
            </ul>
        </div>
    </div>
</nav>