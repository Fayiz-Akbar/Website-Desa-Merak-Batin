<?php include 'includes/header.php'; ?>

<section class="hero-profil">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Struktur Organisasi</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 700px;">
            Aparatur Pemerintah Desa Merak Batin yang siap melayani dengan integritas dan profesionalisme.
        </p>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold mb-2" style="color: var(--accent-red); letter-spacing: 2px;">Pemerintahan Desa</h6>
            <h2 class="fw-bold">Perangkat Desa Merak Batin</h2>
            <div style="width: 50px; height: 4px; background: var(--accent-red); margin: 0 auto;"></div>
        </div>

        <div class="row g-4 justify-content-center">
            <?php 
            // Ambil semua perangkat desa berdasarkan urutan tampil
            $perangkat = mysqli_query($conn, "SELECT * FROM perangkat_desa ORDER BY urutan ASC");
            
            if(mysqli_num_rows($perangkat) > 0):
                while($p = mysqli_fetch_assoc($perangkat)): 
            ?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 perangkat-card text-center p-3">
                    <div class="position-relative mb-4 mt-2">
                        <div class="foto-container mx-auto">
                            <img src="assets/img/perangkat/<?php echo $p['foto'] ?? 'default-user.jpg'; ?>" 
                                 class="rounded-circle shadow" 
                                 alt="<?php echo htmlspecialchars($p['nama']); ?>"
                                 style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <h5 class="fw-bold mb-1" style="color: var(--dark-blue); font-size: 1.1rem;">
                            <?php echo htmlspecialchars($p['nama']); ?>
                        </h5>
                        <p class="mb-3 text-uppercase fw-bold" style="color: var(--accent-red); font-size: 0.75rem; letter-spacing: 1px;">
                            <?php echo htmlspecialchars($p['jabatan']); ?>
                        </p>
                        
                        <div class="d-flex justify-content-center gap-2 mb-2">
                            <span class="badge rounded-pill bg-light text-muted border px-3">
                                <i class="bi bi-shield-check me-1"></i> Aktif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            else:
            ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-people fs-1 text-muted opacity-25"></i>
                <p class="text-muted mt-3">Data perangkat desa belum tersedia.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    /* UI Khusus untuk Struktur Organisasi */
    .perangkat-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border-top: 5px solid var(--dark-blue) !important;
    }
    
    .perangkat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(15, 23, 42, 0.15) !important;
        border-top: 5px solid var(--accent-red) !important;
    }

    .foto-container {
        width: 160px;
        height: 160px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--dark-blue), var(--accent-red));
        border-radius: 50%;
        padding: 5px;
    }

    .perangkat-card h5 {
        min-height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<?php include 'includes/footer.php'; ?>