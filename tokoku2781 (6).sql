-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Jun 2025 pada 02.17
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tokoku2781`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin1`
--

CREATE TABLE `admin1` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin1`
--

INSERT INTO `admin1` (`id`, `nama`, `email`, `password`, `created_at`) VALUES
(1, 'raditya', 'radit@admin.com', '$2y$10$laE9End20tM8io.OVtLgQ.kkMlKlQoxCh/sMyXvPj6kLEhPuEgUeu', '2025-05-22 06:30:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Otomotif'),
(3, 'Fashion'),
(4, 'Makanan'),
(5, 'Minuman'),
(6, 'Elektronik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `id_komentar` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_admin_reply` tinyint(1) DEFAULT 0,
  `id_produk` int(11) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `komentar` text DEFAULT NULL,
  `rating` int(1) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `tanggal` datetime DEFAULT current_timestamp(),
  `id_toko` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komentar`
--

INSERT INTO `komentar` (`id_komentar`, `parent_id`, `is_admin_reply`, `id_produk`, `id_pembeli`, `komentar`, `rating`, `tanggal`, `id_toko`) VALUES
(28, NULL, 0, 28, 23122774, 'keren', 5, '2025-05-30 09:52:47', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `merek`
--

CREATE TABLE `merek` (
  `id_merek` int(11) NOT NULL,
  `nama_merek` varchar(100) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `merek`
--

INSERT INTO `merek` (`id_merek`, `nama_merek`, `gambar`) VALUES
(23, 'APPLE', 'APPLE.png'),
(24, 'ZARA', 'ZARA.png'),
(25, 'COSRX', 'COSRX.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id_order` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `id_pembeli` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembeli`
--

CREATE TABLE `pembeli` (
  `id_pembeli` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `alamat` varchar(255) DEFAULT NULL,
  `nomor_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembeli`
--

INSERT INTO `pembeli` (`id_pembeli`, `nama`, `email`, `password`, `status`, `alamat`, `nomor_hp`) VALUES
(23122770, 'Pembeli', 'pembeli@gmail.com', '$2y$10$s03CUkD5vizp3.HQXInlxu3XzAbGU0PneOGxNOLcP7zCizFXtMZHO', 'aktif', NULL, NULL),
(23122774, 'Radit', 'radityanaufal2005@gmail.com', '$2y$10$3j1R.SOQDqpTdp3PjO6wceExhjmNAwTYvzysJnqGfcc9yaBxsQpq.', 'aktif', 'Jl. Raya Piyungan - Prambanan No.Km. 4.5, Majesem, Madurejo, Kec. Prambanan, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55572', '081187363789'),
(23122775, 'Raditya N', 'raditinaja@gmail.com', '$2y$10$orafjCTLYbNJw0oll.NKce0fmB6ow8kkTBncmPDnvQthws3709uCu', 'aktif', 'jl prambanan piyungan', '0274553783'),
(23122776, 'naufal', 'naufalaja@gmail.com', '$2y$10$fExqKLrNRDznVccIz3XNHugWigCLWc9Ed6T6LflBhGLvRAT1KfPsK', 'aktif', 'Jl. Raya Piyungan - Prambanan No.Km. 4.5, Majesem, Madurejo, Kec. Prambanan, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55572', '0997654778');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertanyaan_produk`
--

CREATE TABLE `pertanyaan_produk` (
  `id` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `nama_penanya` varchar(100) NOT NULL,
  `pertanyaan` text NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `pembeli_id` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `kurir` varchar(50) DEFAULT NULL,
  `status` enum('pending','diproses','dikirim','selesai','batal') DEFAULT 'pending',
  `tanggal_pembelian` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `pembeli_id`, `total_harga`, `metode_pembayaran`, `kurir`, `status`, `tanggal_pembelian`, `created_at`) VALUES
