<?php include 'includes/header.php'; ?>

<section class="py-8 text-white" style="background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)), url('assets/img/hero-desa.jpeg'); background-size: cover; background-position: center; margin-top: 80px; min-height: 450px;">
    <div class="container py-5 text-center d-flex flex-column justify-content-center" style="min-height: 370px;">
        <h1 class="display-3 fw-bold mb-3">Berita Desa</h1>
        <p class="lead opacity-75 fs-4">Informasi terkini mengenai kegiatan, pembangunan, dan pengumuman resmi Desa Merak Batin.</p>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-4">
        <!-- Filter dan Pencarian -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-3">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" id="searchBerita" class="form-control border-start-0 rounded-end-3" placeholder="Cari berita...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select rounded-3" id="sortBerita">
                                <option value="terbaru">Terbaru</option>
                                <option value="terlama">Terlama</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Berita -->
        <div class="row g-4" id="beritaContainer">
            <?php 
            $query_berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY tgl_posting DESC");
            
            if(mysqli_num_rows($query_berita) > 0):
                while($b = mysqli_fetch_assoc($query_berita)): 
            ?>
            <div class="col-lg-4 col-md-6 berita-item" data-judul="<?php echo strtolower($b['judul']); ?>" data-tanggal="<?php echo $b['tgl_posting']; ?>">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-lift">
                    <div class="position-relative overflow-hidden" style="height: 250px;">
                        <img src="assets/img/berita/<?php echo $b['gambar']; ?>" class="card-img-top h-100 w-100 zoom-img" style="object-fit: cover; transition: transform 0.5s ease;" alt="<?php echo $b['judul']; ?>">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.3) 100%);"></div>
                        <span class="position-absolute bottom-0 start-0 m-3 badge px-3 py-2 rounded-pill" style="background: var(--accent-red); font-size: 0.85rem;">
                            <i class="bi bi-calendar3 me-2"></i><?php echo date('d M Y', strtotime($b['tgl_posting'])); ?>
                        </span>
                    </div>
                    
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="mb-3">
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #e8f5e9; color: #2e7d32; font-weight: 600; font-size: 0.75rem;">
                                <i class="bi bi-tag-fill me-1"></i>Berita Desa
                            </span>
                        </div>
                        
                        <h5 class="fw-bold lh-base mb-3" style="color: var(--dark-blue); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <?php echo $b['judul']; ?>
                        </h5>
                        
                        <p class="text-muted small mb-4 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            <?php 
                            // Gunakan ringkasan jika ada, jika tidak gunakan potongan dari isi
                            if(!empty($b['ringkasan'])) {
                                echo $b['ringkasan'];
                            } else {
                                $isi_clean = strip_tags($b['isi'] ?? '');
                                echo substr($isi_clean, 0, 150) . '...';
                            }
                            ?>
                        </p>
                        
                        <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top">
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-person-circle me-2"></i>
                                <span>Admin Desa</span>
                            </div>
                            <a href="berita_detail.php?id=<?php echo $b['id']; ?>" class="btn btn-sm rounded-pill px-4" style="background: var(--accent-red); color: white; font-weight: 600;">
                                Baca <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            else:
            ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-4 bg-light">
                            <i class="bi bi-newspaper fs-1 text-muted"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Belum Ada Berita</h5>
                    <p class="text-muted mb-0">Belum ada berita yang dipublikasikan saat ini.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.hover-lift:hover .zoom-img {
    transform: scale(1.1);
}

.card {
    transition: all 0.3s ease;
}

/* Animasi untuk input search */
#searchBerita:focus {
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
    border-color: var(--accent-red);
}

.form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    border-color: #0d6efd;
}

/* Smooth scroll untuk anchor link */
html {
    scroll-behavior: smooth;
}
</style>

<script>
// Fitur Pencarian Berita
document.getElementById('searchBerita').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const beritaItems = document.querySelectorAll('.berita-item');
    
    beritaItems.forEach(item => {
        const judul = item.getAttribute('data-judul');
        if(judul.includes(searchValue)) {
            item.style.display = '';
            // Animasi fade in
            item.style.animation = 'fadeIn 0.5s';
        } else {
            item.style.display = 'none';
        }
    });
});

// Fitur Sorting Berita
document.getElementById('sortBerita').addEventListener('change', function() {
    const sortValue = this.value;
    const container = document.getElementById('beritaContainer');
    const beritaItems = Array.from(document.querySelectorAll('.berita-item'));
    
    beritaItems.sort((a, b) => {
        const dateA = new Date(a.getAttribute('data-tanggal'));
        const dateB = new Date(b.getAttribute('data-tanggal'));
        
        if(sortValue === 'terbaru') {
            return dateB - dateA;
        } else {
            return dateA - dateB;
        }
    });
    
    // Re-append items in sorted order
    beritaItems.forEach(item => container.appendChild(item));
});

// Animasi CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);
</script>

<?php include 'includes/footer.php'; ?>