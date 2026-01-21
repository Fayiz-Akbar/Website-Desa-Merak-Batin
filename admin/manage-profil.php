<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = false;
$error = false;

// 1. Ambil data profil (ID=1)
$query = mysqli_query($conn, "SELECT * FROM profil WHERE id = 1");
$data = mysqli_fetch_assoc($query);

// 2. Logika Update
if (isset($_POST['update'])) {
    $nama_kades = mysqli_real_escape_string($conn, $_POST['nama_kepala_desa']);
    $sambutan = mysqli_real_escape_string($conn, $_POST['sambutan']);
    $sejarah = mysqli_real_escape_string($conn, $_POST['sejarah']);
    $visi = mysqli_real_escape_string($conn, $_POST['visi']);
    $misi = mysqli_real_escape_string($conn, $_POST['misi']);
    $populasi = (int)$_POST['populasi'];
    $luas = mysqli_real_escape_string($conn, $_POST['luas_wilayah']);

    // --- PROSES UPLOAD LOGO ---
    if ($_FILES['logo']['name']) {
        $ext_logo = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $new_logo = "logo_".uniqid().".".$ext_logo;
        $target_logo = "../assets/img/logo/".$new_logo;
        
        if (!file_exists('../assets/img/logo/')) mkdir('../assets/img/logo/', 0777, true);
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_logo)) {
            mysqli_query($conn, "UPDATE profil SET logo = '$new_logo' WHERE id = 1");
        }
    }

    // --- PROSES UPLOAD FOTO KADES ---
    if ($_FILES['foto_kades']['name']) {
        $ext_kades = pathinfo($_FILES['foto_kades']['name'], PATHINFO_EXTENSION);
        $new_kades = "kades_".uniqid().".".$ext_kades;
        $target_kades = "../assets/img/kades/".$new_kades;

        if (!file_exists('../assets/img/kades/')) mkdir('../assets/img/kades/', 0777, true);
        if (move_uploaded_file($_FILES['foto_kades']['tmp_name'], $target_kades)) {
            mysqli_query($conn, "UPDATE profil SET foto_kepala_desa = '$new_kades' WHERE id = 1");
        }
    }

    // UPDATE DATA TEKS
    $update = mysqli_query($conn, "UPDATE profil SET 
                nama_kepala_desa = '$nama_kades',
                sambutan = '$sambutan',
                sejarah = '$sejarah', visi = '$visi', misi = '$misi', 
                populasi = $populasi, luas_wilayah = '$luas' 
                WHERE id = 1");

    if ($update) {
        $sukses = true;
        $query = mysqli_query($conn, "SELECT * FROM profil WHERE id = 1");
        $data = mysqli_fetch_assoc($query);
    } else { $error = true; }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Profil - Desa Merak Batin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --sidebar-bg: #1a1d20; --primary-blue: #0d6efd; --card-shadow: 0 10px 30px rgba(0,0,0,0.04); }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; }

        .sidebar { width: 280px; background: var(--sidebar-bg); height: 100vh; position: fixed; display: flex; flex-direction: column; padding: 20px 15px; z-index: 1000; }
        .sidebar-brand { color: var(--primary-blue); font-weight: 700; font-size: 1.3rem; padding-bottom: 25px; border-bottom: 1px solid #2d3238; margin-bottom: 15px; }
        .sidebar-menu { flex-grow: 1; list-style: none; padding: 0; margin: 0; overflow-y: hidden; scrollbar-width: thin; }
        .nav-link { color: #adb5bd; padding: 10px 16px; border-radius: 11px; display: flex; align-items: center; transition: 0.3s; font-weight: 500; margin-bottom: 6px; text-decoration: none; font-size: 0.95rem; }
        .nav-link i { font-size: 1.15rem; margin-right: 11px; }
        .nav-link:hover, .nav-link.active { background: rgba(13, 110, 253, 0.15); color: var(--primary-blue); }
        .nav-link.active { background: var(--primary-blue); color: #fff !important; }
        .logout-section { margin-top: auto; padding-top: 20px; border-top: 1px solid #2d3238; }

        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }
        .form-card { background: #fff; border-radius: 20px; border: none; box-shadow: var(--card-shadow); padding: 40px; }
        .section-title { font-size: 1.1rem; font-weight: 700; color: #343a40; margin-bottom: 20px; display: flex; align-items: center; }
        .section-title i { color: var(--primary-blue); margin-right: 10px; }
        
        .form-label { font-weight: 600; font-size: 0.85rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #dee2e6; background-color: #fdfdfd; }
        .preview-img { width: 100px; height: 100px; object-fit: cover; border-radius: 15px; border: 2px solid #eee; }
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
        <div class="sidebar-brand"><i class="bi bi-geo-alt-fill text-primary"></i> Merak Batin</div>
        <ul class="sidebar-menu">
            <li><a href="index.php" class="nav-link"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="manage-profil.php" class="nav-link active"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php" class="nav-link"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php" class="nav-link"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="nav-link"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="nav-link"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-prosedur.php" class="nav-link"><i class="bi bi-card-checklist"></i> Layanan</a></li>
            <li><a href="manage-unduhan.php" class="nav-link"><i class="bi bi-download"></i> Unduhan</a></li>
            <li><a href="manage-galeri.php" class="nav-link"><i class="bi bi-images"></i> Galeri Desa</a></li>
            <li><a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>
        <div class="logout-section"><a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Keluar</a></div>
    </nav>

    <main class="main-content w-100">
        <div class="mb-4">
            <h3 class="fw-bold mb-1">Pengaturan Profil & Branding Desa</h3>
            <p class="text-muted">Kelola identitas visual, pimpinan, dan narasi visi-misi desa.</p>
        </div>

        <?php if ($sukses) : ?>
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><i class="bi bi-check-circle-fill me-2"></i> Profil berhasil diperbarui!</div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-card mb-4">
                <div class="section-title"><i class="bi bi-image"></i> Logo & Identitas Desa</div>
                <div class="row align-items-center g-4">
                    <div class="col-md-2 text-center">
                        <img src="../assets/img/logo/<?php echo $data['logo'] ?? 'default-logo.png'; ?>" class="preview-img mb-2">
                        <div class="small text-muted">Logo Saat Ini</div>
                    </div>
                    <div class="col-md-10">
                        <label class="form-label">Ganti Logo Desa (Format: PNG/JPG)</label>
                        <input type="file" name="logo" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-card mb-4">
                <div class="section-title"><i class="bi bi-person-badge"></i> Kepemimpinan Desa</div>
                <div class="row g-4 mb-4">
                    <div class="col-md-2 text-center">
                        <img src="../assets/img/kades/<?php echo $data['foto_kepala_desa'] ?? 'default-kades.png'; ?>" class="preview-img mb-2">
                        <div class="small text-muted">Foto Pimpinan</div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Nama Kepala Desa</label>
                        <input type="text" name="nama_kepala_desa" class="form-control" value="<?php echo $data['nama_kepala_desa']; ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Ganti Foto Kepala Desa</label>
                        <input type="file" name="foto_kades" class="form-control">
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Sambutan Kepala Desa</label>
                    <textarea name="sambutan" class="form-control" rows="4"><?php echo $data['sambutan']; ?></textarea>
                </div>
            </div>

            <div class="form-card mb-4">
                <div class="section-title"><i class="bi bi-bar-chart-fill"></i> Data Statistik Desa</div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Jumlah Penduduk (Jiwa)</label>
                        <input type="number" name="populasi" class="form-control" value="<?php echo $data['populasi']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Luas Wilayah</label>
                        <input type="text" name="luas_wilayah" class="form-control" value="<?php echo $data['luas_wilayah']; ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-card mb-4">
                <div class="section-title"><i class="bi bi-file-earmark-text-fill"></i> Visi, Misi & Sejarah</div>
                <div class="mb-4"><label class="form-label">Sejarah Desa</label><textarea name="sejarah" class="form-control" rows="5"><?php echo $data['sejarah']; ?></textarea></div>
                <div class="row g-4">
                    <div class="col-md-6"><label class="form-label">Visi Desa</label><textarea name="visi" class="form-control" rows="4"><?php echo $data['visi']; ?></textarea></div>
                    <div class="col-md-6"><label class="form-label">Misi Desa</label><textarea name="misi" class="form-control" rows="4"><?php echo $data['misi']; ?></textarea></div>
                </div>
                <div class="mt-5 pt-3 border-top text-end">
                    <button type="submit" name="update" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow">Simpan Seluruh Perubahan</button>
                </div>
            </div>
        </form>
    </main>
</div>

<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if(toggleBtn) toggleBtn.addEventListener('click', () => { sidebar.classList.toggle('active'); });
</script>
</body>
</html>