(1, 23122774, 16499999.00, NULL, NULL, 'pending', '2025-06-03', '2025-06-03 15:42:14'),
(2, 23122774, 16499999.00, NULL, NULL, 'pending', '2025-06-03', '2025-06-03 15:56:30'),
(3, 23122774, 16499999.00, NULL, NULL, 'dikirim', '2025-06-03', '2025-06-03 16:02:52'),
(4, 23122774, 32999998.00, NULL, NULL, 'diproses', '2025-06-03', '2025-06-03 16:23:57'),
(5, 23122774, 16543199.00, 'mandiri', 'standard', 'dikirim', '2025-06-04', '2025-06-04 04:22:25'),
(6, 23122774, 16543199.00, 'mandiri', 'standard', 'diproses', '2025-06-04', '2025-06-04 04:24:56'),
(7, 23122774, 16532799.00, 'mandiri', 'ekonomi', 'pending', '2025-06-04', '2025-06-04 04:45:43'),
(8, 23122774, 33043198.00, 'mandiri', 'standard', 'pending', '2025-06-04', '2025-06-04 08:20:18'),
(9, 23122774, 33043198.00, 'mandiri', 'standard', 'pending', '2025-06-04', '2025-06-04 12:10:34'),
(10, 23122774, 33032798.00, 'bca', 'ekonomi', 'selesai', '2025-06-04', '2025-06-04 13:29:32'),
(11, 23122774, 669200.00, 'bri', 'reguler', 'pending', '2025-06-04', '2025-06-04 13:43:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan_detail`
--

CREATE TABLE `pesanan_detail` (
  `id` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan_detail`
--

INSERT INTO `pesanan_detail` (`id`, `id_pesanan`, `produk_id`, `jumlah`, `harga`) VALUES
(1, 1, 28, 1, 16499999.00),
(2, 2, 28, 1, 16499999.00),
(3, 3, 28, 1, 16499999.00),
(4, 4, 28, 2, 16499999.00),
(5, 5, 28, 1, 16499999.00),
(6, 6, 28, 1, 16499999.00),
(7, 7, 28, 1, 16499999.00),
(8, 8, 28, 2, 16499999.00),
(9, 9, 28, 2, 16499999.00),
(10, 10, 28, 2, 16499999.00),
(11, 11, 29, 12, 52500.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_toko` int(11) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_merek` int(11) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(12,2) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `dilihat` int(11) DEFAULT 0,
  `berat` decimal(5,2) NOT NULL DEFAULT 1.00 COMMENT 'Berat produk dalam kg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `id_toko`, `id_kategori`, `id_merek`, `nama_produk`, `deskripsi`, `harga`, `stok`, `foto`, `dilihat`, `berat`) VALUES
(28, 17, 6, 23, 'iPhone 16 256gb', 'qfe', 16499999.00, 87, '62fa65abcc278dfac90c4d299f599206.png', 64, 0.50),
(29, 17, 3, 25, 'COSRX Low pH Good Morning Gel Cleanser 50ml Sabun Pembersih Wajah untuk Semua Jenis Kulit', 'VI', 52500.00, 88, '1d3161a22b5e383af205042831768aca.png', 2, 1.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `promo`
--

CREATE TABLE `promo` (
  `id_promo` int(11) NOT NULL,
  `nama_promo` varchar(100) NOT NULL,
  `poster` varchar(255) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `promo`
--

INSERT INTO `promo` (`id_promo`, `nama_promo`, `poster`, `status`, `link`) VALUES
(2, 'Promo 1', 'WhatsApp_Image_2025-04-23_at_23_15_54_0ad1f06b.jpg', 'aktif', '#'),
(4, 'Promo 2', 'WhatsApp_Image_2025-04-23_at_23_15_54_0ad1f06b1.jpg', 'nonaktif', '#'),
(5, 'Promo 3', 'WhatsApp_Image_2025-04-23_at_23_15_54_0ad1f06b2.jpg', 'nonaktif', '#'),
(10, 'Promo 4', 'WhatsApp_Image_2025-04-23_at_23_15_54_0ad1f06b3.jpg', 'nonaktif', '#'),
(19, 'Iphone 16', '1726455284_maxresdefault_(1).jpg', 'aktif', '#'),
(20, 'promo 6', '20240227-h-n-m-bann.jpg', 'aktif', '#'),
(22, 'promo 7', 'Edit-by-Sociolla-Spesial-Promo-di-Shopee-Finest-Brand-Day.jpg', 'aktif', '#');

-- --------------------------------------------------------

--
-- Struktur dari tabel `toko`
--

CREATE TABLE `toko` (
  `id_toko` int(11) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `nama_toko` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `logo_toko` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `toko`
--

INSERT INTO `toko` (`id_toko`, `id_pembeli`, `nama_toko`, `deskripsi`, `logo_toko`) VALUES
(17, 23122774, 'Rads Group', 'Siap memenuhi segala kebutuhan anda', 'ttoko.png'),
(18, 23122774, 'mcd', 'coba aja', 'mcd1.png'),
(19, 23122776, 'mcd', 'rgs', 'ttoko1.png');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin1`
--
ALTER TABLE `admin1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `fk_keranjang_pembeli` (`id_pembeli`),
  ADD KEY `fk_keranjang_produk` (`id_produk`);

--
-- Indeks untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id_komentar`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_pembeli` (`id_pembeli`),
  ADD KEY `id_toko` (`id_toko`);

--
-- Indeks untuk tabel `merek`
--
ALTER TABLE `merek`
  ADD PRIMARY KEY (`id_merek`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `pembeli`
--
ALTER TABLE `pembeli`
  ADD PRIMARY KEY (`id_pembeli`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `pertanyaan_produk`
--
ALTER TABLE `pertanyaan_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `pembeli_id` (`pembeli_id`);

--
-- Indeks untuk tabel `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`id_pesanan`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_toko` (`id_toko`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_merek` (`id_merek`);

--
-- Indeks untuk tabel `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id_promo`);

--
-- Indeks untuk tabel `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`id_toko`),
  ADD KEY `id_pembeli` (`id_pembeli`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin1`
--
ALTER TABLE `admin1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id_komentar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `merek`
--
ALTER TABLE `merek`
  MODIFY `id_merek` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pembeli`
--
ALTER TABLE `pembeli`
  MODIFY `id_pembeli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23122777;

--
-- AUTO_INCREMENT untuk tabel `pertanyaan_produk`
--
ALTER TABLE `pertanyaan_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `promo`
--
ALTER TABLE `promo`
  MODIFY `id_promo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `toko`
--
ALTER TABLE `toko`
  MODIFY `id_toko` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `fk_keranjang_pembeli` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_keranjang_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `komentar_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE,
  ADD CONSTRAINT `komentar_ibfk_2` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE,
  ADD CONSTRAINT `komentar_ibfk_3` FOREIGN KEY (`id_toko`) REFERENCES `toko` (`id_toko`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`pembeli_id`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_toko`) REFERENCES `toko` (`id_toko`),
  ADD CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `produk_ibfk_3` FOREIGN KEY (`id_merek`) REFERENCES `merek` (`id_merek`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
