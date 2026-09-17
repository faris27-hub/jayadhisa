CREATE DATABASE IF NOT EXISTS jayadhisa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jayadhisa;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS pembayaran;
DROP TABLE IF EXISTS detail_pesanan;
DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
 id_user INT AUTO_INCREMENT PRIMARY KEY,
 nama VARCHAR(100) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin','user') NOT NULL DEFAULT 'user',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE kategori (
 id_kategori INT AUTO_INCREMENT PRIMARY KEY,
 nama_kategori VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE produk (
 id_produk INT AUTO_INCREMENT PRIMARY KEY,
 nama_produk VARCHAR(150) NOT NULL,
 harga DECIMAL(14,2) NOT NULL DEFAULT 0,
 stok INT NOT NULL DEFAULT 0,
 id_kategori INT NULL,
 deskripsi TEXT,
 image_url VARCHAR(500) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_produk_kategori FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE pesanan (
 id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
 id_user INT NOT NULL,
 total DECIMAL(14,2) NOT NULL,
 alamat TEXT NOT NULL,
 status ENUM('pending','paid','processing','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_pesanan_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE detail_pesanan (
 id_detail INT AUTO_INCREMENT PRIMARY KEY,
 id_pesanan INT NOT NULL,
 id_produk INT NOT NULL,
 jumlah INT NOT NULL,
 harga DECIMAL(14,2) NOT NULL,
 CONSTRAINT fk_detail_pesanan FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
 CONSTRAINT fk_detail_produk FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
 id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
 id_pesanan INT NOT NULL UNIQUE,
 metode VARCHAR(50) NOT NULL,
 status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
 reference VARCHAR(100) NOT NULL UNIQUE,
 paid_at DATETIME NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_pembayaran_pesanan FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO kategori(nama_kategori) VALUES ('Elektronik'),('Fashion'),('Rumah Tangga'),('Aksesoris');
INSERT INTO produk(nama_produk,harga,stok,id_kategori,deskripsi,image_url) VALUES
('Wireless Headset',299000,15,1,'Headset wireless untuk kebutuhan harian.',NULL),
('Keyboard Mechanical',450000,10,1,'Keyboard mechanical untuk belajar dan bekerja.',NULL),
('Hoodie Basic',179000,20,2,'Hoodie nyaman dengan desain minimalis.',NULL),
('Botol Minum',85000,30,3,'Botol minum praktis untuk aktivitas harian.',NULL),
('Tas Selempang',129000,12,4,'Tas selempang compact untuk membawa barang penting.',NULL);

-- Admin demo: admin@jayadhisa.test / admin123
INSERT INTO users(nama,email,password,role) VALUES
('Administrator','admin@jayadhisa.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llCqX4nZJ7Wq4W5YvK9yG','admin');
