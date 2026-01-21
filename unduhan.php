<?php include 'includes/header.php'; ?>

<section class="hero-profil">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Pusat Unduhan</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 700px;">
            Akses dokumen resmi, formulir pelayanan, dan informasi publik Desa Merak Batin secara cepat dan mandiri.
        </p>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px;">
                    <h6 class="fw-bold mb-3" style="color: var(--dark-blue);">Filter Kategori</h6>
                    <div class="list-group list-group-flush">
                        <a href="unduhan.php" class="list-group-item list-group-item-action border-0 px-0 d-flex justify-content-between align-items-center">
                            Semua Dokumen <i class="bi bi-chevron-right small"></i>
                        </a>
                        <?php 
                        // Ambil kategori dari database
                        $categories = mysqli_query($conn, "SELECT * FROM kategori_unduhan ORDER BY nama_kategori ASC");
                        while($cat = mysqli_fetch_assoc($categories)):
                            $kat_id = $cat['id'];
                            $count_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM unduhan WHERE id_kategori = $kat_id"));
                        ?>
                        <a href="?kategori=<?php echo $kat_id; ?>" class="list-group-item list-group-item-action border-0 px-0 d-flex justify-content-between align-items-center">
                            <?php echo $cat['nama_kategori']; ?>
                            <span class="badge rounded-pill bg-light text-dark border"><?php echo $count_res['total']; ?></span>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-uppercase fw-bold" style="color: var(--dark-blue);">
                                    <th class="ps-4 py-3">Nama Dokumen</th>
                                    <th class="py-3">Kategori</th>
                                    <th class="text-end pe-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Logika filter kategori
                                $where = "";
                                if(isset($_GET['kategori'])) {
                                    $k_id = (int)$_GET['kategori'];
                                    $where = "WHERE u.id_kategori = $k_id";
                                }

                                // Query data unduhan
                                $query_unduhan = mysqli_query($conn, "SELECT u.*, k.nama_kategori FROM unduhan u LEFT JOIN kategori_unduhan k ON u.id_kategori = k.id $where ORDER BY u.tgl_upload DESC");
                                
                                if(mysqli_num_rows($query_unduhan) > 0):
                                    while($d = mysqli_fetch_assoc($query_unduhan)):
                                        // Menentukan icon berdasarkan ekstensi file
                                        $ext = pathinfo($d['nama_file'], PATHINFO_EXTENSION);
                                        $icon = "bi-file-earmark-text";
                                        if($ext == 'pdf') $icon = "bi-file-earmark-pdf text-danger";
                                        if(in_array($ext, ['doc', 'docx'])) $icon = "bi-file-earmark-word text-primary";
                                        if(in_array($ext, ['xls', 'xlsx'])) $icon = "bi-file-earmark-excel text-success";
                                ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <i class="bi <?php echo $icon; ?> fs-3"></i>
                                            <div>
                                                <h6 class="fw-bold mb-0" style="color: var(--dark-blue);"><?php echo htmlspecialchars($d['nama_dokumen']); ?></h6>
                                                <small class="text-muted">Diunggah: <?php echo date('d M Y', strtotime($d['tgl_upload'])); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill">
                                            <?php echo $d['nama_kategori'] ?? 'Lainnya'; ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="assets/files/unduhan/<?php echo $d['nama_file']; ?>" class="btn btn-red btn-sm px-3 rounded-pill shadow-sm" download>
                                            <i class="bi bi-download me-1"></i> Unduh
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile; 
                                else:
                                ?>
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted italic">
                                        <i class="bi bi-folder-x fs-1 opacity-25 d-block mb-2"></i>
                                        Belum ada dokumen yang tersedia di kategori ini.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .list-group-item-action:hover {
        background-color: rgba(190, 18, 60, 0.05);
        color: var(--accent-red) !important;
    }
    .hero-profil {
        background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.85)), 
                    url('assets/img/hero-desa.jpeg');
        background-size: cover;
        background-position: center;
        padding: 160px 0 100px;
        color: white;
        text-align: center;
    }
</style>

<?php include 'includes/footer.php'; ?>