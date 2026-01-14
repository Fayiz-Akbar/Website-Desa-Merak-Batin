<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = "";
$error = "";

// --- LOGIKA TAMBAH DATA ---
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $urutan = (int)$_POST['urutan'];

    $foto = $_FILES['foto']['name'];
    $tmp_name = $_FILES['foto']['tmp_name'];
    $ekstensi_valid = ['jpg', 'jpeg', 'png'];
    $ekstensi_file = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

    if (!in_array($ekstensi_file, $ekstensi_valid)) {
        $error = "Format foto harus JPG, JPEG, atau PNG!";
    } else {
        $nama_foto_baru = uniqid() . "." . $ekstensi_file;
        // Pastikan folder ini sudah ada: assets/img/perangkat/
        move_uploaded_file($tmp_name, '../assets/img/perangkat/' . $nama_foto_baru);

        $query = "INSERT INTO perangkat_desa (nama, jabatan, foto, urutan) VALUES ('$nama', '$jabatan', '$nama_foto_baru', $urutan)";
        if (mysqli_query($conn, $query)) {
            $sukses = "Perangkat desa berhasil ditambahkan!";
        }
    }
}

// --- LOGIKA HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $data_lama = mysqli_query($conn, "SELECT foto FROM perangkat_desa WHERE id = $id");
    $row = mysqli_fetch_assoc($data_lama);
    
    if ($row && file_exists('../assets/img/perangkat/' . $row['foto'])) {
        unlink('../assets/img/perangkat/' . $row['foto']);
    }

    mysqli_query($conn, "DELETE FROM perangkat_desa WHERE id = $id");
    header("Location: manage-struktur.php");
    exit;
}

// Ambil semua data perangkat
$perangkat = mysqli_query($conn, "SELECT * FROM perangkat_desa ORDER BY urutan ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Perangkat - Desa Merak Batin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --sidebar-bg: #1a1d20; --active-blue: #0d6efd; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; }

        /* Sidebar Styling (Sesuai Dashboard) */
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
        }

        .sidebar-brand { color: var(--active-blue); font-weight: 700; font-size: 1.4rem; padding-bottom: 30px; border-bottom: 1px solid #2d3238; margin-bottom: 20px; }

        .sidebar-menu { flex-grow: 1; list-style: none; padding: 0; margin: 0; overflow-y: auto; scrollbar-width: thin; }
        .nav-link {
            color: #adb5bd;
            padding: 12px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            font-weight: 500;
            margin-bottom: 4px;
            text-decoration: none;
        }
        .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        .nav-link:hover, .nav-link.active { background: rgba(13, 110, 253, 0.15); color: var(--active-blue); }
        .nav-link.active { background: var(--active-blue); color: #fff; }

        .logout-section { margin-top: auto; padding-top: 20px; border-top: 1px solid #2d3238; }
        .logout-link { color: #ea868f !important; }

        /* Main Content Styling */
        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }
        .card-custom { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .img-preview { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 2px solid #eee; }

        /* Responsivitas */
        .mobile-header { display: none; background: #fff; padding: 15px 20px; border-bottom: 1px solid #dee2e6; position: sticky; top: 0; z-index: 999; }
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
            <li><a href="manage-struktur.php" class="nav-link active"><i class="bi bi-people"></i> Perangkat Desa</a></li>
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
        <div class="mb-4">
            <h3 class="fw-bold">Manajemen Perangkat Desa</h3>
            <p class="text-muted">Kelola struktur organisasi dan pengurus Desa Merak Batin.</p>
        </div>

        <?php if($sukses): ?> <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><?php echo $sukses; ?></div> <?php endif; ?>
        <?php if($error): ?> <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4"><?php echo $error; ?></div> <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-4">Tambah Data</h5>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">NAMA LENGKAP</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama tanpa gelar" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">JABATAN</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Sekretaris Desa" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">FOTO PROFIL</label>
                            <input type="file" name="foto" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">URUTAN TAMPIL</label>
                            <input type="number" name="urutan" class="form-control" value="0">
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary w-100 fw-bold py-2 rounded-3 shadow-sm">Simpan Perangkat</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-4">Struktur Organisasi Saat Ini</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Foto</th>
                                    <th>Nama & Jabatan</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; while($row = mysqli_fetch_assoc($perangkat)): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><img src="../assets/img/perangkat/<?php echo $row['foto']; ?>" class="img-preview"></td>
                                    <td>
                                        <div class="fw-bold"><?php echo $row['nama']; ?></div>
                                        <div class="small text-muted"><?php echo $row['jabatan']; ?></div>
                                    </td>
                                    <td>
                                        <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0 rounded-pill" onclick="return confirm('Hapus data perangkat ini?')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
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

<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    toggleBtn.addEventListener('click', () => { sidebar.classList.toggle('active'); });
</script>

</body>
</html>