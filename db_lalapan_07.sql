CREATE DATABASE IF NOT EXISTS db_lalapan_07 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_lalapan_07;

CREATE TABLE IF NOT EXISTS users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(30) NOT NULL DEFAULT 'pengelola',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS kategori (
  id_kategori INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(100) NOT NULL UNIQUE,
  keterangan VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS menu (
  id_menu INT AUTO_INCREMENT PRIMARY KEY,
  id_kategori INT NOT NULL,
  nama_menu VARCHAR(100) NOT NULL,
  gambar_menu VARCHAR(255) NULL,
  harga DECIMAL(10,2) NOT NULL,
  status_menu ENUM('Tersedia','Tidak Tersedia') NOT NULL DEFAULT 'Tersedia',
  CONSTRAINT fk_menu_kategori FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS transaksi (
  id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
  kode_transaksi VARCHAR(30) NOT NULL UNIQUE,
  id_user INT NOT NULL,
  jenis_pesanan ENUM('Makan di Tempat','Bungkus') NOT NULL DEFAULT 'Makan di Tempat',
  nama_pelanggan VARCHAR(100) NULL,
  nomor_meja VARCHAR(10) NULL,
  tanggal_transaksi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total_harga DECIMAL(10,2) NOT NULL,
  metode_pembayaran ENUM('Tunai','Transfer/QRIS') NOT NULL,
  uang_diterima DECIMAL(10,2) NOT NULL,
  kembalian DECIMAL(10,2) NOT NULL DEFAULT 0,
  CONSTRAINT fk_transaksi_user FOREIGN KEY (id_user) REFERENCES users(id_user)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS detail_transaksi (
  id_detail INT AUTO_INCREMENT PRIMARY KEY,
  id_transaksi INT NOT NULL,
  id_menu INT NOT NULL,
  varian ENUM('Dada','Paha') NULL,
  jumlah INT NOT NULL,
  harga_satuan DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  catatan VARCHAR(255) NULL,
  CONSTRAINT fk_detail_transaksi FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_detail_menu FOREIGN KEY (id_menu) REFERENCES menu(id_menu)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT IGNORE INTO kategori (nama_kategori) VALUES
('Paket Bakar'),('Paket Goreng'),('Minuman'),('Lainnya');

INSERT INTO menu (id_kategori,nama_menu,harga,gambar_menu,status_menu)
SELECT k.id_kategori, v.nama, v.harga, v.gambar, 'Tersedia'
FROM (
 SELECT 'Paket Bakar' kategori,'Paket Ayam Bakar Serundeng' nama,26000 harga,'assets/img/menu/ayam-bakar.jpg' gambar UNION ALL
 SELECT 'Paket Bakar','Paket Lele Bakar Serundeng',26000,'assets/img/menu/lele-bakar.jpg' UNION ALL
 SELECT 'Paket Bakar','Paket Nila Bakar Serundeng',27000,'assets/img/menu/nila-bakar.jpg' UNION ALL
 SELECT 'Paket Goreng','Paket Pecel Ayam Serundeng',25000,'assets/img/menu/pecel-ayam.jpg' UNION ALL
 SELECT 'Paket Goreng','Paket Pecel Lele Serundeng',25000,'assets/img/menu/pecel-lele.jpg' UNION ALL
 SELECT 'Paket Goreng','Paket Nila Goreng Serundeng',26000,'assets/img/menu/nila-goreng.jpg' UNION ALL
 SELECT 'Minuman','Es Teh Manis',5000,'assets/img/menu/es-teh.jpg' UNION ALL
 SELECT 'Minuman','Es Jeruk Kecil',5000,'assets/img/menu/es-jeruk.jpg' UNION ALL
 SELECT 'Minuman','Es Jeruk Besar',8000,'assets/img/menu/es-jeruk.jpg' UNION ALL
 SELECT 'Minuman','Air Mineral',5000,'assets/img/menu/air-mineral.jpg' UNION ALL
 SELECT 'Minuman','Es Tawar',2000,'assets/img/menu/es-tawar.jpg' UNION ALL
 SELECT 'Lainnya','Tumis Terong',10000,'assets/img/menu/terong.jpg' UNION ALL
 SELECT 'Lainnya','Terong Goreng',8000,'assets/img/menu/terong.jpg' UNION ALL
 SELECT 'Lainnya','Tumis Kangkung',8000,'assets/img/menu/kangkung.jpg' UNION ALL
 SELECT 'Lainnya','Kol Goreng',7000,'assets/img/menu/kol-goreng.jpg' UNION ALL
 SELECT 'Lainnya','Petai Goreng',6000,'assets/img/menu/petai.jpg' UNION ALL
 SELECT 'Lainnya','Ati Ampela',5000,'assets/img/menu/ati-ampela.jpg' UNION ALL
 SELECT 'Lainnya','Nasi Putih',5000,'assets/img/menu/nasi.jpg'
) v JOIN kategori k ON k.nama_kategori=v.kategori
WHERE NOT EXISTS (SELECT 1 FROM menu m WHERE m.nama_menu=v.nama);
