<?php include 'includes/header.php'; ?>

<section class="hero-profil">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Berita Desa</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 700px;">
            Informasi terkini mengenai kegiatan, pembangunan, dan pengumuman resmi Desa Merak Batin.
        </p>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-4">
            <?php 
            // Query untuk mengambil semua berita diurutkan dari yang terbaru
            $query_berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY tgl_posting DESC");
            
            if(mysqli_num_rows($query_berita) > 0):
                while($b = mysqli_fetch_assoc($query_berita)): 
            ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-up">
                    <div class="position-relative">
                        <img src="assets/img/berita/<?php echo $b['gambar']; ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Thumbnail Berita">
                        <span class="position-absolute bottom-0 start-0 m-3 badge px-3 py-2" style="background: var(--accent-red);">
                            <i class="bi bi-calendar3 me-2"></i><?php echo date('d M Y', strtotime($b['tgl_posting'])); ?>
                        </span>
                    </div>
                    
                    <div class="card-body p-4">
                        <h5 class="fw-bold lh-base mb-3" style="color: var(--dark-blue);">
                            <?php echo $b['judul']; ?>
                        </h5>
                        <p class="text-muted small mb-4">
                            <?php 
                            // Menampilkan cuplikan isi berita (strip tags untuk menghilangkan format HTML)
                            echo substr(strip_tags($b['isi']), 0, 100) . '...'; 
                            ?>
                        </p>
                        <a href="berita_detail.php?id=<?php echo $b['id']; ?>" class="text-decoration-none fw-bold" style="color: var(--accent-red);">
                            Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            else:
            ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-newspaper fs-1 text-muted opacity-25"></i>
                <p class="text-muted mt-3">Belum ada berita yang dipublikasikan.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    /* Efek hover pada kartu berita */
    .transition-up {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .transition-up:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    /* Mengikuti tema warna header profil */
    .hero-profil {
        background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.95)), 
                    url('assets/img/hero-desa.jpg');
        background-size: cover;
        background-position: center;
        padding: 150px 0 80px;
        color: white;
        text-align: center;
    }
</style>

<?php include 'includes/footer.php'; ?>