<?php
// Pastikan koneksi db sudah ada (biasanya di header), lalu ambil data kontak
// Kita ambil data ID 1 sesuai dengan manage-kontak.php
$query_footer = mysqli_query($conn, "SELECT * FROM kontak WHERE id=1");
$kontak = mysqli_fetch_assoc($query_footer);
?>

<footer class="py-5 text-white" style="background: var(--dark-blue); border-top: 5px solid var(--accent-red);">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="d-flex align-items-center mb-4">
                    <?php if (!empty($profil['logo'])): ?>
                        <img src="assets/img/logo/<?php echo htmlspecialchars($profil['logo'], ENT_QUOTES, 'UTF-8'); ?>" width="60" class="me-3">
                    <?php endif; ?>
                    <h4 class="fw-bold mb-0">DESA MERAK BATIN</h4>
                </div>
                <p class="opacity-75 lh-lg">
                    <?php echo !empty($kontak['alamat']) ? nl2br(htmlspecialchars($kontak['alamat'], ENT_QUOTES, 'UTF-8')) : "Alamat belum diatur."; ?>
                </p>
            </div>
            
            <div class="col-lg-3">
                <h6 class="fw-bold text-uppercase mb-4" style="letter-spacing: 2px;">Navigasi</h6>
                <ul class="list-unstyled opacity-75 d-grid gap-2">
                    <li><a href="index.php" class="text-white text-decoration-none hover-red">Beranda</a></li>
                    <li><a href="berita.php" class="text-white text-decoration-none hover-red">Berita</a></li>
                    <li><a href="layanan.php" class="text-white text-decoration-none hover-red">Info Layanan</a></li>
                    <li><a href="kontak.php" class="text-white text-decoration-none hover-red">Hubungi Kami</a></li>
                    <li><a href="galeri.php" class="text-white text-decoration-none hover-red">Galeri</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h6 class="fw-bold text-uppercase mb-4" style="letter-spacing: 2px;">Kontak Resmi</h6>
                <div class="d-grid gap-3 opacity-75">
                    <?php if (!empty($kontak['whatsapp'])): ?>
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-whatsapp fs-5 text-success"></i>
                        <span><?php echo htmlspecialchars($kontak['whatsapp'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($kontak['email'])): ?>
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-envelope fs-5 text-warning"></i>
                        <span><?php echo htmlspecialchars($kontak['email'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="mt-4 d-flex gap-3">
                    <?php if (!empty($kontak['facebook'])): ?>
                        <a href="<?php echo htmlspecialchars($kontak['facebook'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                    <?php endif; ?>
                    
                    <?php if (!empty($kontak['instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($kontak['instagram'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <hr class="my-5 opacity-25">
        <div class="text-center small opacity-50">
            <p class="mb-0">&copy; 2026 Pemerintah Desa Merak Batin. KKN UNILA.</p>
        </div>
    </div>
</footer>

<style>
    .hover-red:hover { color: var(--accent-red) !important; padding-left: 5px; transition: 0.3s; }
</style>

<script>
    // Script Scroll untuk Navbar
    window.onscroll = function() {
        let nav = document.getElementById('mainNav');
        if (nav && !nav.classList.contains('not-home')) {
            if (window.pageYOffset > 50) { 
                nav.classList.add('scrolled'); 
            } else { 
                nav.classList.remove('scrolled'); 
            }
        }
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>