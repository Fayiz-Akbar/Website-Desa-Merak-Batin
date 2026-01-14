<?php
require 'auth_check.php';
require '../config/db.php';

// 1. Ambil statistik berita
$query_berita = mysqli_query($conn, "SELECT COUNT(*) as total FROM berita");
$total_berita = mysqli_fetch_assoc($query_berita)['total'] ?? 0;

// 2. Ambil data profil (Pastikan data ini yang diubah di DB)
$query_profil = mysqli_query($conn, "SELECT populasi, luas_wilayah FROM profil LIMIT 1");
$data_desa = mysqli_fetch_assoc($query_profil);

// Jika query gagal atau data kosong, beri nilai default agar tidak error
$populasi = isset($data_desa['populasi']) ? $data_desa['populasi'] : 0;
$luas = isset($data_desa['luas_wilayah']) ? $data_desa['luas_wilayah'] : '0 $m^2$';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Desa Merak Batin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --sidebar-bg: #1a1d20; --active-blue: #0d6efd; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }

        /* Sidebar Styling */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            padding: 25px 15px;
            transition: all 0.3s;
            z-index: 1000;
            overflow: hidden; /* Prevent sidebar from scrolling */
        }

        .sidebar-brand { 
            color: var(--active-blue); 
            font-weight: 700; 
            font-size: 1.4rem; 
            padding-bottom: 30px; 
            border-bottom: 1px solid #2d3238; 
            margin-bottom: 20px;
            flex-shrink: 0; /* Prevent brand from shrinking */
        }

        .sidebar-menu { 
            flex-grow: 1; 
            list-style: none; 
            padding: 0; 
            margin: 0;
            overflow-y: auto; /* Only menu scrolls */
            overflow-x: hidden;
            /* Custom scrollbar */
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }
        
        /* Webkit scrollbar styling */
        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .nav-link {
            color: #adb5bd;
            padding: 12px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            font-weight: 500;
            margin-bottom: 4px;
            white-space: nowrap; /* Prevent text wrapping */
        }
        .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        .nav-link:hover, .nav-link.active { background: rgba(13, 110, 253, 0.15); color: var(--active-blue); }
        .nav-link.active { background: var(--active-blue); color: #fff; }

        /* Logout Section (Fixed at bottom) */
        .logout-section { 
            margin-top: auto; 
            padding-top: 20px; 
            border-top: 1px solid #2d3238;
            flex-shrink: 0; /* Prevent logout from shrinking */
            background: var(--sidebar-bg); /* Ensure background consistency */
        }
        .logout-link { color: #ea868f !important; }
        .logout-link:hover { background: rgba(234, 134, 143, 0.1); }

        /* Layout Responsif */
        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }
        .mobile-header { display: none; background: #fff; padding: 15px 20px; border-bottom: 1px solid #dee2e6; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 999; }
        }

        .stat-card { border: none; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

<div class="mobile-header">
    <span class="fw-bold text-primary">Admin Merak Batin</span>
    <button class="btn btn-primary" id="sidebarToggle"><i class="bi bi-list"></i></button>
</div>

<div class="d-flex">
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand"><i class="bi bi-geo-alt-fill"></i> Merak Batin</div>
        
        <ul class="sidebar-menu">
            <li><a href="index.php" class="nav-link active"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="manage-profil.php" class="nav-link"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php" class="nav-link"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php" class="nav-link"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="nav-link"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="nav-link"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-prosedur.php" class="nav-link"><i class="bi bi-card-checklist"></i> Layanan</a></li>
            <li><a href="manage-unduhan.php" class="nav-link"><i class="bi bi-download"></i> Unduhan</a></li>
            <li><a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>

        <div class="logout-section">
            <a href="logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <main class="main-content w-100">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold">Selamat Datang, <?php echo $_SESSION['nama_admin']; ?>! </h3>
                <p class="text-muted">Kelola informasi publik Desa Merak Batin hari ini.</p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-white text-dark p-2 px-3 shadow-sm rounded-pill border">
                    <i class="bi bi-calendar3 me-2 text-primary"></i> <?php echo date('d M Y'); ?>
                </span>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card stat-card bg-primary text-white">
                    <p class="mb-1 opacity-75">Jumlah Penduduk</p>
                    <h2 class="fw-bold mb-0"><?php echo number_format($populasi); ?> Jiwa</h2>
                    <small class="mt-2 d-block opacity-50">Sesuai data infografis</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card bg-success text-white">
                    <p class="mb-1 opacity-75">Luas Wilayah</p>
                    <h2 class="fw-bold mb-0"><?php echo htmlspecialchars($luas); ?></h2>
                    <small class="mt-2 d-block opacity-50">Data koordinat spasial</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card bg-dark text-white">
                    <p class="mb-1 opacity-75">Berita Publikasi</p>
                    <h2 class="fw-bold mb-0"><?php echo $total_berita; ?> Artikel</h2>
                    <small class="mt-2 d-block opacity-50">Total berita yang aktif</small>
                </div>
            </div>
        </div>

        <div class="mt-5 p-4 bg-white rounded-4 shadow-sm border">
            <h5 class="fw-bold text-dark">Status Sistem</h5>
            <p class="text-muted mb-0 small">Sistem dashboard admin saat ini berjalan normal dan terhubung ke database **db_merakbatin**. Setiap perubahan pada menu **Profil Desa** akan langsung mengubah statistik di atas secara *real-time*.</p>
        </div>
    </main>
</div>

<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    // Menutup sidebar otomatis jika klik di luar pada layar kecil
    document.addEventListener('click', (e) => {
        if (window.innerWidth < 992 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>