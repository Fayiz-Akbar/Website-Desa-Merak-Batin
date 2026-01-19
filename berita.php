<?php include 'includes/header.php'; ?>

<section class="text-white" style="background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)), url('assets/img/hero-desa.jpeg'); background-size: cover; background-position: center; padding-top: 140px; padding-bottom: 80px;">
    <div class="container text-center">
        <h1 class="display-3 fw-bold mb-3">Berita Desa</h1>
        <p class="lead opacity-75 fs-4">Informasi terkini mengenai kegiatan, pembangunan, dan pengumuman resmi Desa Merak Batin.</p>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-4">
        <!-- Filter & Search -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
            <div class="row g-3 align-items-center">
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" id="searchBerita" placeholder="Cari berita...">
                    </div>
                </div>
                <div class="col-lg-4">
                    <select class="form-select" id="filterKategori">
                        <option value="">Semua Kategori</option>
                        <?php
                        $kat_query = mysqli_query($conn, "SELECT DISTINCT k.id, k.nama_kategori FROM kategori_berita k INNER JOIN berita b ON k.id = b.id_kategori ORDER BY k.nama_kategori ASC");
                        while($kat = mysqli_fetch_assoc($kat_query)):
                        ?>
                        <option value="<?php echo $kat['id']; ?>"><?php echo $kat['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-lg-3">
                    <select class="form-select" id="sortBerita">
                        <option value="terbaru">Terbaru</option>
                        <option value="terlama">Terlama</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Berita Grid -->
        <div class="row g-4" id="beritaContainer">
            <?php
            $query = mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b LEFT JOIN kategori_berita k ON b.id_kategori = k.id ORDER BY b.tgl_posting DESC");
            while($b = mysqli_fetch_assoc($query)):
            ?>
            <div class="col-md-4 berita-item" 
                 data-judul="<?php echo strtolower($b['judul']); ?>" 
                 data-kategori="<?php echo $b['id_kategori']; ?>"
                 data-tanggal="<?php echo strtotime($b['tgl_posting']); ?>">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <img src="assets/img/berita/<?php echo $b['gambar']; ?>" 
                         class="card-img-top" 
                         style="height: 250px; object-fit: cover;">
                    <div class="card-body p-4">
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-calendar3 me-1"></i><?php echo date('d M Y', strtotime($b['tgl_posting'])); ?>
                        </small>
                        <h5 class="fw-bold lh-base mb-3"><?php echo $b['judul']; ?></h5>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge bg-primary"><?php echo $b['nama_kategori'] ?? 'Umum'; ?></span>
                            <a href="berita_detail.php?id=<?php echo $b['id']; ?>" 
                               class="text-decoration-none fw-bold" 
                               style="color: var(--accent-red);">
                                Selengkapnya <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Pesan jika tidak ada hasil -->
        <div id="noResults" class="text-center py-5" style="display: none;">
            <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Tidak ada berita ditemukan</h5>
            <p class="text-muted">Coba ubah kata kunci atau filter pencarian Anda</p>
        </div>
    </div>
</section>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.12) !important;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.input-group-text {
    background-color: transparent;
}
</style>

<script>
function filterBerita() {
    const searchValue = document.getElementById('searchBerita').value.toLowerCase();
    const kategoriValue = document.getElementById('filterKategori').value;
    const beritaItems = document.querySelectorAll('.berita-item');
    let visibleCount = 0;
    
    beritaItems.forEach(item => {
        const judul = item.getAttribute('data-judul');
        const kategori = item.getAttribute('data-kategori');
        
        const matchSearch = judul.includes(searchValue);
        const matchKategori = kategoriValue === '' || kategori === kategoriValue;
        
        if (matchSearch && matchKategori) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
}

// Event Listeners untuk Filter
document.getElementById('searchBerita').addEventListener('keyup', filterBerita);
document.getElementById('filterKategori').addEventListener('change', filterBerita);

// Fitur Sorting Berita
document.getElementById('sortBerita').addEventListener('change', function() {
    const sortValue = this.value;
    const container = document.getElementById('beritaContainer');
    const beritaItems = Array.from(document.querySelectorAll('.berita-item'));
    
    beritaItems.sort((a, b) => {
        if (sortValue === 'terbaru') {
            return b.getAttribute('data-tanggal') - a.getAttribute('data-tanggal');
        } else if (sortValue === 'terlama') {
            return a.getAttribute('data-tanggal') - b.getAttribute('data-tanggal');
        }
    });
    
    // Re-append items in sorted order
    beritaItems.forEach(item => container.appendChild(item));
});
</script>

<?php include 'includes/footer.php'; ?>