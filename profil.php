<?php include 'includes/header.php'; ?>

<section class="text-white" style="background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), url('assets/img/merakbatin.jpeg'); background-size: cover; background-position: center; padding-top: 140px; padding-bottom: 80px;">
    <div class="container text-center">
        <h1 class="display-3 fw-bold mb-3">Profil Desa</h1>
        <p class="lead opacity-75 fs-4">Mengenal lebih dekat sejarah dan cita-cita Desa Merak Batin.</p>
    </div>
</section>

<section id="visimisi" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold mb-3" style="color: var(--accent-red); letter-spacing: 2px;">Cita-cita Desa</h6>
            <h2 class="fw-bold display-6">Visi & Misi</h2>
            <hr class="mx-auto" style="width: 60px; height: 4px; background: var(--accent-red); border: 0; opacity: 1;">
        </div>
        
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 text-center border-top-crimson">
                    <div class="mb-4">
                        <div class="icon-circle bg-danger bg-opacity-10 mx-auto">
                            <i class="bi bi-eye-fill fs-2" style="color: var(--accent-red);"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-4" style="color: var(--dark-blue);">Visi</h4>
                    <p class="vision-text italic">
                        "<?php echo nl2br($profil['visi'] ?? ''); ?>"
                    </p>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 border-top-navy">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-circle bg-primary bg-opacity-10 me-3">
                            <i class="bi bi-list-check fs-2" style="color: var(--dark-blue);"></i>
                        </div>
                        <h4 class="fw-bold mb-0" style="color: var(--dark-blue);">Misi Desa</h4>
                    </div>
                    <div class="mission-content">
                        <?php echo nl2br($profil['misi'] ?? ''); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* CSS Tambahan untuk Memperjelas Tulisan */
    :root {
        --text-dark: #1e293b; /* Warna Slate-800 agar lebih mudah dibaca daripada abu-abu biasa */
    }

    .border-top-crimson { border-top: 6px solid var(--accent-red) !important; }
    .border-top-navy { border-top: 6px solid var(--dark-blue) !important; }

    .icon-circle {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    /* Styling Teks Visi agar lebih 'berwibawa' */
    .vision-text {
        font-size: 1.35rem; /* Ukuran lebih besar dari teks biasa */
        font-weight: 600;
        color: var(--text-dark);
        line-height: 1.6;
        padding: 0 15px;
    }

    /* Styling Teks Misi agar tidak terlihat menumpuk */
    .mission-content {
        font-size: 1.1rem;
        line-height: 1.9; /* Menambah jarak antar baris agar mata tidak lelah */
        color: #334155;
        font-weight: 400;
        text-align: justify;
    }

    /* Memastikan teks yang ditebalkan (bold) di CKEditor tetap terlihat menonjol */
    .mission-content strong, .mission-content b {
        color: var(--dark-blue);
        font-weight: 700;
    }
</style>

<section id="sejarah" class="py-5 bg-light">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="rounded-4 overflow-hidden shadow-lg">
                    <img src="assets/img/hero-desa.jpeg" class="w-100" alt="Foto Desa Merak Batin" style="height: 400px; object-fit: cover;">
                </div>
            </div>
            <div class="col-lg-6">
                <h6 class="text-uppercase fw-bold mb-3" style="color: var(--accent-red); letter-spacing: 2px;">Jejak Langkah</h6>
                <h2 class="fw-bold mb-4">Sejarah Desa Merak Batin</h2>
                <div class="lh-lg text-muted">
                    <?php echo nl2br($profil['sejarah'] ?? ''); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="geografis" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold mb-3" style="color: var(--accent-red); letter-spacing: 2px;">Posisi & Wilayah</h6>
            <h2 class="fw-bold">Letak Geografis</h2>
            <hr class="mx-auto" style="width: 60px; height: 4px; background: var(--accent-red); border: 0; opacity: 1;">
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center hover-lift" style="transition: transform 0.3s;">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-3 bg-danger bg-opacity-10">
                            <i class="bi bi-geo-alt-fill fs-2 text-danger"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-uppercase mb-2" style="font-size: 0.75rem; color: #6c757d; letter-spacing: 1px;">Desa</h6>
                    <h5 class="fw-bold mb-0">Merak Batin</h5>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center hover-lift" style="transition: transform 0.3s;">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-3 bg-primary bg-opacity-10">
                            <i class="bi bi-pin-map-fill fs-2 text-primary"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-uppercase mb-2" style="font-size: 0.75rem; color: #6c757d; letter-spacing: 1px;">Kecamatan</h6>
                    <h5 class="fw-bold mb-0">Natar</h5>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center hover-lift" style="transition: transform 0.3s;">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-3 bg-success bg-opacity-10">
                            <i class="bi bi-building fs-2 text-success"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-uppercase mb-2" style="font-size: 0.75rem; color: #6c757d; letter-spacing: 1px;">Kabupaten</h6>
                    <h5 class="fw-bold mb-0">Lampung Selatan</h5>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center hover-lift" style="transition: transform 0.3s;">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-3 bg-warning bg-opacity-10">
                            <i class="bi bi-map fs-2 text-warning"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-uppercase mb-2" style="font-size: 0.75rem; color: #6c757d; letter-spacing: 1px;">Provinsi</h6>
                    <h5 class="fw-bold mb-0">Lampung</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="potensi" class="py-5" style="background-color: #f8fafc;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold mb-3" style="color: var(--accent-red); letter-spacing: 2px;">Kekayaan Desa</h6>
            <h2 class="fw-bold">Potensi Desa</h2>
            <hr class="mx-auto" style="width: 60px; height: 4px; background: var(--accent-red); border: 0; opacity: 1;">
            <p class="text-muted mt-3">Mengenal keunggulan UMKM, wisata, dan hasil bumi Desa Merak Batin.</p>
        </div>

        <div class="row g-4">
            <?php 
            $q_potensi = mysqli_query($conn, "SELECT p.*, k.nama_kategori FROM potensi_desa p 
                                              LEFT JOIN kategori_potensi k ON p.id_kategori = k.id 
                                              ORDER BY p.id DESC");
            if (mysqli_num_rows($q_potensi) > 0) :
                while ($p = mysqli_fetch_assoc($q_potensi)) :
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-lift" style="transition: all 0.3s ease;">
                    <div class="position-relative">
                        <img src="assets/img/potensi/<?php echo $p['foto']; ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="<?php echo $p['judul']; ?>">
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-crimson px-3 py-2 rounded-pill shadow-sm">
                                <?php echo $p['nama_kategori']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-2" style="color: var(--dark-blue);"><?php echo $p['judul']; ?></h5>
                        <p class="text-muted small mb-0 lh-lg">
                            <?php echo nl2br($p['deskripsi']); ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php 
                endwhile;
            else :
                echo '<div class="col-12 text-center text-muted italic">Belum ada data potensi desa.</div>';
            endif;
            ?>
        </div>
    </div>
</section>

<section id="peta" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold mb-3" style="color: var(--accent-red); letter-spacing: 2px;">Temukan Kami</h6>
            <h2 class="fw-bold">Peta Lokasi Desa</h2>
            <hr class="mx-auto" style="width: 60px; height: 4px; background: var(--accent-red); border: 0; opacity: 1;">
            <p class="text-muted mt-3">Kunjungi kami di Kecamatan Natar, Lampung Selatan.</p>
        </div>
        <div class="rounded-4 overflow-hidden shadow-lg border" style="border: 3px solid #e9ecef !important;">
            <?php 
            $q_map = mysqli_fetch_assoc(mysqli_query($conn, "SELECT maps_embed FROM kontak WHERE id=1"));
            $map_iframe = str_replace('width="600"', 'width="100%"', $q_map['maps_embed'] ?? '');
            $map_iframe = str_replace('height="450"', 'height="550"', $map_iframe);
            echo $map_iframe; 
            ?>
        </div>
    </div>
</section>

<style>
.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 1rem 3rem rgba(15, 23, 42, 0.15) !important;
}
.bg-crimson {
    background-color: var(--accent-red);
}
</style>

<?php include 'includes/footer.php'; ?>