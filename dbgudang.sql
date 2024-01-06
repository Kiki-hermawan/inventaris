-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2024 at 06:14 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbgudang`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `brgkode` char(10) NOT NULL,
  `brgnama` varchar(100) NOT NULL,
  `brgkatid` int(10) UNSIGNED NOT NULL,
  `brgsatid` int(10) UNSIGNED NOT NULL,
  `brgharga` double NOT NULL,
  `brggambar` varchar(200) NOT NULL,
  `brgstok` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`brgkode`, `brgnama`, `brgkatid`, `brgsatid`, `brgharga`, `brggambar`, `brgstok`) VALUES
('P-001', 'Kemaja Flanel', 2, 1, 80000, '', '16'),
('S-001', 'Compass Gazelle', 1, 1, 250000, '', '8');

-- --------------------------------------------------------

--
-- Table structure for table `barangkeluar`
--

CREATE TABLE `barangkeluar` (
  `faktur` char(20) NOT NULL,
  `tglfaktur` date DEFAULT NULL,
  `idpel` int(11) DEFAULT NULL,
  `totalharga` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barangmasuk`
--

CREATE TABLE `barangmasuk` (
  `faktur` char(20) NOT NULL,
  `tglfaktur` date DEFAULT NULL,
  `totalharga` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_barangkeluar`
--

CREATE TABLE `detail_barangkeluar` (
  `id` bigint(20) NOT NULL,
  `detfaktur` char(20) DEFAULT NULL,
  `detbrgkode` char(10) DEFAULT NULL,
  `dethargajual` double DEFAULT NULL,
  `detjml` int(11) DEFAULT NULL,
  `detsubtotal` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_barangmasuk`
--

CREATE TABLE `detail_barangmasuk` (
  `iddetail` bigint(20) NOT NULL,
  `detfaktur` char(20) DEFAULT NULL,
  `detbrgkode` char(10) DEFAULT NULL,
  `dethargamasuk` double DEFAULT NULL,
  `dethargajual` double DEFAULT NULL,
  `detjml` int(11) DEFAULT NULL,
  `detsubtotal` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Triggers `detail_barangmasuk`
--
DELIMITER $$
CREATE TRIGGER `tri_kurangi_stok_barang` AFTER DELETE ON `detail_barangmasuk` FOR EACH ROW BEGIN
	UPDATE barang SET brgstok = brgstok - old.detjml WHERE brgkode = old.detbrgkode;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tri_tambah_stok_barang` AFTER INSERT ON `detail_barangmasuk` FOR EACH ROW BEGIN
	UPDATE barang SET barang.brgstok = barang.brgstok + new.detjml WHERE barang.brgkode = new.detbrgkode;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tri_update_stok_barang` AFTER UPDATE ON `detail_barangmasuk` FOR EACH ROW BEGIN
	UPDATE barang SET brgstok = (brgstok - old.detjml) + new.detjml WHERE brgkode = new.detbrgkode;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `katid` int(10) UNSIGNED NOT NULL,
  `katnama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`katid`, `katnama`) VALUES
(1, 'Sepatu'),
(2, 'Pakaian'),
(3, 'Elektronik'),
(4, 'Makanan'),
(5, 'Minuman'),
(6, 'Pakaian Olahraga');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2024-01-04-084756', 'App\\Database\\Migrations\\Kategori', 'default', 'App', 1704358219, 1),
(2, '2024-01-04-084805', 'App\\Database\\Migrations\\Satuan', 'default', 'App', 1704358219, 1),
(3, '2024-01-04-084810', 'App\\Database\\Migrations\\Barang', 'default', 'App', 1704358219, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `pelid` int(11) NOT NULL,
  `pelnama` varchar(100) DEFAULT NULL,
  `peltelp` char(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `satid` int(10) UNSIGNED NOT NULL,
  `satnama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`satid`, `satnama`) VALUES
(1, 'Pcs'),
(2, 'Pak'),
(3, 'Box'),
(4, 'Lusin');

-- --------------------------------------------------------

--
-- Table structure for table `temp_barangmasuk`
--

CREATE TABLE `temp_barangmasuk` (
  `iddetail` bigint(20) NOT NULL,
  `detfaktur` char(20) DEFAULT NULL,
  `detbrgkode` char(10) DEFAULT NULL,
  `dethargamasuk` double DEFAULT NULL,
  `dethargajual` double DEFAULT NULL,
  `detjml` int(11) DEFAULT NULL,
  `detsubtotal` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`brgkode`),
  ADD KEY `barang_brgkatid_foreign` (`brgkatid`),
  ADD KEY `barang_brgsatid_foreign` (`brgsatid`);

--
-- Indexes for table `barangkeluar`
--
ALTER TABLE `barangkeluar`
  ADD PRIMARY KEY (`faktur`),
  ADD KEY `idpel` (`idpel`);

--
-- Indexes for table `barangmasuk`
--
ALTER TABLE `barangmasuk`
  ADD PRIMARY KEY (`faktur`);

--
-- Indexes for table `detail_barangkeluar`
--
ALTER TABLE `detail_barangkeluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detbrgkode` (`detbrgkode`),
  ADD KEY `detfaktur` (`detfaktur`);

--
-- Indexes for table `detail_barangmasuk`
--
ALTER TABLE `detail_barangmasuk`
  ADD PRIMARY KEY (`iddetail`),
  ADD KEY `detbrgkode` (`detbrgkode`),
  ADD KEY `detfaktur` (`detfaktur`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD KEY `katid` (`katid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`pelid`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD KEY `satid` (`satid`);

--
-- Indexes for table `temp_barangmasuk`
--
ALTER TABLE `temp_barangmasuk`
  ADD PRIMARY KEY (`iddetail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_barangkeluar`
--
ALTER TABLE `detail_barangkeluar`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_barangmasuk`
--
ALTER TABLE `detail_barangmasuk`
  MODIFY `iddetail` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `katid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `pelid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `satid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `temp_barangmasuk`
--
ALTER TABLE `temp_barangmasuk`
  MODIFY `iddetail` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_brgkatid_foreign` FOREIGN KEY (`brgkatid`) REFERENCES `kategori` (`katid`),
  ADD CONSTRAINT `barang_brgsatid_foreign` FOREIGN KEY (`brgsatid`) REFERENCES `satuan` (`satid`);

--
-- Constraints for table `barangkeluar`
--
ALTER TABLE `barangkeluar`
  ADD CONSTRAINT `barangkeluar_ibfk_1` FOREIGN KEY (`idpel`) REFERENCES `pelanggan` (`pelid`);

--
-- Constraints for table `detail_barangkeluar`
--
ALTER TABLE `detail_barangkeluar`
  ADD CONSTRAINT `detail_barangkeluar_ibfk_1` FOREIGN KEY (`detbrgkode`) REFERENCES `barang` (`brgkode`),
  ADD CONSTRAINT `detail_barangkeluar_ibfk_2` FOREIGN KEY (`detfaktur`) REFERENCES `barangkeluar` (`faktur`);

--
-- Constraints for table `detail_barangmasuk`
--
ALTER TABLE `detail_barangmasuk`
  ADD CONSTRAINT `detail_barangmasuk_ibfk_1` FOREIGN KEY (`detbrgkode`) REFERENCES `barang` (`brgkode`),
  ADD CONSTRAINT `detail_barangmasuk_ibfk_2` FOREIGN KEY (`detfaktur`) REFERENCES `barangmasuk` (`faktur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
