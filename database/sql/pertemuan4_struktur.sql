-- 1. Membuat basis data jika belum ada
CREATE DATABASE IF NOT EXISTS praktikum_web_2401020060
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- 2. Memilih basis data yang akan digunakan
USE praktikum_web_2401020060;

-- 3. Membuat tabel program_studi (Tabel Induk)
CREATE TABLE program_studi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_prodi VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 4. Membuat tabel mahasiswa (Tabel Anak dengan Relasi & Batasan)
CREATE TABLE mahasiswa (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nim CHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    usia TINYINT UNSIGNED NOT NULL,
    program_studi_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Key: Merujuk ke program_studi(id)
    CONSTRAINT fk_mahasiswa_program_studi
        FOREIGN KEY (program_studi_id)
        REFERENCES program_studi(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
        
    -- Check Constraint: Membatasi rentang usia 17 - 60 tahun
    CONSTRAINT chk_usia
        CHECK (usia BETWEEN 17 AND 60)
) ENGINE=InnoDB;