-- DROP SEMUA TABEL (Jalankan ini dulu untuk reset database)
DROP TABLE IF EXISTS unduhan;
DROP TABLE IF EXISTS kategori_unduhan;
DROP TABLE IF EXISTS potensi_desa;
DROP TABLE IF EXISTS kategori_potensi;
DROP TABLE IF EXISTS berita;
DROP TABLE IF EXISTS kategori_berita;
DROP TABLE IF EXISTS galeri;
DROP TABLE IF EXISTS apb_desa;
DROP TABLE IF EXISTS potensi_visual;
DROP TABLE IF EXISTS layanan;
DROP TABLE IF EXISTS perangkat_desa;
DROP TABLE IF EXISTS kontak;
DROP TABLE IF EXISTS profil;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS site_settings;

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

-- 7. Tabel layanan (Prosedur Administrasi)
CREATE TABLE layanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_layanan VARCHAR(255) NOT NULL,
    persyaratan TEXT,
    prosedur TEXT,
    biaya VARCHAR(100) DEFAULT 'Gratis',
    estimasi_waktu VARCHAR(100)
);

-- 8. Tabel potensi_visual (Wadah Kerja Arsitektur & Pertanian)
CREATE TABLE potensi_visual (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul_peta VARCHAR(255) NOT NULL,
    gambar_peta VARCHAR(255) NOT NULL,
    keterangan TEXT,
    sumber ENUM('Arsitektur', 'Pertanian') NOT NULL
);

-- 9. Tabel apb_desa (Transparansi Anggaran)
CREATE TABLE apb_desa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tahun YEAR NOT NULL,
    jenis ENUM('Pendapatan', 'Belanja', 'Pembiayaan') NOT NULL,
    rincian VARCHAR(255),
    anggaran BIGINT NOT NULL,
    realisasi BIGINT NOT NULL
);


-- 11. Tabel kategori_potensi & potensi_desa
CREATE TABLE kategori_potensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

CREATE TABLE potensi_desa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_potensi VARCHAR(255) NOT NULL,
    id_kategori INT,
    deskripsi TEXT,
    gambar VARCHAR(255),
    lokasi_maps TEXT,
    tgl_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori_potensi(id) ON DELETE SET NULL
);

-- 12. Tabel kategori_unduhan & unduhan
CREATE TABLE kategori_unduhan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

CREATE TABLE unduhan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_dokumen VARCHAR(255) NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    id_kategori INT,
    tgl_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori_unduhan(id) ON DELETE SET NULL
);

-- 13. Tabel kontak (PERBAIKAN - TAMBAHKAN maps_embed)
CREATE TABLE kontak (
    id INT PRIMARY KEY DEFAULT 1,
    alamat TEXT,
    telepon VARCHAR(20),
    email VARCHAR(100),
    whatsapp VARCHAR(20),
    facebook VARCHAR(255),
    instagram VARCHAR(255),
    maps_embed TEXT
);

-- Insert data default kontak
INSERT INTO kontak (id, alamat, telepon, email, whatsapp, facebook, instagram, maps_embed) 
VALUES (1, 'Jl. Raya Merak Batin No. 1, Natar, Lampung Selatan', '08123456789', 'desa@merakbatin.id', '-', '-', '-', '-')
ON DUPLICATE KEY UPDATE 
    alamat = VALUES(alamat),
    telepon = VALUES(telepon),
    email = VALUES(email);

-- Insert data default profil
INSERT INTO profil (id, sejarah, visi, misi, populasi, luas_wilayah) 
VALUES (1, 'Sejarah desa...', 'Visi...', 'Misi...', 1890, '54.482.300 m²')
ON DUPLICATE KEY UPDATE 
    sejarah = VALUES(sejarah), 
    visi = VALUES(visi), 
    misi = VALUES(misi), 
    populasi = VALUES(populasi), 
    luas_wilayah = VALUES(luas_wilayah);

-- Insert default user admin
INSERT INTO users (username, password, nama_lengkap) 
VALUES ('admin', MD5('admin123'), 'Administrator')
ON DUPLICATE KEY UPDATE username = VALUES(username);

-- 1. Reset Tabel Profil
DROP TABLE IF EXISTS profil;
CREATE TABLE profil (
    id INT PRIMARY KEY DEFAULT 1,
    populasi INT DEFAULT 0,
    luas_wilayah VARCHAR(100) DEFAULT '0 km²',
    logo VARCHAR(255),
    foto_kepala_desa VARCHAR(255),
    nama_kepala_desa VARCHAR(255),
    sambutan TEXT,
    visi TEXT,
    misi TEXT,
    sejarah TEXT
);

-- Masukkan data awal agar tidak error "Null" saat pertama kali dibuka
INSERT INTO profil (id, nama_kepala_desa, sambutan, sejarah) 
VALUES (1, 'Nama Kepala Desa', 'Selamat datang di portal resmi kami...', 'Sejarah singkat desa...');

-- 2. Reset Tabel Galeri
DROP TABLE IF EXISTS galeri;
CREATE TABLE galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255),
    foto VARCHAR(255),
    tgl_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Reset Tabel Kontak (Agar sinkron dan tidak error maps_embed)
DROP TABLE IF EXISTS kontak;
CREATE TABLE kontak (
    id INT PRIMARY KEY DEFAULT 1,
    alamat TEXT,
    telepon VARCHAR(20),
    email VARCHAR(100),
    whatsapp VARCHAR(20),
    facebook VARCHAR(255),
    instagram VARCHAR(255),
    maps_embed TEXT
);

-- Masukkan data awal kontak
INSERT INTO kontak (id, alamat) VALUES (1, 'Jl. Raya Merak Batin, Natar, Lampung Selatan');