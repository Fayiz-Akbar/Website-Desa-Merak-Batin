<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = ""; $error = "";

// --- LOGIKA KATEGORI ---
if (isset($_POST['tambah_kategori'])) {
    $nama_kat = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "INSERT INTO kategori_berita (nama_kategori) VALUES ('$nama_kat')");
    $sukses = "Kategori berhasil ditambah!";
}

if (isset($_GET['hapus_kategori'])) {
    $id_kat = (int)$_GET['hapus_kategori'];
    mysqli_query($conn, "DELETE FROM kategori_berita WHERE id = $id_kat");
    header("Location: manage-berita.php"); exit;
}

// --- LOGIKA BERITA (Tambah & Edit) ---
if (isset($_POST['tambah_berita']) || isset($_POST['edit_berita'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $id_kategori = (int)$_POST['id_kategori'];
    $isi = mysqli_real_escape_string($conn, $_POST['isi_berita']);
    $ringkasan = mysqli_real_escape_string($conn, $_POST['ringkasan']);
    
    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../assets/img/berita/";
        $new_filename = uniqid() . '.' . strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $new_filename);
        $foto = $new_filename;
    }

    if (isset($_POST['tambah_berita'])) {
        mysqli_query($conn, "INSERT INTO berita (judul, id_kategori, gambar, isi_berita, ringkasan, tgl_posting) VALUES ('$judul', $id_kategori, '$foto', '$isi', '$ringkasan', NOW())");
        $sukses = "Berita berhasil terbit!";
    } else {
        $id = (int)$_POST['id_berita'];
        if ($foto != "") mysqli_query($conn, "UPDATE berita SET gambar = '$foto' WHERE id = $id");
        mysqli_query($conn, "UPDATE berita SET judul='$judul', id_kategori=$id_kategori, isi_berita='$isi', ringkasan='$ringkasan' WHERE id=$id");
        $sukses = "Berita diperbarui!";
    }
}

// Hapus Berita
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM berita WHERE id = $id");
    header("Location: manage-berita.php"); exit;
}

