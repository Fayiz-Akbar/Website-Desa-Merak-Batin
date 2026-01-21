<?php
include 'config/db.php';
$profil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM profil WHERE id=1"));

// Cek halaman aktif untuk logika UI
$current_page = basename($_SERVER['PHP_SELF']);
$is_home = ($current_page == 'index.php' || $current_page == '');
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
            --accent-red: #be123c;
            --glass: rgba(15, 23, 42, 0.8);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; 
            color: #1e293b;
            padding-top: 0 !important;
            margin-top: 0 !important;
        }

        /* Navbar Styling */
        .navbar {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            z-index: 1050;
            margin: 0;
        }

        /* Kondisi Navigasi di Halaman Selain Home */
        .navbar.not-home, .navbar.scrolled {
            background: var(--dark-blue) !important;
            padding: 0.75rem 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }

        .nav-link {
            color: rgba(255,255,255,0.7) !important;
            font-weight: 600;
            font-size: 0.95rem;
            margin: 0 10px;
            transition: 0.3s;
            position: relative;
        }

        /* Warna Merah Crimson untuk Link Aktif */
        .nav-link:hover, .nav-link.active { 
            color: #fff !important; 
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 10px;
            right: 10px;
            height: 2px;
            background: var(--accent-red);
            border-radius: 2px;
        }

        .navbar-brand { 
            font-weight: 800; 
            letter-spacing: -0.5px; 
            color: #fff !important; 
        }

        /* Button Red Style */
        .btn-red {
            background: var(--accent-red);
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(190, 18, 60, 0.3);
        }

        .btn-red:hover {
            background: #9f102f;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(190, 18, 60, 0.4);
        }

        /* Dropdown Menu */
        .dropdown-menu {
            background: white;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            padding: 0.5rem 0;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.3s;
            color: #1e293b;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
            color: var(--accent-red);
            padding-left: 2rem;
        }
        
        .dropdown-toggle::after {
            display: inline-block !important;
            margin-left: 0.3em;
            vertical-align: middle;
        }
        
        .nav-link:focus,
        .nav-link:active,
        .dropdown-toggle:focus,
        .dropdown-toggle:active {
            outline: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        
        .dropdown-menu.show {
            display: block;
        }

        /* Mobile Navbar */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: rgba(15, 23, 42, 0.98);
                margin-top: 1rem;
                padding: 1rem;
                border-radius: 0.5rem;
            }
            
            .nav-link.active::after {
                display: none;
            }
            
            .nav-link.active {
                background: rgba(190, 18, 60, 0.2);
                border-radius: 0.5rem;
                padding: 0.5rem 1rem;
            }
        }

        /* Utility */
        .hero-profil {
            padding: 160px 0 100px;
            background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.9)), 
                        url('assets/img/hero-desa.jpg');
            background-size: cover; 
            background-position: center;
            color: white; 
            text-align: center;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top <?php echo !$is_home ? 'not-home' : ''; ?>" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <?php 
            $logo_path = 'assets/img/logo/' . ($profil['logo'] ?? 'default.png');
            if (!empty($profil['logo']) && file_exists($logo_path)): 
            ?>
                <img src="<?php echo $logo_path; ?>" width="40" class="me-2">
            <?php endif; ?>
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
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($current_page == 'profil.php') ? 'active' : ''; ?>" href="#" data-bs-toggle="dropdown">Profil Desa</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profil.php#visimisi">Visi & Misi</a></li>
                        <li><a class="dropdown-item" href="profil.php#sejarah">Sejarah Desa</a></li>
                        <li><a class="dropdown-item" href="profil.php#geografis">Letak Geografis</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'berita.php') ? 'active' : ''; ?>" href="berita.php">Berita</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'struktur-organisasi.php') ? 'active' : ''; ?>" href="struktur-organisasi.php">Struktur Organisasi</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'layanan.php') ? 'active' : ''; ?>" href="layanan.php">Layanan</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'unduhan.php') ? 'active' : ''; ?>" href="unduhan.php">Unduhan</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'kontak.php') ? 'active' : ''; ?>" href="kontak.php">Kontak</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'galeri.php') ? 'active' : ''; ?>" href="galeri.php">Galeri</a></li>
            </ul>
        </div>
    </div>
</nav>