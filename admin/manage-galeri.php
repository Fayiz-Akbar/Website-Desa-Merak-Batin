<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = ""; $error = "";

// 1. LOGIKA UNGGAH FOTO GALERI
if (isset($_POST['tambah_foto'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    
    // Proses File Gambar
    $file_name = $_FILES['foto']['name'];
    $file_tmp = $_FILES['foto']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    
    if (in_array($file_ext, $allowed)) {
        $new_filename = "galeri_".uniqid().".".$file_ext;
        $target_path = '../assets/img/galeri/' . $new_filename;
        
        if (!file_exists('../assets/img/galeri/')) mkdir('../assets/img/galeri/', 0777, true);

        if (move_uploaded_file($file_tmp, $target_path)) {
            $query = "INSERT INTO galeri (judul, foto) VALUES ('$judul', '$new_filename')";
            if (mysqli_query($conn, $query)) {
                $sukses = "Foto kegiatan berhasil ditambahkan ke galeri!";
            }
        } else { $error = "Gagal mengunggah foto ke server."; }
    } else { $error = "Format file tidak didukung. Gunakan JPG/PNG/WebP."; }
}

// 2. LOGIKA HAPUS FOTO
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $res = mysqli_query($conn, "SELECT foto FROM galeri WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    
    // Hapus file fisik dari server agar tidak penuh
    if ($row['foto'] && file_exists("../assets/img/galeri/" . $row['foto'])) {
        unlink("../assets/img/galeri/" . $row['foto']);
    }
    
    mysqli_query($conn, "DELETE FROM galeri WHERE id = $id");
    header("Location: manage-galeri.php"); exit;
}

$data_galeri = mysqli_query($conn, "SELECT * FROM galeri ORDER BY tgl_upload DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri - Merak Batin</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/merakbatin.jpeg">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/merakbatin.jpeg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --sidebar-bg: #1a1d20; --primary-blue: #0d6efd; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }

        /* Sidebar Sesuai Identitas Website Anda */
        .sidebar { width: 280px; background: var(--sidebar-bg); height: 100vh; position: fixed; display: flex; flex-direction: column; padding: 20px 15px; z-index: 1000; }
        .sidebar-brand { color: var(--primary-blue); font-weight: 700; font-size: 1.3rem; padding-bottom: 25px; border-bottom: 1px solid #2d3238; margin-bottom: 15px; }
        .sidebar-menu { flex-grow: 1; list-style: none; padding: 0; margin: 0; overflow-y: hidden; scrollbar-width: thin; }
        .nav-link { color: #adb5bd; padding: 10px 16px; border-radius: 11px; display: flex; align-items: center; transition: 0.3s; font-weight: 500; margin-bottom: 6px; text-decoration: none; font-size: 0.95rem; }
        .nav-link i { font-size: 1.15rem; margin-right: 11px; }
        .nav-link:hover, .nav-link.active { background: rgba(13, 110, 253, 0.15); color: var(--primary-blue); }
        .nav-link.active { background: var(--primary-blue); color: #fff !important; }
        .logout-section { margin-top: auto; padding-top: 16px; border-top: 1px solid #2d3238; }

        .main-content { margin-left: 280px; padding: 40px; }
        .gallery-card { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s; background: #fff; }
        .gallery-card:hover { transform: translateY(-5px); }
        .img-container { height: 200px; overflow: hidden; position: relative; }
        .img-container img { width: 100%; height: 100%; object-fit: cover; }
        .delete-btn { position: absolute; top: 10px; right: 10px; opacity: 0; transition: 0.3s; }
        .gallery-card:hover .delete-btn { opacity: 1; }
        .mobile-header { display: none; background: #fff; padding: 15px 20px; border-bottom: 1px solid #dee2e6; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; justify-content: space-between; align-items: center; }
        }
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
            <li><a href="index.php" class="nav-link"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="manage-profil.php" class="nav-link"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php" class="nav-link"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php" class="nav-link"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="nav-link"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="nav-link"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-prosedur.php" class="nav-link"><i class="bi bi-card-checklist"></i> Layanan</a></li>
            <li><a href="manage-unduhan.php" class="nav-link"><i class="bi bi-download"></i> Unduhan</a></li>
                        <li><a href="manage-galeri.php" class="nav-link active"><i class="bi bi-images"></i> Galeri Desa</a></li>
            <li><a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a></li>

        </ul>
        <div class="logout-section"><a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Keluar</a></div>
    </nav>

    <main class="main-content w-100">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold">Galeri Dokumentasi Desa</h3>
                <p class="text-muted mb-0">Kelola foto kegiatan KKN dan dokumentasi pembangunan desa.</p>
            </div>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalUpload">
                <i class="bi bi-plus-lg me-2"></i> Unggah Foto
            </button>
        </div>

        <?php if($sukses): ?> <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><?php echo $sukses; ?></div> <?php endif; ?>
        <?php if($error): ?> <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4"><?php echo $error; ?></div> <?php endif; ?>

        <div class="row g-4">
            <?php while($row = mysqli_fetch_assoc($data_galeri)): ?>
            <div class="col-md-3">
                <div class="gallery-card">
                    <div class="img-container">
                        <img src="../assets/img/galeri/<?php echo $row['foto']; ?>" alt="Galeri">
                        <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm delete-btn" onclick="return confirm('Hapus foto ini?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                    <div class="p-3 text-center">
                        <small class="fw-bold text-dark d-block text-truncate"><?php echo htmlspecialchars($row['judul']); ?></small>
                        <small class="text-muted" style="font-size: 10px;"><?php echo date('d M Y', strtotime($row['tgl_upload'])); ?></small>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            
            <?php if(mysqli_num_rows($data_galeri) == 0): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-images fs-1 text-muted opacity-25"></i>
                    <p class="text-muted mt-3">Belum ada koleksi foto di galeri desa.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<div class="modal fade" id="modalUpload" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Tambah Koleksi Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">KETERANGAN / JUDUL FOTO</label>
                        <input type="text" name="judul" class="form-control" placeholder="Misal: Peresmian Jalan Desa" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">PILIH GAMBAR</label>
                        <input type="file" name="foto" class="form-control" required>
                    </div>
                    <small class="text-muted mt-2 d-block">Mendukung format JPG, PNG, atau WebP.</small>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" name="tambah_foto" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                        Unggah ke Galeri
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if(toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }
</script>
</body>
</html>