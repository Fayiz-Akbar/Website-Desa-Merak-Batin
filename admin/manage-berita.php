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
            overflow: hidden;
        }

        .sidebar-brand { 
            color: var(--active-blue); 
            font-weight: 700; 
            font-size: 1.4rem; 
            padding-bottom: 30px; 
            border-bottom: 1px solid #2d3238; 
            margin-bottom: 20px;
            flex-shrink: 0;
        }

        .sidebar-menu { 
            flex-grow: 1; 
            list-style: none; 
            padding: 0; 
            margin: 0;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }
        
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
            white-space: nowrap;
            text-decoration: none;
        }
        .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        .nav-link:hover { background: rgba(13, 110, 253, 0.15); color: var(--active-blue); }
        .nav-link.active { background: var(--active-blue); color: #fff !important; }

        /* Logout Section */
        .logout-section { 
            margin-top: auto; 
            padding-top: 20px; 
            border-top: 1px solid #2d3238;
            flex-shrink: 0;
            background: var(--sidebar-bg);
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

        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
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
            <li><a href="manage-berita.php" class="nav-link active"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
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
    
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    document.addEventListener('click', (e) => {
        if (window.innerWidth < 992 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    });
</script>
</body>
</html>