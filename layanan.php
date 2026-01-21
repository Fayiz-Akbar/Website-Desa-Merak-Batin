<?php include 'includes/header.php'; ?>

<section class="hero-profil">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Layanan Publik</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 700px;">
            Informasi lengkap mengenai persyaratan, prosedur, biaya, dan estimasi waktu pelayanan administrasi desa.
        </p>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold mb-2" style="color: var(--accent-red); letter-spacing: 2px;">Pusat Informasi</h6>
            <h2 class="fw-bold">Pelayanan Dokumen</h2>
            <div style="width: 50px; height: 4px; background: var(--accent-red); margin: 0 auto;"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion" id="accordionLayanan">
                    <?php 
                    // Query mengambil semua data layanan
                    $query_layanan = mysqli_query($conn, "SELECT * FROM layanan ORDER BY nama_layanan ASC");
                    $i = 0;
                    
                    if(mysqli_num_rows($query_layanan) > 0):
                        while($l = mysqli_fetch_assoc($query_layanan)): 
                            $i++;
                            $collapse_id = "collapse" . $l['id'];
                    ?>
                    <div class="accordion-item border-0 shadow-sm mb-3 rounded-4 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-4 px-4 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapse_id; ?>" style="color: var(--dark-blue); font-size: 1.1rem;">
                                <i class="bi bi-file-earmark-text text-crimson me-3"></i>
                                <?php echo htmlspecialchars($l['nama_layanan']); ?>
                            </button>
                        </h2>
                        <div id="<?php echo $collapse_id; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionLayanan">
                            <div class="accordion-body p-4 bg-light">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-white rounded-3 h-100 shadow-sm border-start border-primary border-4">
                                            <h6 class="fw-bold text-uppercase small text-muted mb-3"><i class="bi bi-list-check me-2"></i>Persyaratan</h6>
                                            <div class="small text-dark">
                                                <?php echo $l['persyaratan']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-white rounded-3 h-100 shadow-sm border-start border-success border-4">
                                            <h6 class="fw-bold text-uppercase small text-muted mb-3"><i class="bi bi-arrow-repeat me-2"></i>Prosedur</h6>
                                            <div class="small text-dark">
                                                <?php echo $l['prosedur']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="d-flex flex-wrap gap-3 mt-2">
                                            <div class="badge bg-white text-dark border p-3 rounded-3 shadow-sm d-flex align-items-center gap-2">
                                                <i class="bi bi-cash-coin text-success fs-5"></i>
                                                <div>
                                                    <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Biaya</small>
                                                    <span class="fw-bold">Rp <?php echo htmlspecialchars($l['biaya']); ?></span>
                                                </div>
                                            </div>
                                            <div class="badge bg-white text-dark border p-3 rounded-3 shadow-sm d-flex align-items-center gap-2">
                                                <i class="bi bi-clock-history text-primary fs-5"></i>
                                                <div>
                                                    <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Estimasi Waktu</small>
                                                    <span class="fw-bold"><?php echo htmlspecialchars($l['estimasi_waktu']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <div class="text-center py-5">
                        <i class="bi bi-info-circle fs-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-3">Belum ada data layanan yang ditambahkan.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-5 pt-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center" style="background: linear-gradient(135deg, var(--dark-blue), #1e293b); color: white;">
                    <h5 class="fw-bold mb-3">Butuh bantuan lebih lanjut?</h5>
                    <p class="opacity-75 mb-4">Jika Anda memiliki pertanyaan seputar layanan yang tidak tercantum di atas, silakan hubungi kami atau datang langsung ke Balai Desa.</p>
                    <div>
                        <a href="kontak.php" class="btn btn-red px-4 py-2">Hubungi Kami</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* UI Khusus Layanan */
    .text-crimson { color: var(--accent-red); }
    
    .accordion-button:not(.collapsed) {
        background-color: rgba(15, 23, 42, 0.03);
        color: var(--dark-blue);
        box-shadow: none;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }

    .accordion-item {
        border: 1px solid rgba(0,0,0,0.05) !important;
        transition: transform 0.3s ease;
    }

    .accordion-item:hover {
        transform: scale(1.01);
    }

    /* Mengatur list di dalam persyaratan/prosedur */
    .accordion-body ul, .accordion-body ol {
        padding-left: 1.2rem;
        margin-bottom: 0;
    }
</style>

<?php include 'includes/footer.php'; ?>