$data_berita = mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b LEFT JOIN kategori_berita k ON b.id_kategori = k.id ORDER BY b.tgl_posting DESC");
$categories = mysqli_query($conn, "SELECT * FROM kategori_berita ORDER BY nama_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Merak Batin</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/merakbatin.jpeg">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/merakbatin.jpeg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        :root { --sidebar-bg: #1a1d20; --active-blue: #0d6efd; }
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; margin: 0; }

        /* SIDEBAR SAMA SEPERTI INDEX.PHP */
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
            text-decoration: none;
            font-size: 0.95rem;
        }
        .nav-link i { font-size: 1.15rem; margin-right: 11px; }
        .nav-link:hover, .nav-link.active { background: rgba(13, 110, 253, 0.15); color: var(--active-blue); }
        .nav-link.active { background: var(--active-blue); color: #fff; }

        .logout-section { 
            margin-top: auto; 
            padding-top: 16px; 
            border-top: 1px solid #2d3238;
            flex-shrink: 0;
            background: var(--sidebar-bg);
        }
        .logout-link { color: #ea868f !important; }
        .logout-link:hover { background: rgba(234, 134, 143, 0.1); }

        .main-content { margin-left: 280px; padding: 40px; }
        .mobile-header { display: none; background: #fff; padding: 15px 20px; border-bottom: 1px solid #dee2e6; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 999; }
        }

        .card-premium { border: none; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); background: #fff; }

        /* FIX LINK & IMAGE POPUP DI MODAL */
        :root { --ck-z-modal: 1060 !important; }
        .ck-editor__editable { min-height: 400px; }
        .berita-img { width: 80px; height: 50px; object-fit: cover; border-radius: 10px; }
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
            <li><a href="manage-galeri.php" class="nav-link"><i class="bi bi-images"></i> Galeri Desa</a></li>
            <li><a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>

        <div class="logout-section">
            <a href="logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <main class="main-content w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Manajemen Konten</h3>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalKategori">Kategori</button>
                <button class="btn btn-primary rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#modalBerita">Tulis Berita</button>
            </div>
        </div>

        <?php if($sukses): ?> <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><?php echo $sukses; ?></div> <?php endif; ?>

        <div class="card card-premium p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr class="small text-secondary fw-bold"><th>FOTO</th><th>JUDUL</th><th>AKSI</th></tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($data_berita)): ?>
                        <tr>
                            <td><img src="../assets/img/berita/<?php echo $row['gambar']; ?>" class="berita-img"></td>
                            <td><div class="fw-bold"><?php echo $row['judul']; ?></div><small class="text-muted"><?php echo $row['nama_kategori']; ?></small></td>
                            <td>
                                <button class="btn btn-sm btn-outline-warning border-0" onclick='editBerita(<?php echo json_encode($row); ?>)'><i class="bi bi-pencil-square"></i></button>
                                <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header"><h5 class="fw-bold mb-0">Kelola Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form action="" method="POST" class="mb-4">
                    <label class="form-label small fw-bold">TAMBAH BARU</label>
                    <div class="input-group">
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Nama kategori..." required>
                        <button type="submit" name="tambah_kategori" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                <label class="form-label small fw-bold">DAFTAR KATEGORI</label>
                <ul class="list-group list-group-flush">
                    <?php mysqli_data_seek($categories, 0); while($cat = mysqli_fetch_assoc($categories)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                            <?php echo $cat['nama_kategori']; ?>
                            <a href="?hapus_kategori=<?php echo $cat['id']; ?>" class="text-danger small" onclick="return confirm('Hapus kategori ini?')"><i class="bi bi-x-circle-fill"></i></a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBerita" tabindex="-1">
    <div class="modal-dialog modal-xl"><div class="modal-content">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_berita" id="id_berita">
            <div class="modal-header"><h5 class="fw-bold" id="modalTitle">Tulis Berita Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><div class="row g-4">
                <div class="col-lg-9">
                    <input type="text" name="judul" id="judul" class="form-control form-control-lg border-0 bg-light mb-4" placeholder="Judul Berita" required>
                    <div class="mb-3">
                        <label class="form-label">Ringkasan Berita</label>
                        <textarea name="ringkasan" class="form-control" rows="3" placeholder="Ringkasan singkat berita (maksimal 200 karakter)"></textarea>
                    </div>
                    <textarea name="isi_berita" id="editor"></textarea>
                </div>
                <div class="col-lg-3"><div class="p-3 bg-light rounded-4">
                    <label class="form-label fw-bold small">KATEGORI</label>
                    <select name="id_kategori" id="id_kategori" class="form-select mb-4" required>
                        <?php mysqli_data_seek($categories, 0); while($cat = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <label class="form-label fw-bold small">FOTO SAMPUL</label>
                    <input type="file" name="foto" class="form-control mb-4">
                    <button type="submit" name="tambah_berita" id="btnSubmit" class="btn btn-primary w-100 py-2 fw-bold">Publikasikan</button>
                </div></div>
            </div></div>
        </form>
    </div></div>
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

    let editorInstance;
    ClassicEditor.create(document.querySelector('#editor'), {
        ckfinder: { uploadUrl: 'upload_image.php' }, // Mesin pengolah gambar
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'imageUpload', 'undo', 'redo' ]
    }).then(editor => { editorInstance = editor; });

    function editBerita(data) {
        document.getElementById('modalTitle').innerText = "Edit Berita";
        document.getElementById('btnSubmit').innerText = "Simpan Perubahan";
        document.getElementById('btnSubmit').name = "edit_berita";
        document.getElementById('id_berita').value = data.id;
        document.getElementById('judul').value = data.judul;
        document.getElementById('id_kategori').value = data.id_kategori;
        editorInstance.setData(data.isi_berita);
        new bootstrap.Modal(document.getElementById('modalBerita')).show();
    }
</script>
</body>
</html>