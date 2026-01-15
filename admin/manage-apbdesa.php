<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = "";
$error = "";

// 1. Logika Tambah Data APB Desa
if (isset($_POST['tambah'])) {
    $tahun = $_POST['tahun'];
    $jenis = $_POST['jenis'];
    $rincian = mysqli_real_escape_string($conn, $_POST['rincian']);
    $anggaran = (int)$_POST['anggaran'];

    // Perbaikan: Sesuaikan nama kolom dengan struktur database
    $query = "INSERT INTO apb_desa (tahun, jenis, rincian, anggaran) 
              VALUES ('$tahun', '$jenis', '$rincian', $anggaran)";
    
    if (mysqli_query($conn, $query)) {
        $sukses = "Data anggaran berhasil ditambahkan ke sistem!";
    } else {
        $error = "Terjadi kesalahan sistem: " . mysqli_error($conn);
    }
}

// 2. Logika Hapus Data
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM apb_desa WHERE id = $id");
    header("Location: manage-apbdesa.php");
    exit;
}

$data_apb = mysqli_query($conn, "SELECT * FROM apb_desa ORDER BY tahun DESC, jenis ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola APB Desa - Merak Batin</title>
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
        
        .sidebar-menu::-webkit-scrollbar { width: 6px; }
        .sidebar-menu::-webkit-scrollbar-track { background: transparent; }
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
        }
        .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        .nav-link:hover, .nav-link.active { background: rgba(13, 110, 253, 0.15); color: var(--active-blue); }
        .nav-link.active { background: var(--active-blue); color: #fff; }

        .logout-section { 
            margin-top: auto; 
            padding-top: 20px; 
            border-top: 1px solid #2d3238;
            flex-shrink: 0;
            background: var(--sidebar-bg);
        }
        .logout-link { color: #ea868f !important; }
        .logout-link:hover { background: rgba(234, 134, 143, 0.1); }

        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }
        .mobile-header { display: none; background: #fff; padding: 15px 20px; border-bottom: 1px solid #dee2e6; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 999; }
        }
        
        .card-premium {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
            background: #fff;
        }

        .form-label { font-weight: 600; color: #4b5563; font-size: 0.85rem; }
        .form-control, .form-select { padding: 12px; border-radius: 10px; border: 1px solid #e5e7eb; }
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
            <li><a href="manage-apbdesa.php" class="nav-link active"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
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
        <div class="mb-5">
            <h3 class="fw-bold mb-1">Transparansi APB Desa</h3>
            <p class="text-muted">Kelola rincian anggaran untuk ditampilkan di infografis publik desa.</p>
        </div>

        <?php if($sukses): ?>
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> <?php echo $sukses; ?>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                <i class="bi bi-exclamation-circle-fill me-2"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card card-premium p-4">
                    <h5 class="fw-bold mb-4">Input Anggaran</h5>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">TAHUN ANGGARAN</label>
                            <input type="number" name="tahun" class="form-control" value="2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">JENIS</label>
                            <select name="jenis" class="form-select">
                                <option value="Pendapatan">Pendapatan</option>
                                <option value="Belanja">Belanja</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">RINCIAN / KETERANGAN</label>
                            <input type="text" name="rincian" class="form-control" placeholder="Contoh: Dana Desa (DD)" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">JUMLAH (Rp)</label>
                            <input type="number" name="anggaran" class="form-control" placeholder="0" required>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary w-100 fw-bold py-3 rounded-3 shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i> Simpan Anggaran
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-premium p-4">
                    <h5 class="fw-bold mb-4">Daftar Anggaran Terdata</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr class="small text-secondary fw-bold">
                                    <th>KETERANGAN</th>
                                    <th>JENIS</th>
                                    <th>NOMINAL</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($data_apb)): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['rincian']); ?></div>
                                        <div class="small text-muted">Tahun <?php echo $row['tahun']; ?></div>
                                    </td>
                                    <td>
                                        <?php if($row['jenis'] == 'Pendapatan'): ?>
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill border border-success-subtle">Pendapatan</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill border border-warning-subtle">Belanja</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-dark">Rp <?php echo number_format($row['anggaran'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0 rounded-pill" onclick="return confirm('Hapus data anggaran ini?')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if(mysqli_num_rows($data_apb) == 0): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">Belum ada data anggaran yang diinput.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });
</script>
</body>
</html>