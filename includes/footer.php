<footer class="py-5 text-white" style="background: var(--dark-blue); border-top: 5px solid var(--accent-red);">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="d-flex align-items-center mb-4">
                    <img src="assets/img/logo/<?php echo $profil['logo']; ?>" width="60" class="me-3">
                    <h4 class="fw-bold mb-0">DESA MERAK BATIN</h4>
                </div>
                <p class="opacity-75 lh-lg"><?php echo $kontak['alamat']; ?></p>
            </div>
            <div class="col-lg-3">
                <h6 class="fw-bold text-uppercase mb-4" style="letter-spacing: 2px;">Navigasi</h6>
                <ul class="list-unstyled opacity-75 d-grid gap-2">
                    <li><a href="index.php" class="text-white text-decoration-none">Beranda</a></li>
                    <li><a href="berita.php" class="text-white text-decoration-none">Berita</a></li>
                    <li><a href="layanan.php" class="text-white text-decoration-none">Info Layanan</a></li>
                    <li><a href="kontak.php" class="text-white text-decoration-none">Hubungi Kami</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="fw-bold text-uppercase mb-4" style="letter-spacing: 2px;">Kontak Resmi</h6>
                <div class="d-grid gap-3 opacity-75">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-whatsapp fs-5 text-success"></i>
                        <span><?php echo $kontak['whatsapp']; ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-envelope fs-5 text-warning"></i>
                        <span><?php echo $kontak['email']; ?></span>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-3">
                    <a href="<?php echo $kontak['facebook']; ?>" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                    <a href="<?php echo $kontak['instagram']; ?>" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-5 opacity-25">
        <div class="text-center small opacity-50">
            <p class="mb-0">&copy; 2026 Pemerintah Desa Merak Batin. Informatics KKN UNILA.</p>
        </div>
    </div>
</footer>

<script>
    window.onscroll = function() {
        let nav = document.getElementById('mainNav');
        // Hanya tambah class scrolled jika di halaman home (tidak punya class not-home)
        if (!nav.classList.contains('not-home')) {
            if (window.pageYOffset > 50) { 
                nav.classList.add('scrolled'); 
            } else { 
                nav.classList.remove('scrolled'); 
            }
        }
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>