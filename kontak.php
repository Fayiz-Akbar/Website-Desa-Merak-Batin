<?php include 'includes/header.php'; ?>

<section class="hero-profil">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Hubungi Kami</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 700px;">
            Silakan hubungi kami melalui saluran resmi di bawah ini atau kunjungi langsung Kantor Balai Desa Merak Batin.
        </p>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-5">
            <div class="col-lg-5">
                <h6 class="text-uppercase fw-bold mb-3" style="color: var(--accent-red); letter-spacing: 2px;">Saluran Resmi</h6>
                <h2 class="fw-bold mb-4">Informasi Kontak</h2>
                
                <?php 
                // Mengambil data kontak dari database
                $query_kontak = mysqli_query($conn, "SELECT * FROM kontak WHERE id=1");
                $k = mysqli_fetch_assoc($query_kontak);
                ?>

                <div class="d-grid gap-4 mt-5">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-box bg-light rounded-4 p-3 shadow-sm">
                            <i class="bi bi-geo-alt-fill fs-3 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Alamat Kantor</h6>
                            <p class="text-muted small mb-0"><?php echo nl2br($k['alamat'] ?? 'Jl. Raya Merak Batin, Natar, Lampung Selatan'); ?></p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-box bg-light rounded-4 p-3 shadow-sm">
                            <i class="bi bi-envelope-at-fill fs-3 text-danger"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Email Resmi</h6>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($k['email'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-box bg-light rounded-4 p-3 shadow-sm">
                            <i class="bi bi-whatsapp fs-3 text-success"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">WhatsApp Center</h6>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($k['whatsapp'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4">
                    <h6 class="fw-bold mb-3">Ikuti Media Sosial Kami:</h6>
                    <div class="d-flex gap-2">
                        <a href="<?php echo htmlspecialchars($k['facebook'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary rounded-circle p-2 px-3 shadow-sm"><i class="bi bi-facebook"></i></a>
                        <a href="<?php echo htmlspecialchars($k['instagram'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-danger rounded-circle p-2 px-3 shadow-sm"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow rounded-5 overflow-hidden h-100">
                    <div class="card-body p-0">
                        <?php 
                        if(!empty($k['maps_embed'])) {
                            // Menyesuaikan ukuran iframe maps agar responsif
                            $map_res = str_replace('width="600"', 'width="100%"', $k['maps_embed']);
                            $map_res = str_replace('height="450"', 'height="100%" style="min-height: 500px; border:0;"', $map_res);
                            echo $map_res;
                        } else {
                            echo '<div class="h-100 d-flex align-items-center justify-content-center bg-light"><p class="text-muted italic">Peta belum tersedia</p></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Styling khusus Kontak */
    .icon-box {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .hero-profil {
        background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.9)), 
                    url('assets/img/hero-desa.jpeg'); /* Menggunakan aset hero desa */
        background-size: cover;
        background-position: center;
        padding: 160px 0 100px;
        color: white;
        text-align: center;
    }
</style>

<?php include 'includes/footer.php'; ?>