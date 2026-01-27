-- 1. HAPUS SEMUA TABEL LAMA (Agar bersih)
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

-- 2. TABEL PENDUKUNG (Tanpa Foreign Key)
CREATE TABLE site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul_website VARCHAR(255) DEFAULT 'Desa Merak Batin',
    tagline VARCHAR(255),
    logo VARCHAR(255),
    favicon VARCHAR(255)
);

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100)
);

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

CREATE TABLE kategori_berita (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(50) NOT NULL
);

CREATE TABLE kategori_potensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

CREATE TABLE kategori_unduhan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

-- 3. TABEL UTAMA (Dengan Foreign Key)
CREATE TABLE berita (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_kategori INT,
    judul VARCHAR(255) NOT NULL,
    ringkasan TEXT NULL,
    isi_berita TEXT NOT NULL,
    gambar VARCHAR(255),
    tgl_posting TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori_berita(id) ON DELETE SET NULL
);

CREATE TABLE perangkat_desa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100) NOT NULL,
    foto VARCHAR(255),
    urutan INT DEFAULT 0 
);

CREATE TABLE layanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_layanan VARCHAR(255) NOT NULL,
    persyaratan TEXT,
    prosedur TEXT,
    biaya VARCHAR(100) DEFAULT 'Gratis',
    estimasi_waktu VARCHAR(100)
);

CREATE TABLE apb_desa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tahun YEAR NOT NULL,
    jenis ENUM('Pendapatan', 'Belanja', 'Pembiayaan') NOT NULL,
    rincian VARCHAR(255),
    anggaran BIGINT NOT NULL,
    realisasi BIGINT NOT NULL
);

CREATE TABLE potensi_desa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    id_kategori INT,
    deskripsi TEXT,
    foto VARCHAR(255),
    tgl_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori_potensi(id) ON DELETE SET NULL
);

CREATE TABLE unduhan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_dokumen VARCHAR(255) NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    id_kategori INT,
    tgl_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori_unduhan(id) ON DELETE SET NULL
);

CREATE TABLE galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255),
    foto VARCHAR(255),
    tgl_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. INSERT DATA AWAL (DEFAULT)
INSERT INTO profil (id, nama_kepala_desa, sambutan, sejarah, visi, misi) 
VALUES (1, 'Nama Kepala Desa', 'Selamat datang...', 'Sejarah desa...', 'Visi...', 'Misi...');

INSERT INTO kontak (id, alamat, email) 
VALUES (1, 'Jl. Raya Merak Batin, Natar, Lampung Selatan', 'desa@merakbatin.id');

INSERT INTO users (username, password, nama_lengkap) 
VALUES ('admin', MD5('admin123'), 'Administrator');