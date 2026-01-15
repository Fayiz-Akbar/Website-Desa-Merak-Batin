<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = "";
$error = "";

// 1. Logika Tambah Data (Wajib menyertakan realisasi agar tidak error)
if (isset($_POST['tambah'])) {
    $tahun = $_POST['tahun'];
    $jenis = $_POST['jenis'];
    $rincian = mysqli_real_escape_string($conn, $_POST['rincian']);
    $anggaran = (int)$_POST['anggaran'];
    $realisasi = (int)$_POST['realisasi'];

    $query = "INSERT INTO apb_desa (tahun, jenis, rincian, anggaran, realisasi) 
              VALUES ('$tahun', '$jenis', '$rincian', $anggaran, $realisasi)";
    
    if (mysqli_query($conn, $query)) {
        $sukses = "Data transparansi berhasil disimpan!";
    } else {
        $error = "Gagal menyimpan data: " . mysqli_error($conn);
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
    <title>Manajemen APB Desa - Merak Batin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --sidebar-bg: #111827; --active-blue: #0d6efd; }
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; margin: 0; }

        /* SIDEBAR IDENTIK GAMBAR */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            padding: 25px 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar-brand { 
            color: var(--active-blue); 
            font-weight: 800; 
            font-size: 1.5rem; 
            padding: 0 25px 25px; 
            border-bottom: 1px solid #1f2937; 
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-menu { flex-grow: 1; list-style: none; padding: 0 15px; overflow-y: auto; }
        
        .nav-link {
            color: #9ca3af;
            padding: 12px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 5px;
        }
        .nav-link i { font-size: 1.3rem; margin-right: 15px; }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .nav-link.active { background: var(--active-blue); color: #fff !important; }

        /* LOGOUT TETAP DI BAWAH */
        .logout-section { margin-top: auto; padding: 20px 15px; border-top: 1px solid #1f2937; }
        .logout-link { color: #f87171 !important; }

        /* MAIN CONTENT */
        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }
        .card-premium { border: none; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); background: #fff; }
        
        .progress { height: 6px; border-radius: 10px; background-color: #f1f5f9; }
        .form-label { font-weight: 600; color: #4b5563; font-size: 0.85rem; }
        .form-control, .form-select { padding: 12px; border-radius: 10px; border: 1px solid #e5e7eb; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; justify-content: space-between; padding: 15px; background: #fff; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; z-index: 999; }
        }
    </style>
</head>
<body>

<div class="mobile-header d-lg-none">
    <span class="fw-bold text-primary"><i class="bi bi-geo-alt-fill"></i> Merak Batin</span>
    <button class="btn btn-primary" id="sidebarToggle"><i class="bi bi-list"></i></button>
</div>

<div class="d-flex">
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand"><i class="bi bi-geo-alt-fill"></i><span>Merak Batin</span></div>
        <div class="sidebar-menu">
            <a href="index.php" class="nav-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
            <a href="manage-profil.php" class="nav-link"><i class="bi bi-house"></i> Profil Desa</a>
            <a href="manage-struktur.php" class="nav-link"><i class="bi bi-people"></i> Perangkat Desa</a>
            <a href="manage-berita.php" class="nav-link"><i class="bi bi-journal-text"></i> Kelola Berita</a>
            <a href="manage-apbdesa.php" class="nav-link active"><i class="bi bi-wallet2"></i> APB Desa</a>
            <a href="manage-potensi.php" class="nav-link"><i class="bi bi-map"></i> Potensi Desa</a>
            <a href="manage-prosedur.php" class="nav-link"><i class="bi bi-card-checklist"></i> Layanan</a>
            <a href="manage-unduhan.php" class="nav-link"><i class="bi bi-download"></i> Unduhan</a>
            <a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a>
        </div>
        <div class="logout-section">
            <a href="logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <main class="main-content w-100">
        <div class="mb-5">
            <h3 class="fw-bold mb-1">Transparansi Anggaran (APB Desa)</h3>
            <p class="text-muted">Kelola rencana anggaran vs realisasi fisik di lapangan secara transparan.</p>
        </div>

        <?php if($sukses): ?> <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><?php echo $sukses; ?></div> <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card card-premium p-4">
                    <h5 class="fw-bold mb-4">Input Anggaran</h5>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">TAHUN</label>
                            <input type="number" name="tahun" class="form-control" value="2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">JENIS</label>
                            <select name="jenis" class="form-select" required>
                                <option value="Pendapatan">Pendapatan (Uang Masuk)</option>
                                <option value="Belanja">Belanja (Uang Keluar)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">RINCIAN KEGIATAN</label>
                            <input type="text" name="rincian" class="form-control" placeholder="Contoh: Bantuan Dana Desa / Aspal Jalan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">TARGET ANGGARAN (Rp)</label>
                            <input type="number" name="anggaran" class="form-control" placeholder="Nilai yang direncanakan" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">REALISASI SAAT INI (Rp)</label>
                            <input type="number" name="realisasi" class="form-control" placeholder="Uang yang sudah terpakai/cair" value="0" required>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary w-100 fw-bold py-3 rounded-3 shadow-sm">
                            <i class="bi bi-save me-2"></i> Simpan Data
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-premium p-4">
                    <h5 class="fw-bold mb-4">Ringkasan Transparansi</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr class="small text-secondary fw-bold">
                                    <th>KEGIATAN</th>
                                    <th>ANGGARAN & REALISASI</th>
                                    <th>PROGRESS</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($data_apb)): 
                                    $persen = ($row['anggaran'] > 0) ? round(($row['realisasi'] / $row['anggaran']) * 100) : 0;
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['rincian']); ?></div>
                                        <span class="badge <?php echo ($row['jenis'] == 'Pendapatan') ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'; ?> border px-2 py-1 rounded-pill small mt-1"><?php echo $row['jenis']; ?></span>
                                    </td>
                                    <td>
                                        <div class="small text-muted">Target: Rp <?php echo number_format($row['anggaran'], 0, ',', '.'); ?></div>
                                        <div class="fw-bold text-primary">Cair: Rp <?php echo number_format($row['realisasi'], 0, ',', '.'); ?></div>
                                    </td>
                                    <td width="150">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1">
                                                <div class="progress-bar <?php echo ($row['jenis'] == 'Pendapatan') ? 'bg-success' : 'bg-warning'; ?>" style="width: <?php echo $persen; ?>%"></div>
                                            </div>
                                            <small class="fw-bold"><?php echo $persen; ?>%</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0 rounded-pill" onclick="return confirm('Hapus data ini?')">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if(toggleBtn) {
        toggleBtn.addEventListener('click', () => { sidebar.classList.toggle('active'); });
    }
</script>
</body>
</html>