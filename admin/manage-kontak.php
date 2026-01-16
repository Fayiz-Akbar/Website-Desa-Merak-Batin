<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = ""; $error = "";

// 1. LOGIKA UPDATE KONTAK
if (isset($_POST['update_kontak'])) {
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $wa = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $fb = mysqli_real_escape_string($conn, $_POST['facebook']);
    $ig = mysqli_real_escape_string($conn, $_POST['instagram']);
    $maps = mysqli_real_escape_string($conn, $_POST['maps_embed']);

    $query = "UPDATE kontak SET 
              alamat='$alamat', telepon='$telepon', email='$email', 
              whatsapp='$wa', facebook='$fb', instagram='$ig', 
              maps_embed='$maps' WHERE id=1";
    
    if (mysqli_query($conn, $query)) {
        $sukses = "Informasi kontak berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui data.";
    }
}

// 2. AMBIL DATA KONTAK SAAT INI
$res = mysqli_query($conn, "SELECT * FROM kontak WHERE id=1");
$data = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kontak - Merak Batin</title>
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
            padding: 20px 15px;
            transition: all 0.3s;
            z-index: 1000;
            overflow: hidden;
        }

        .sidebar-brand { 
            color: var(--active-blue); 
            font-weight: 700; 
            font-size: 1.3rem; 
            padding-bottom: 25px; 
            border-bottom: 1px solid #2d3238; 
            margin-bottom: 15px;
            flex-shrink: 0;
        }

        .sidebar-menu { 
            flex-grow: 1; 
            list-style: none; 
            padding: 0; 
            margin: 0;
            overflow-y: hidden;
            overflow-x: hidden;
        }

        .nav-link {
            color: #adb5bd;
            padding: 10px 16px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            font-weight: 500;
            margin-bottom: 6px;
            white-space: nowrap;
            font-size: 0.95rem;
        }
        .nav-link i { font-size: 1.15rem; margin-right: 11px; }
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
            <li><a href="index.php" class="nav-link"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="manage-profil.php" class="nav-link"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php" class="nav-link"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php" class="nav-link"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="nav-link"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="nav-link"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-prosedur.php" class="nav-link"><i class="bi bi-card-checklist"></i> Layanan</a></li>
            <li><a href="manage-unduhan.php" class="nav-link"><i class="bi bi-download"></i> Unduhan</a></li>
            <li><a href="manage-galeri.php" class="nav-link"><i class="bi bi-images"></i> Galeri Desa</a></li>
            <li><a href="manage-kontak.php" class="nav-link active"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>

        <div class="logout-section">
            <a href="logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <main class="main-content w-100">
        <div class="mb-4">
            <h3 class="fw-bold">Pengaturan Kontak Desa</h3>
            <p class="text-muted">Informasi ini akan ditampilkan pada bagian footer website publik.</p>
        </div>

        <?php if($sukses): ?> <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><?php echo $sukses; ?></div> <?php endif; ?>

        <form action="" method="POST">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card card-premium p-4">
                        <h5 class="fw-bold mb-4">Informasi Dasar</h5>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">ALAMAT KANTOR DESA</label>
                            <textarea name="alamat" class="form-control" rows="3" required><?php echo $data['alamat']; ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">NOMOR TELEPON</label>
                                <input type="text" name="telepon" class="form-control" value="<?php echo $data['telepon']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">EMAIL RESMI</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold">LINK GOOGLE MAPS (EMBED)</label>
                            <textarea name="maps_embed" class="form-control" rows="3" placeholder="Tempel kode <iframe> di sini"><?php echo $data['maps_embed']; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card card-premium p-4 h-100">
                        <h5 class="fw-bold mb-4">Media Sosial & Chat</h5>
                        <div class="mb-3">
                            <label class="form-label small fw-bold"><i class="bi bi-whatsapp text-success"></i> NOMOR WHATSAPP</label>
                            <input type="text" name="whatsapp" class="form-control" value="<?php echo $data['whatsapp']; ?>" placeholder="Contoh: 628123xxx">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold"><i class="bi bi-facebook text-primary"></i> LINK FACEBOOK</label>
                            <input type="url" name="facebook" class="form-control" value="<?php echo $data['facebook']; ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold"><i class="bi bi-instagram text-danger"></i> LINK INSTAGRAM</label>
                            <input type="url" name="instagram" class="form-control" value="<?php echo $data['instagram']; ?>">
                        </div>
                        <button type="submit" name="update_kontak" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                            Simpan Perubahan Kontak
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>