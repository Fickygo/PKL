-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 20 Bulan Mei 2025 pada 04.29
-- Versi server: 10.4.20-MariaDB
-- Versi PHP: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pdp_printing`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `username` varchar(35) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `history`
--

CREATE TABLE `history` (
  `tgl_pesan` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idproduk` varchar(20) NOT NULL,
  `nama_pengguna` varchar(20) NOT NULL,
  `jumlah_pesanan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `history`
--

INSERT INTO `history` (`tgl_pesan`, `idproduk`, `nama_pengguna`, `jumlah_pesanan`) VALUES
('2025-04-21 12:26:18', 'kalender3', 'Bapak', 2),
('2025-04-21 12:27:06', 'banner5', 'M. Ficky Zulfikar', 3),
('2025-04-21 12:27:34', 'kalender3', 'M. Ficky Zulfikar', 3),
('2025-04-21 12:28:50', 'banner5', 'M. Ficky Zulfikar', 3),
('2025-04-22 11:26:22', 'kalender1', 'M. Ficky Zulfikar', 2),
('2025-04-22 11:28:53', 'kalender3', 'M. Ficky Zulfikar', 3),
('2025-04-22 13:23:46', 'stiker1', 'M. Ficky Zulfikar', 2),
('2025-04-22 13:37:19', 'banner5', 'M. Ficky Zulfikar', 2),
('2025-04-24 13:31:09', 'banner5', 'M. Ficky Zulfikar', 2),
('2025-04-28 01:47:08', 'banner1', 'M. Ficky Zulfikar', 4),
('2025-05-09 23:08:48', 'banner1', 'M. Ficky Zulfikar', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `no_telp` varchar(15) NOT NULL,
  `nama_pengguna` varchar(50) NOT NULL,
  `maps` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`tanggal`, `no_telp`, `nama_pengguna`, `maps`) VALUES
('2025-04-25 06:05:06', '082133435726', 'M. Ficky Zulfikar', 'https://maps.app.goo.gl/bLc7Lco4YQRQ4RhF7'),
('2025-05-08 22:34:57', '082314818182', 'Bapak', 'https://maps.app.goo.gl/bLc7Lco4YQRQ4RhF7'),
('2025-05-10 03:00:31', '08123456789', 'Saya', 'https://maps.app.goo.gl/bLc7Lco4YQRQ4RhF7');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `tgl_pesan` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nama_pengguna` varchar(50) NOT NULL,
  `idproduk` varchar(20) NOT NULL,
  `jumlah_pesanan` int(11) NOT NULL,
  `metode_pengiriman` enum('Ambil di Tempat','Kirim ke Lokasi') NOT NULL,
  `file` varchar(255) NOT NULL COMMENT 'Stores the uploaded file path	',
  `pesan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`tgl_pesan`, `nama_pengguna`, `idproduk`, `jumlah_pesanan`, `metode_pengiriman`, `file`, `pesan`) VALUES
('2025-05-10 03:03:00', 'Saya', 'banner5', 2, 'Kirim ke Lokasi', 'Index/uploads/Saya/1_pdp_mohon-Photoroom.jpg', 'bisa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE `product` (
  `idproduk` varchar(20) NOT NULL,
  `nama_produk` varchar(50) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `ukuran` varchar(50) NOT NULL,
  `harga` int(12) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `product`
--

INSERT INTO `product` (`idproduk`, `nama_produk`, `jenis`, `deskripsi`, `ukuran`, `harga`) VALUES
('aksesoris1', 'Aksesoris', 'Pin', 'Pin custom dengan desain sesuai keinginan. Cocok untuk merchandise atau identitas organisasi.', '10 cm', 10000),
('aksesoris2', 'Aksesoris', 'Gantungan kunci', 'Gantungan kunci custom dengan berbagai pilihan bahan dan desain. Cocok untuk souvenir atau merchandise.', '5 cm', 7000),
('banner1', 'Banner', 'Biasa', 'Banner dengan bahan berkualitas dan print resolusi tinggi. Tersedia berbagai ukuran dan material sesuai kebutuhan Anda.', 'Besar (Persegi 1 meter)', 17000),
('banner2', 'Banner', 'Biasa', '', 'Kecil (Persegi 50 cm)', 10000),
('banner3', 'Banner', 'X-banner', '', '60x160 cm', 0),
('banner4', 'Banner', 'Y-banner', '', '60x160 cm', 0),
('banner5', 'Banner', 'Roll banner', '', '80x200 cm', 50000),
('brosur1', 'Brosur', 'BIasa', 'Brosur berkualitas tinggi dengan berbagai pilihan kertas dan finishing. Cocok untuk promosi bisnis, event, atau kampanye marketing.', 'A4', 30000),
('buku1', 'Buku', 'Biasa', 'Cetak buku dengan kualitas premium. Tersedia berbagai pilihan kertas dan jilid sesuai kebutuhan.', 'A5', 45000),
('buku2', 'Buku', 'Nota', 'Nota dengan desain profesional dan kustomisasi sesuai brand bisnis Anda. Tersedia dalam berbagai ukuran.', '10 x 16 cm', 20000),
('kalender1', 'Kalender', 'Dinding', 'Kalender custom dengan desain eksklusif. Tersedia dalam berbagai format dan ukuran.', 'A3', 20000),
('kalender2', 'Kalender', 'Meja', 'Kalender custom dengan desain eksklusif. Tersedia dalam berbagai format dan ukuran.', 'Landscape 7 Lembar 500xp', 24200),
('kalender3', 'Kalender', 'Meja', '', 'Landscape 7 Lembar 1000xp', 22000),
('kalender4', 'Kalender', 'Meja', '', 'Potrait 7 Lembar 500xp', 23100),
('kalender5', 'Kalender', 'Meja', '', 'Potrait 7 Lembar 1000exp', 20900),
('kalender6', 'Kalender', 'Meja', '', 'Landscape 13 Lembar 500xp', 32450),
('kalender7', 'Kalender', 'Meja', '', 'Landscape 13 Lembar 1000xp', 28600),
('kalender8', 'Kalender', 'Meja', '', 'Potrait 13 Lembar 500exp', 29700),
('kalender9', 'Kalender', 'Meja', '', 'Potrait 13 Lembar 1000exp', 25300),
('mug1', 'Mug', 'Kaca', 'Mug custom dengan printing kualitas tinggi dan tahan lama. Ideal untuk merchandise atau hadiah.', 'P 8cm, T 9,5cm', 35000),
('pamflet1', 'Pamflet', '', '', 'A4', 0),
('stiker1', 'Stiker', 'Biasa', '', '50mm', 5000),
('stiker2', 'Stiker', 'OneWay', '', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`tgl_pesan`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`tanggal`) USING BTREE;

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`tgl_pesan`) USING BTREE;

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`idproduk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
