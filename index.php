<?php
require 'config/db.php';

// 1. Data Profil
$profil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM profil LIMIT 1"));

// 2. Data Berita (3 Teratas)
$berita = mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b 
                               LEFT JOIN kategori_berita k ON b.id_kategori = k.id 
                               ORDER BY b.tgl_posting DESC LIMIT 3");

// 3. Data Layanan (4 Teratas)
$layanan = mysqli_query($conn, "SELECT * FROM layanan LIMIT 4");

// 4. Data Potensi (3 Teratas)
$potensi = mysqli_query($conn, "SELECT p.*, k.nama_kategori FROM potensi_desa p 
                                LEFT JOIN kategori_potensi k ON p.id_kategori = k.id LIMIT 3");

// 5. Ringkasan APB Desa
$q_apb = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
    SUM(CASE WHEN jenis = 'Pendapatan' THEN realisasi ELSE 0 END) as pendapatan,
    SUM(CASE WHEN jenis = 'Belanja' THEN realisasi ELSE 0 END) as belanja FROM apb_desa"));
$sisa_kas = ($q_apb['pendapatan'] ?? 0) - ($q_apb['belanja'] ?? 0);

// 6. Kontak
$kontak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kontak WHERE id=1"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Merak Batin - Inovasi & Transparansi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --p-blue: #0061ff; --s-blue: #60efff; --dark: #0f172a; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; overflow-x: hidden; }
        
        /* Navbar Glassmorphism */
        .navbar { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(0,0,0,0.05); }
        
        /* Hero Styling */
        .hero { 
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.7)), url('assets/img/village-bg.jpg');
            background-size: cover; background-position: center; min-height: 90vh; display: flex; align-items: center; color: white;
            clip-path: ellipse(150% 100% at 50% 0%);
        }

        /* Card Effects */
        .card-hover { border: none; border-radius: 24px; transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .card-hover:hover { transform: translateY(-12px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        
        .icon-shape { width: 64px; height: 64px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 20px; }
        
        .section-padding { padding: 100px 0; }
        .btn-premium { padding: 14px 32px; border-radius: 50px; font-weight: 700; transition: 0.3s; }
        
        .footer { background: var(--dark); color: #94a3b8; padding: 80px 0 30px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top py-3">
    <div class="container">
        <a class="navbar-brand fw-extrabold d-flex align-items-center gap-2" href="#">
            <div class="bg-primary text-white rounded-3 px-2"><i class="bi bi-geo-alt-fill"></i></div>
            <span class="text-dark">MERAK BATIN</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto gap-3">
                <li class="nav-item"><a class="nav-link fw-semibold" href="#berita">Warta</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="#layanan">Layanan</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="#potensi">Potensi</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="#apb">Transparansi</a></li>
                <li class="nav-item"><a class="btn btn-dark btn-premium text-white px-4 ms-lg-3" href="admin/login.php">Dashboard</a></li>
            </ul>
        </div>
    </div>
</nav>

<header class="hero">
    <div class="container text-center text-lg-start">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3 fw-bold">Official Village Website</span>
                <h1 class="display-2 fw-bold mb-4">Membangun Digitalisasi <span class="text-primary">Desa Merak Batin</span></h1>
                <p class="lead opacity-75 mb-5 fs-4">Pusat informasi terpadu dan pelayanan mandiri bagi warga Kecamatan Natar, Lampung Selatan.</p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="#layanan" class="btn btn-primary btn-premium shadow-lg">Mulai Layanan</a>
                    <a href="#apb" class="btn btn-outline-light btn-premium">Pantau Anggaran</a>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container" style="margin-top: -60px; position: relative; z-index: 10;">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-hover p-4 text-center">
                <h2 class="fw-bold text-primary mb-0"><?php echo number_format($profil['populasi'] ?? 0); ?></h2>
                <small class="text-muted text-uppercase fw-bold ls-1">Total Penduduk</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-hover p-4 text-center">
                <h2 class="fw-bold text-primary mb-0"><?php echo $profil['luas_wilayah'] ?? '0 km²'; ?></h2>
                <small class="text-muted text-uppercase fw-bold ls-1">Wilayah Desa</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-hover p-4 text-center">
                <h2 class="fw-bold text-primary mb-0">2026</h2>
                <small class="text-muted text-uppercase fw-bold ls-1">Tahun Berjalan</small>
            </div>
        </div>
    </div>
</div>

<section id="layanan" class="section-padding">
    <div class="container text-center mb-5">
        <h6 class="text-primary fw-bold text-uppercase">Pelayanan Publik</h6>
        <h2 class="fw-bold fs-1">Urus Dokumen Lebih Mudah</h2>
    </div>
    <div class="container">
        <div class="row g-4">
            <?php while($l = mysqli_fetch_assoc($layanan)): ?>
            <div class="col-md-3">
                <div class="card card-hover h-100 p-4">
                    <div class="icon-shape bg-primary-subtle text-primary"><i class="bi bi-file-earmark-text"></i></div>
                    <h5 class="fw-bold"><?php echo $l['nama_layanan']; ?></h5>
                    <p class="small text-muted mb-4">Estimasi: <?php echo $l['estimasi_waktu']; ?></p>
                    <a href="#" class="mt-auto text-decoration-none fw-bold">Lihat Syarat <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<section id="apb" class="section-padding bg-light">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h6 class="text-primary fw-bold text-uppercase">Transparansi Publik</h6>
                <h2 class="fw-bold fs-1 mb-4">Dana Desa Untuk Rakyat</h2>
                <p class="text-muted mb-5">Kami berkomitmen mengelola APB Desa secara terbuka. Warga dapat memantau penggunaan anggaran secara real-time melalui sistem ini.</p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-white rounded-4 shadow-sm border-start border-success border-4">
                            <small class="text-muted d-block">Pendapatan Cair</small>
                            <span class="fw-bold text-success">Rp <?php echo number_format($q_apb['pendapatan'] ?? 0, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-white rounded-4 shadow-sm border-start border-danger border-4">
                            <small class="text-muted d-block">Belanja Terpakai</small>
                            <span class="fw-bold text-danger">Rp <?php echo number_format($q_apb['belanja'] ?? 0, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-hover bg-primary text-white p-5 shadow-lg border-0">
                    <div class="d-flex justify-content-between mb-4">
                        <h4 class="fw-bold">Kas Desa Saat Ini</h4>
                        <i class="bi bi-wallet2 fs-2 opacity-50"></i>
                    </div>
                    <h2 class="display-5 fw-bold mb-4">Rp <?php echo number_format($sisa_kas, 0, ',', '.'); ?></h2>
                    <p class="mb-0 opacity-75 small">*Update otomatis dari modul anggaran admin.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <h4 class="text-white fw-bold mb-4">Merak Batin</h4>
                <p class="lh-lg mb-4"><?php echo $kontak['alamat'] ?? 'Lampung Selatan, Natar.'; ?></p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
            <div class="col-lg-2 ms-auto">
                <h6 class="text-white fw-bold mb-4">Menu</h6>
                <ul class="list-unstyled d-grid gap-2">
                    <li><a href="#" class="text-decoration-none text-muted">Beranda</a></li>
                    <li><a href="#layanan" class="text-decoration-none text-muted">Layanan</a></li>
                    <li><a href="#apb" class="text-decoration-none text-muted">Keuangan</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="text-white fw-bold mb-4">Hubungi Kami</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-shape bg-secondary-subtle text-dark" style="width: 40px; height: 40px;"><i class="bi bi-whatsapp"></i></div>
                    <span><?php echo $kontak['whatsapp'] ?? '-'; ?></span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-shape bg-secondary-subtle text-dark" style="width: 40px; height: 40px;"><i class="bi bi-envelope"></i></div>
                    <span><?php echo $kontak['email'] ?? '-'; ?></span>
                </div>
            </div>
        </div>
        <hr class="my-5 opacity-10">
        <p class="text-center small mb-0">&copy; 2026 Proyek KKN Universitas Lampung. Informatics Developed.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>