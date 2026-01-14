<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = "";
$error = "";

// --- LOGIKA TAMBAH KATEGORI ---
if (isset($_POST['tambah_kategori'])) {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    $query_kat = "INSERT INTO kategori_berita (nama_kategori) VALUES ('$nama_kategori')";
    if (mysqli_query($conn, $query_kat)) {
        $sukses = "Kategori baru berhasil ditambahkan!";
    }
}

// --- LOGIKA TAMBAH BERITA ---
if (isset($_POST['tambah'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $id_kategori = (int)$_POST['id_kategori'];
    $isi = mysqli_real_escape_string($conn, $_POST['isi_berita']);

    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $ekstensi_file = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

    $nama_gambar_baru = uniqid() . "." . $ekstensi_file;
    move_uploaded_file($tmp_name, '../assets/img/berita/' . $nama_gambar_baru);

    $query = "INSERT INTO berita (id_kategori, judul, isi_berita, gambar) VALUES ($id_kategori, '$judul', '$isi', '$nama_gambar_baru')";
    if (mysqli_query($conn, $query)) {
        $sukses = "Berita berhasil dipublikasikan!";
    }
}

// Ambil Kategori & Daftar Berita
$categories = mysqli_query($conn, "SELECT * FROM kategori_berita ORDER BY nama_kategori ASC");
$news_list = mysqli_query($conn, "SELECT berita.*, kategori_berita.nama_kategori 
                                  FROM berita 
                                  LEFT JOIN kategori_berita ON berita.id_kategori = kategori_berita.id 
                                  ORDER BY tgl_posting DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Merak Batin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* Sidebar Styling Sesuai Gambar */
        :root { --sidebar-dark: #111827; --active-blue: #0d6efd; }
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; margin: 0; }

        .sidebar {
            width: 280px;
            background-color: var(--sidebar-dark);
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            padding: 20px 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 0 25px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--active-blue);
            font-size: 1.5rem;
            font-weight: 800;
            border-bottom: 1px solid #1f2937;
        }

        .sidebar-menu {
            flex-grow: 1;
            padding: 20px 15px;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #9ca3af;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            margin-bottom: 5px;
            transition: 0.2s;
        }

        .nav-item i { font-size: 1.3rem; margin-right: 15px; }
        .nav-item:hover { color: #fff; background: rgba(255,255,255,0.05); }

        /* Item Aktif Sesuai Gambar */
        .nav-item.active {
            background-color: var(--active-blue);
            color: white !important;
        }

        .sidebar-footer {
            padding: 20px 15px;
            border-top: 1px solid #1f2937;
        }

        .logout-link { color: #f87171 !important; }

        /* Layout Main Content */
        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }
        
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; justify-content: space-between; padding: 15px; background: #fff; border-bottom: 1px solid #e5e7eb; }
        }

        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="mobile-header d-lg-none">
    <span class="fw-bold text-primary">Admin Merak Batin</span>
    <button class="btn btn-primary" id="sidebarToggle"><i class="bi bi-list"></i></button>
</div>

<div class="d-flex">
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-geo-alt-fill"></i>
            <span>Merak Batin</span>
        </div>
        
        <div class="sidebar-menu">
            <a href="index.php" class="nav-item"><i class="bi bi-grid-1x2"></i> Dashboard</a>
            <a href="manage-profil.php" class="nav-item"><i class="bi bi-house"></i> Profil Desa</a>
            <a href="manage-struktur.php" class="nav-item"><i class="bi bi-people"></i> Perangkat Desa</a>
            <a href="manage-berita.php" class="nav-item active"><i class="bi bi-journal-text"></i> Kelola Berita</a>
            <a href="manage-apbdesa.php" class="nav-item"><i class="bi bi-wallet2"></i> APB Desa</a>
            <a href="manage-potensi.php" class="nav-item"><i class="bi bi-map"></i> Potensi Desa</a>
            <a href="manage-prosedur.php" class="nav-item"><i class="bi bi-card-checklist"></i> Layanan</a>
            <a href="manage-unduhan.php" class="nav-item"><i class="bi bi-download"></i> Unduhan</a>
            <a href="manage-kontak.php" class="nav-item"><i class="bi bi-telephone"></i> Kontak</a>
        </div>

        <div class="sidebar-footer">
            <a href="logout.php" class="nav-item logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <main class="main-content w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Manajemen Berita</h3>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalKategori">
                <i class="bi bi-plus-circle me-2"></i> Kategori
            </button>
        </div>

        <?php if($sukses): ?> <div class="alert alert-success border-0 shadow-sm rounded-3"><?php echo $sukses; ?></div> <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card card-custom p-4">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">JUDUL</label>
                            <input type="text" name="judul" class="form-control" placeholder="Judul artikel" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">KATEGORI</label>
                            <select name="id_kategori" class="form-select" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nama_kategori']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">GAMBAR</label>
                            <input type="file" name="gambar" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-secondary">ISI BERITA</label>
                            <textarea name="isi_berita" class="form-control" rows="6" required></textarea>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary w-100 fw-bold py-2">Terbitkan</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card card-custom p-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="small text-secondary">
                                    <th>BERITA</th>
                                    <th>KATEGORI</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($news_list)): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?php echo $row['judul']; ?></div>
                                        <small class="text-muted"><?php echo date('d/m/Y', strtotime($row['tgl_posting'])); ?></small>
                                    </td>
                                    <td><span class="badge bg-light text-primary"><?php echo $row['nama_kategori']; ?></span></td>
                                    <td>
                                        <a href="?hapus=<?php echo $row['id']; ?>" class="text-danger" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header">
                <h5 class="fw-bold">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori Baru" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah_kategori" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    toggleBtn.addEventListener('click', () => { sidebar.classList.toggle('active'); });
</script>
</body>
</html>