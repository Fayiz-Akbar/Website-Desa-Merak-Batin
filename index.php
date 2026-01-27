<?php include 'includes/header.php'; ?>

<header class="hero" style="position: relative; min-height: 100vh; display: flex; align-items: center; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.9)), url('assets/img/hero-desa.jpeg'); background-size: cover; background-position: center; z-index: -1;"></div>
    
    <div class="container py-5 text-center text-white">
        <div class="animate__animated animate__fadeInUp">
            <span class="badge px-3 py-2 mb-3" style="background: var(--accent-red);">Selamat Datang di Portal Resmi</span>
            <h1 class="display-4 display-md-2 fw-bolder mb-3" style="letter-spacing: -1px;">DESA MERAK BATIN</h1>
            <p class="lead opacity-75 mb-5 mx-auto" style="max-width: 700px; font-size: 1.1rem;">Wadah Informasi & Pelayanan Digital Terpadu untuk Masyarakat Desa Merak Batin yang Mandiri dan Transparan.</p>
            <div class="d-flex flex-column flex-md-row justify-content-center gap-3 px-4">
                <a href="layanan.php" class="btn btn-red px-5 py-3 shadow">Info Layanan</a>
                <a href="#profil" class="btn btn-outline-light px-5 py-3 rounded-pill fw-bold">Jelajah Profil</a>
            </div>
        </div>
    </div>
</header>

<section id="profil" class="py-5">
    <div class="container py-md-5">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-5 text-center">
                <div class="position-relative d-inline-block">
                    <img src="assets/img/kades/<?php echo htmlspecialchars($profil['foto_kepala_desa'] ?? 'default.jpg', ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded-4 shadow-lg border-5 border-white" style="width: 100%; max-width: 400px; height: auto; object-fit: cover;">
                    <div class="position-absolute bottom-0 start-50 translate-middle-x bg-white p-3 rounded-4 shadow-sm" style="width: 85%;">
                        <h6 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($profil['nama_kepala_desa'] ?? 'Nama Kepala Desa', ENT_QUOTES, 'UTF-8'); ?></h6>
                        <small class="text-muted">Kepala Desa Merak Batin</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 text-center text-lg-start mt-5 mt-lg-0 px-4">
                <h6 class="text-uppercase fw-bold mb-3" style="color: var(--accent-red); letter-spacing: 2px;">Kata Sambutan</h6>
                <h2 class="fw-bold mb-4">Membangun Bersama Masyarakat</h2>
                <div class="fs-5 text-muted lh-lg mb-4" style="font-style: italic; border-left: 4px solid var(--accent-red); padding-left: 20px;">
                    "<?php echo nl2br(htmlspecialchars($profil['sambutan'] ?? 'Selamat datang...', ENT_QUOTES, 'UTF-8')); ?>"
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold section-title">Struktur Perangkat Desa</h2>
        </div>
        <div class="row g-3 g-md-4">
            <?php 
            $perangkat = mysqli_query($conn, "SELECT * FROM perangkat_desa ORDER BY urutan ASC LIMIT 8");
            while($p = mysqli_fetch_assoc($perangkat)): ?>
            <div class="col-6 col-md-3">
                <div class="card border-0 text-center p-3 rounded-4 shadow-sm h-100" style="transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <img src="assets/img/perangkat/<?php echo htmlspecialchars($p['foto'], ENT_QUOTES, 'UTF-8'); ?>" class="rounded-circle mx-auto mb-3" width="80" height="80" style="object-fit: cover; border: 3px solid var(--dark-blue);">
                    <h6 class="fw-bold mb-1 small"><?php echo htmlspecialchars($p['nama'], ENT_QUOTES, 'UTF-8'); ?></h6>
                    <small class="badge bg-light text-primary" style="font-size: 0.7rem;"><?php echo htmlspecialchars($p['jabatan'], ENT_QUOTES, 'UTF-8'); ?></small>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center text-md-end mt-4">
            <a href="struktur.php" class="btn btn-link text-decoration-none fw-bold" style="color: var(--dark-blue);">Lihat Semua Perangkat <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</section>

