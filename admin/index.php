<?php
require 'auth_check.php';
require '../config/db.php';

// 1. Ambil Statistik Data Utama
$total_berita = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM berita"))['total'] ?? 0;
$total_perangkat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM perangkat_desa"))['total'] ?? 0;
$total_layanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM layanan"))['total'] ?? 0;
$total_potensi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM potensi_desa"))['total'] ?? 0;

// 2. Ambil data profil desa
$query_profil = mysqli_query($conn, "SELECT populasi, luas_wilayah FROM profil LIMIT 1");
$data_desa = mysqli_fetch_assoc($query_profil);
$populasi = $data_desa['populasi'] ?? 0;
$luas = $data_desa['luas_wilayah'] ?? '0 m²';

// 3. Ambil Ringkasan APB Desa (Financial Dashboard)
$q_total = mysqli_query($conn, "SELECT 
    SUM(CASE WHEN jenis = 'Pendapatan' THEN realisasi ELSE 0 END) as total_pendapatan,
    SUM(CASE WHEN jenis = 'Belanja' THEN realisasi ELSE 0 END) as total_belanja
    FROM apb_desa");
$res_total = mysqli_fetch_assoc($q_total);
$pendapatan = $res_total['total_pendapatan'] ?? 0;
$belanja = $res_total['total_belanja'] ?? 0;
$sisa_kas = $pendapatan - $belanja;

// 4. Ambil 5 Berita Terbaru untuk Feed
$berita_terbaru = mysqli_query($conn, "SELECT judul, tgl_posting FROM berita ORDER BY tgl_posting DESC LIMIT 5");
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

        /* Sidebar Styling Sesuai Template Anda */
        .sidebar { width: 280px; background: var(--sidebar-bg); height: 100vh; position: fixed; display: flex; flex-direction: column; padding: 20px 15px; transition: 0.3s; z-index: 1000; overflow: hidden; }
        .sidebar-brand { color: var(--active-blue); font-weight: 700; font-size: 1.3rem; padding-bottom: 25px; border-bottom: 1px solid #2d3238; margin-bottom: 15px; flex-shrink: 0; }
        .sidebar-menu { flex-grow: 1; list-style: none; padding: 0; margin: 0; overflow-y: hidden; scrollbar-width: thin; }
        .nav-link { color: #adb5bd; padding: 10px 16px; border-radius: 11px; display: flex; align-items: center; transition: 0.3s; font-weight: 500; margin-bottom: 6px; text-decoration: none; font-size: 0.95rem; }
        .nav-link i { font-size: 1.15rem; margin-right: 11px; }
        .nav-link:hover, .nav-link.active { background: rgba(13, 110, 253, 0.15); color: var(--active-blue); }
        .nav-link.active { background: var(--active-blue); color: #fff !important; }
        .logout-section { margin-top: auto; padding-top: 16px; border-top: 1px solid #2d3238; flex-shrink: 0; }
        .logout-link { color: #ea868f !important; }

        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }
        .stat-card { border: none; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: 0.3s; background: #fff; }
        .stat-card:hover { transform: translateY(-5px); }
        .icon-circle { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-bottom: 15px; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; justify-content: space-between; padding: 15px; background: #fff; border-bottom: 1px solid #dee2e6; position: sticky; top: 0; z-index: 999; }
        }
    </style>
</head>
<body>

<div class="mobile-header d-lg-none">
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
            <li><a href="manage-galeri.php" class="nav-link"><i class="bi bi-images"></i> Galeri Desa</a></li>
            <li><a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>
        <div class="logout-section"><a href="logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a></div>
    </nav>

    <main class="main-content w-100">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold">Ringkasan Desa </h3>
                <p class="text-muted">Pantau status digital Desa Merak Batin secara real-time.</p>
            </div>
            <div class="d-none d-md-block">
                <div class="badge bg-white text-dark p-2 px-3 shadow-sm rounded-pill border">
                    <i class="bi bi-calendar3 me-2 text-primary"></i> <?php echo date('d M Y'); ?>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="icon-circle bg-primary-subtle text-primary"><i class="bi bi-journal-text"></i></div>
                    <p class="text-muted small mb-1">Berita Aktif</p>
                    <h3 class="fw-bold mb-0"><?php echo $total_berita; ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="icon-circle bg-success-subtle text-success"><i class="bi bi-people"></i></div>
                    <p class="text-muted small mb-1">Perangkat Desa</p>
                    <h3 class="fw-bold mb-0"><?php echo $total_perangkat; ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="icon-circle bg-warning-subtle text-warning"><i class="bi bi-card-checklist"></i></div>
                    <p class="text-muted small mb-1">Layanan Publik</p>
                    <h3 class="fw-bold mb-0"><?php echo $total_layanan; ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="icon-circle bg-danger-subtle text-danger"><i class="bi bi-map"></i></div>
                    <p class="text-muted small mb-1">Potensi Desa</p>
                    <h3 class="fw-bold mb-0"><?php echo $total_potensi; ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card stat-card h-100">
                    <h5 class="fw-bold mb-4">Realisasi Anggaran 2026</h5>
                    <div class="row text-center g-3">
                        <div class="col-md-4 border-end">
                            <small class="text-muted d-block mb-1">Pendapatan</small>
                            <span class="fw-bold text-success">Rp <?php echo number_format($pendapatan, 0, ',', '.'); ?></span>
                        </div>
                        <div class="col-md-4 border-end">
                            <small class="text-muted d-block mb-1">Belanja</small>
                            <span class="fw-bold text-danger">Rp <?php echo number_format($belanja, 0, ',', '.'); ?></span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Sisa Kas</small>
                            <span class="fw-bold text-primary">Rp <?php echo number_format($sisa_kas, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                    <hr class="my-4">
                    <h6 class="fw-bold mb-3 small text-secondary text-uppercase">Berita Terbaru</h6>
                    <ul class="list-group list-group-flush small">
                        <?php while($news = mysqli_fetch_assoc($berita_terbaru)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="bi bi-dot text-primary me-1"></i> <?php echo htmlspecialchars($news['judul']); ?></span>
                            <span class="text-muted" style="font-size: 0.75rem;"><?php echo date('d/m', strtotime($news['tgl_posting'])); ?></span>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card stat-card bg-primary text-white h-100">
                    <h5 class="fw-bold mb-4">Informasi Desa</h5>
                    <div class="mb-3">
                        <small class="opacity-75">Jumlah Penduduk:</small>
                        <div class="fs-4 fw-bold"><?php echo number_format($populasi); ?> Jiwa</div>
                    </div>
                    <div class="mb-4">
                        <small class="opacity-75">Luas Wilayah:</small>
                        <div class="fs-4 fw-bold"><?php echo htmlspecialchars($luas); ?></div>
                    </div>
                    <hr class="opacity-25">
                    <div class="d-grid">
                        <a href="manage-profil.php" class="btn btn-light btn-sm rounded-pill fw-bold py-2">Edit Profil Desa</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    toggleBtn.addEventListener('click', () => sidebar.classList.toggle('active'));
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>