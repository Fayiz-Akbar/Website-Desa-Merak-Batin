<?php include 'includes/header.php'; ?>

<?php
// Ambil ID berita dari URL
$id_berita = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query berita berdasarkan ID dengan JOIN kategori
$query = mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b LEFT JOIN kategori_berita k ON b.id_kategori = k.id WHERE b.id = $id_berita");
$berita = mysqli_fetch_assoc($query);

// Jika berita tidak ditemukan, redirect ke halaman berita
if (!$berita) {
    header('Location: berita.php');
    exit;
}
?>

<!-- Hero Section -->
<section class="position-relative text-white" style="padding-top: 140px; padding-bottom: 60px; background: linear-gradient(135deg, #31364b 0%, #121212 100%);">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="mb-3">
                    <a href="berita.php" class="btn btn-light btn-sm rounded-pill px-3 mb-3">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Berita
                    </a>
                </div>
                <div class="mb-3">
                    <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(255,255,255,0.3); font-size: 0.9rem;">
                        <i class="bi bi-calendar3 me-2"></i><?php echo date('d F Y', strtotime($berita['tgl_posting'])); ?>
                    </span>
                    <span class="badge rounded-pill px-3 py-2 ms-2" style="background-color: rgba(255,255,255,0.3); font-size: 0.9rem;">
                        <i class="bi bi-person-circle me-2"></i>Admin Desa
                    </span>
                </div>
                <h1 class="display-4 fw-bold mb-0"><?php echo $berita['judul']; ?></h1>
            </div>
        </div>
    </div>
</section>

<!-- Konten Berita -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Main Content -->
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <!-- Featured Image -->
                    <div class="position-relative" style="height: 500px; overflow: hidden;">
                        <img src="assets/img/berita/<?php echo $berita['gambar']; ?>" 
                             class="w-100 h-100" 
                             style="object-fit: cover;" 
                             alt="<?php echo $berita['judul']; ?>">
                    </div>
                    
                    <!-- Content -->
                    <div class="card-body p-5">
                        <!-- Info Bar -->
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-4 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                    <i class="bi bi-person-circle fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Ditulis oleh</small>
                                    <strong>Admin Desa Merak Batin</strong>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">Dipublikasikan</small>
                                <strong><?php echo date('d M Y', strtotime($berita['tgl_posting'])); ?></strong>
                            </div>
                        </div>

                        <!-- Article Content -->
                        <div class="article-content lh-lg" style="font-size: 1.05rem; color: #4a5568;">
                            <?php 
                            // Ambil isi berita dari kolom isi_berita
                            $isi_berita = $berita['isi_berita'] ?? $berita['isi'] ?? '';
                            echo $isi_berita; 
                            ?>
                        </div>

                        <!-- Tags/Kategori -->
                        <div class="mt-5 pt-4 border-top">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <strong class="me-2"><i class="bi bi-bookmark me-2"></i>Kategori:</strong>
                                <span class="badge bg-primary px-3 py-2"><?php echo $berita['nama_kategori'] ?? 'Umum'; ?></span>
                            </div>
                        </div>

                        <!-- Share Buttons -->
                        <div class="mt-4 pt-4 border-top">
                            <strong class="d-block mb-3"><i class="bi bi-share me-2"></i>Bagikan:</strong>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>" 
                                   target="_blank" 
                                   class="btn btn-primary rounded-circle" 
                                   style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($berita['judul']); ?>" 
                                   target="_blank" 
                                   class="btn btn-info text-white rounded-circle" 
                                   style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="https://wa.me/?text=<?php echo urlencode($berita['judul'] . ' http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>" 
                                   target="_blank" 
                                   class="btn btn-success rounded-circle" 
                                   style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.article-content {
    text-align: justify;
}

.article-content p {
    margin-bottom: 1.5rem;
    line-height: 1.8;
}

.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1.5rem 0;
}

.article-content ul, .article-content ol {
    margin: 1.5rem 0;
    padding-left: 2rem;
}

.article-content h2, .article-content h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
}
</style>

<?php include 'includes/footer.php'; ?>