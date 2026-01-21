<?php include 'includes/header.php'; ?>

<section class="hero-profil">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Galeri Desa</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 700px;">
            Dokumentasi visual berbagai kegiatan, pembangunan, dan potensi yang ada di Desa Merak Batin.
        </p>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-4">
            <?php 
            // Ambil seluruh data galeri dari database
            $query_galeri = mysqli_query($conn, "SELECT * FROM galeri ORDER BY tgl_upload DESC");
            
            if(mysqli_num_rows($query_galeri) > 0):
                while($g = mysqli_fetch_assoc($query_galeri)): 
            ?>
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 gallery-item">
                    <div class="img-container position-relative" style="height: 280px;">
                        <img src="assets/img/galeri/<?php echo $g['foto']; ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?php echo htmlspecialchars($g['judul']); ?>">
                        
                        <div class="gallery-overlay d-flex align-items-center justify-content-center">
                            <button class="btn btn-light rounded-circle shadow" onclick="showImage('assets/img/galeri/<?php echo $g['foto']; ?>', '<?php echo htmlspecialchars($g['judul']); ?>')">
                                <i class="bi bi-zoom-in fs-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3 text-center">
                        <h6 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($g['judul']); ?></h6>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            <i class="bi bi-calendar-event me-1"></i> <?php echo date('d M Y', strtotime($g['tgl_upload'])); ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            else:
            ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-images fs-1 text-muted opacity-25"></i>
                <p class="text-muted mt-3">Belum ada koleksi foto yang tersedia.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <img src="" id="modalImg" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling Galeri agar Menarik */
    .gallery-item {
        transition: transform 0.3s ease;
    }
    .gallery-item:hover {
        transform: translateY(-10px);
    }
    .img-container {
        position: relative;
        overflow: hidden;
    }
    .gallery-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.6);
        opacity: 0;
        transition: 0.3s;
    }
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
</style>

<script>
    function showImage(src, title) {
        document.getElementById('modalImg').src = src;
        document.getElementById('modalTitle').innerText = title;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }
</script>

<?php include 'includes/footer.php'; ?>