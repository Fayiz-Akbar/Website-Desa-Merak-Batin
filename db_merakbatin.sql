-- 1. Membuat Database
CREATE DATABASE IF NOT EXISTS db_merakbatin;
USE db_merakbatin;

-- 2. Tabel site_settings (Informasi Global Header/Footer)
CREATE TABLE site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul_website VARCHAR(255) DEFAULT 'Desa Merak Batin',
    tagline VARCHAR(255),
    logo VARCHAR(255),
    favicon VARCHAR(255)
);

-- 3. Tabel users (Otentikasi Admin)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100)
);

-- 4. Tabel profil (Statistik & Identitas Desa)
CREATE TABLE profil (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sejarah TEXT,
    visi TEXT,
    misi TEXT,
    populasi INT DEFAULT 0,
    luas_wilayah VARCHAR(50),
    batas_utara VARCHAR(100),
    batas_selatan VARCHAR(100),
    batas_timur VARCHAR(100),
    batas_barat VARCHAR(100),
    peta_lokasi_iframe TEXT 
);

-- 5. Tabel kategori_berita & berita
CREATE TABLE kategori_berita (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(50) NOT NULL
);

CREATE TABLE berita (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_kategori INT,
    judul VARCHAR(255) NOT NULL,
    isi_berita TEXT NOT NULL,
    gambar VARCHAR(255),
    tgl_posting TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori_berita(id) ON DELETE SET NULL
);

-- 6. Tabel perangkat_desa (Struktur Organisasi)
CREATE TABLE perangkat_desa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100) NOT NULL,
    foto VARCHAR(255),
    urutan INT DEFAULT 0 
);

-- 7. Tabel unduhan (Pusat Dokumen PDF)
CREATE TABLE unduhan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_dokumen VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    file_path VARCHAR(255) NOT NULL, 
    tgl_upload DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 8. Tabel layanan (Prosedur Administrasi)
CREATE TABLE layanan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul_layanan VARCHAR(255) NOT NULL, 
    isi_prosedur TEXT NOT NULL
);

-- 9. Tabel potensi_visual (Wadah Kerja Arsitektur & Pertanian)
CREATE TABLE potensi_visual (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul_peta VARCHAR(255) NOT NULL,
    gambar_peta VARCHAR(255) NOT NULL,
    keterangan TEXT,
    sumber ENUM('Arsitektur', 'Pertanian') NOT NULL
);

-- 10. Tabel apb_desa (Transparansi Anggaran)
CREATE TABLE apb_desa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tahun YEAR NOT NULL,
    jenis ENUM('Pendapatan', 'Belanja') NOT NULL,
    keterangan VARCHAR(255),
    jumlah BIGINT NOT NULL
);

-- 11. Tabel galeri
CREATE TABLE galeri (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul_foto VARCHAR(255),
    file_gambar VARCHAR(255) NOT NULL,
    tgl_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 12. Tabel kontak
CREATE TABLE kontak (
    id INT PRIMARY KEY AUTO_INCREMENT,
    alamat TEXT,
    telepon VARCHAR(20),
    whatsapp VARCHAR(20),
    email VARCHAR(100),
    facebook VARCHAR(255),
    instagram VARCHAR(255)
);

-- Hapus baris ini (DUPLIKAT):
-- INSERT INTO profil (id, populasi, luas_wilayah) VALUES (1, 0, '0 m2');

-- Gunakan INSERT ... ON DUPLICATE KEY UPDATE atau INSERT IGNORE
INSERT INTO profil (id, sejarah, visi, misi, populasi, luas_wilayah) 
VALUES (1, 'Sejarah desa...', 'Visi...', 'Misi...', 1890, '54.482.300 m²')
ON DUPLICATE KEY UPDATE 
    sejarah = 'Sejarah desa...', 
    visi = 'Visi...', 
    misi = 'Misi...', 
    populasi = 1890, 
    luas_wilayah = '54.482.300 m²';

ALTER TABLE apb_desa 
    MODIFY COLUMN jenis ENUM('Pendapatan', 'Belanja', 'Pembiayaan') NOT NULL,
    CHANGE COLUMN keterangan rincian VARCHAR(255),
    CHANGE COLUMN jumlah anggaran BIGINT NOT NULL,
    ADD COLUMN realisasi BIGINT NOT NULL AFTER anggaran;