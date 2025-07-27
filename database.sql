CREATE DATABASE IF NOT EXISTS perpustakaan_digital;
USE perpustakaan_digital;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    penulis VARCHAR(150) NOT NULL,
    penerbit VARCHAR(150) NOT NULL,
    tahun_terbit INT NOT NULL,
    isbn VARCHAR(20) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    sinopsis TEXT,
    stok INT NOT NULL DEFAULT 0,
    cover VARCHAR(255) DEFAULT 'default_cover.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, nama_lengkap, email, role) 
VALUES ('admin', '$2y$10$C2yrQhNAsuxwQqyck1mxQucJhBpGZyEjyI1WtpMQcnYgxHOAMcjaG', 'Administrator', 'admin@perpus.com', 'admin');
-- Password: testadmin