<section class="py-5 text-white" style="background: var(--dark-blue); border-radius: 40px 40px 0 0;">
    <div class="container py-4">
        <div class="row g-4 text-center mb-5">
            <div class="col-md-6 border-md-end border-white border-opacity-25">
                <h1 class="display-4 fw-bold mb-0"><?php echo number_format($profil['populasi'] ?? 0); ?></h1>
                <p class="opacity-75 text-uppercase fw-bold small">Total Penduduk</p>
            </div>
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-0"><?php echo htmlspecialchars($profil['luas_wilayah'] ?? '0', ENT_QUOTES, 'UTF-8'); ?></h1>
                <p class="opacity-75 text-uppercase fw-bold small">Luas Wilayah</p>
            </div>
        </div>
        
        <div class="row g-3 justify-content-center text-center px-2">
            <?php 
            $q_apb = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(CASE WHEN jenis='Pendapatan' THEN realisasi ELSE 0 END) as p, SUM(CASE WHEN jenis='Belanja' THEN realisasi ELSE 0 END) as b FROM apb_desa"));
            ?>
            <div class="col-sm-6 col-md-5">
                <div class="p-3 rounded-4 h-100" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                    <h6 class="opacity-75 small text-uppercase">Realisasi Pendapatan</h6>
                    <h4 class="fw-bold text-success mb-0">Rp <?php echo number_format($q_apb['p'] ?? 0, 0, ',', '.'); ?></h4>
                </div>
            </div>
            <div class="col-sm-6 col-md-5">
                <div class="p-3 rounded-4 h-100" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                    <h6 class="opacity-75 small text-uppercase">Realisasi Belanja</h6>
                    <h4 class="fw-bold mb-0" style="color: var(--accent-red);">Rp <?php echo number_format($q_apb['b'] ?? 0, 0, ',', '.'); ?></h4>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 text-center text-md-start">
            <div>
                <h2 class="fw-bold">Berita Merak Batin</h2>
                <p class="text-muted">Ikuti kabar dan kegiatan terkini warga desa.</p>
            </div>
            <a href="berita.php" class="btn btn-outline-dark rounded-pill px-4 fw-bold mt-3 mt-md-0">Semua Berita</a>
        </div>
        <div class="row g-4">
            <?php 
            $berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY tgl_posting DESC LIMIT 3");
            while($b = mysqli_fetch_assoc($berita)): ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <img src="assets/img/berita/<?php echo htmlspecialchars($b['gambar'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-4">
                        <small class="text-muted d-block mb-2"><?php echo date('d M Y', strtotime($b['tgl_posting'])); ?></small>
                        <h5 class="fw-bold lh-base mb-3" style="font-size: 1.1rem;"><?php echo htmlspecialchars($b['judul'], ENT_QUOTES, 'UTF-8'); ?></h5>
                        <a href="berita_detail.php?id=<?php echo (int)$b['id']; ?>" class="text-decoration-none fw-bold small" style="color: var(--accent-red);">Selengkapnya <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 text-center text-md-start">
            <div>
                <h2 class="fw-bold">Galeri Dokumentasi</h2>
                <p class="text-muted">Dokumentasi kegiatan dan pembangunan desa.</p>
            </div>
            <a href="galeri.php" class="btn btn-red rounded-pill px-4 mt-3 mt-md-0">Lihat Semua Galeri</a>
        </div>
        <div class="row g-2 g-md-3">
            <?php 
            $galeri = mysqli_query($conn, "SELECT * FROM galeri ORDER BY tgl_upload DESC LIMIT 6");
            while($g = mysqli_fetch_assoc($galeri)): ?>
            <div class="col-6 col-md-4">
                <div class="rounded-4 overflow-hidden shadow-sm" style="height: 180px; height: md-250px;">
                    <img src="assets/img/galeri/<?php echo htmlspecialchars($g['foto'], ENT_QUOTES, 'UTF-8'); ?>" class="w-100 h-100" style="object-fit: cover; transition: 0.5s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>