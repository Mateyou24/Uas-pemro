-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2026 at 09:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boardgame_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `isi` text NOT NULL,
  `gambar` varchar(255) DEFAULT 'default-art.jpg',
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `board_games`
--

CREATE TABLE `board_games` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `genre` varchar(50) NOT NULL,
  `pemain` varchar(20) NOT NULL,
  `durasi` varchar(20) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `board_games`
--

INSERT INTO `board_games` (`id`, `nama`, `genre`, `pemain`, `durasi`, `deskripsi`, `gambar`, `created_at`) VALUES
(2, 'Unmatched', 'Strategi, Kartu, Pertarungan', '2-4 Players', '30-60 min', 'Unmatched adalah permainan pertarungan taktis yang mempertemukan karakter-karakter legendaris dari berbagai cerita, mitologi, sejarah, dan budaya populer. Setiap karakter memiliki kemampuan unik serta dek kartu khusus yang menciptakan gaya bermain berbeda. Pemain harus memanfaatkan strategi, posisi di arena, dan kemampuan karakter untuk mengalahkan lawan dan menjadi petarung terakhir yang bertahan.', '1780384305_unimachet.jpeg', '2026-05-28 09:26:06'),
(5, 'UNO', 'Kartu, Party', '2-10 Pemain', '15-30 Menit', 'UNO adalah permainan kartu yang mengharuskan pemain mencocokkan warna atau angka kartu yang dimainkan. Pemain pertama yang berhasil menghabiskan seluruh kartunya menjadi pemenang.', '1780382825_WhatsApp Image 2026-06-02 at 14.46.17.jpeg', '2026-06-02 06:47:05'),
(6, 'Monopoly', 'Strategi, Ekonomi', '2-8 Pemain', '60-180 Menit', 'Monopoly adalah permainan jual beli properti di mana pemain membeli, menyewakan, dan mengembangkan aset untuk mengumpulkan kekayaan serta membuat lawan bangkrut.', '1780382946_monopoli.jpeg', '2026-06-02 06:49:06'),
(7, 'Ludo', 'Keluarga, Strategi Ringan', '2-4 Pemain', '20-45 Menit', 'Ludo adalah permainan papan klasik yang mengharuskan pemain memindahkan seluruh bidaknya mengelilingi papan hingga mencapai area tujuan dengan bantuan lemparan dadu.', '1780383412_ludo.jpeg', '2026-06-02 06:56:52'),
(8, 'Catur', 'Strategi', '2 Pemain', '10-120 Menit', 'Catur adalah permainan strategi klasik yang dimainkan dua orang dengan tujuan menjatuhkan raja lawan melalui perencanaan dan taktik yang matang.', '1780383470_catur.jpeg', '2026-06-02 06:57:50'),
(9, 'Scrabble', 'Kata, Edukasi', '2-4 Pemain', '45-90 Menit', 'Scrabble adalah permainan menyusun kata menggunakan huruf-huruf yang tersedia untuk memperoleh skor sebanyak mungkin di atas papan permainan.', '1780383534_scrabble.jpeg', '2026-06-02 06:58:54'),
(10, 'Catan', 'Strategi, Ekonomi', '3-4 Pemain', '60-120 Menit', 'Catan adalah permainan strategi di mana pemain mengumpulkan sumber daya, membangun pemukiman, jalan, dan kota untuk mencapai jumlah poin kemenangan tertentu.', '1780383602_catan.jpeg', '2026-06-02 07:00:02'),
(11, 'Ticket to Ride', 'Strategi, Keluarga', '2-5 Pemain', '30-60 Menit', 'Pemain berlomba membangun jalur kereta api yang menghubungkan berbagai kota berdasarkan kartu tujuan yang dimiliki untuk memperoleh poin terbanyak.', '1780383655_ticket to ride.jpeg', '2026-06-02 07:00:55'),
(12, 'Carcassonne', 'Strategi, Tile Placement', '2-5 Pemain', '35-45 Menit', 'Pemain secara bergantian menyusun ubin wilayah untuk membentuk kota, jalan, dan ladang sambil menempatkan pengikut untuk memperoleh poin.', '1780383710_carcaasone.jpeg', '2026-06-02 07:01:50'),
(13, 'Codenames', 'Party, Kata', '4-8 Pemain', '15-30 Menit', 'Dua tim bersaing menebak kata rahasia berdasarkan petunjuk satu kata yang diberikan oleh pemimpin tim. Komunikasi dan kreativitas sangat dibutuhkan.', '1780384346_codenames.jpeg', '2026-06-02 07:04:02'),
(14, 'Dixit', 'Party, Imajinasi', '3-8 Pemain', '30 Menit', 'Dixit adalah permainan kartu bergambar yang mengandalkan imajinasi dan kreativitas pemain dalam memberikan petunjuk yang unik dan tidak terlalu jelas', '1780383906_dixit.jpeg', '2026-06-02 07:05:06'),
(15, 'Exploding Kittens', 'Kartu, Party', '2-5 Pemain', '15 Menit', 'Pemain bergantian mengambil kartu dari tumpukan sambil berusaha menghindari kartu Exploding Kitten yang dapat langsung mengeliminasi mereka dari permainan.', '1780383965_kittens.jpeg', '2026-06-02 07:06:05'),
(16, 'Pandemic', 'Kooperatif, Strategi', '2-4 Pemain', '45-60 Menit', 'Dalam Pandemic, seluruh pemain bekerja sama sebagai tim untuk menghentikan penyebaran penyakit mematikan yang mengancam berbagai kota di dunia.', '1780384013_pandemic.jpeg', '2026-06-02 07:06:53');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `board_game_id` int(11) DEFAULT NULL,
  `nama_reviewer` varchar(100) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `komentar` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `board_game_id`, `nama_reviewer`, `rating`, `komentar`, `created_at`) VALUES
(17, 5, 'ewat', 4, 'okelah', '2026-06-03 06:09:20'),
(18, 5, 'Steward', 5, 'Mantap gamenya', '2026-06-03 07:08:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(255) DEFAULT 'default-avatar.png',
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `avatar`, `bio`) VALUES
(1, 'admin', '$2y$10$dqDPDJU1/jjs5kPr3WRkc.SHiGRC24VoPUiFMKB.YMcomgD11RlMm', 'admin', '2026-05-26 10:25:32', 'default-avatar.png', NULL),
(2, 'member', '$2y$10$M/N5ZANWUF754w3BKfX./uZHIS35PfQVeeRIsMvRiCzwAJH2vz1e6', 'member', '2026-05-28 09:45:27', '1780469550_Screenshot 2026-03-17 120737.png', ''),
(8, 'Steward', '$2y$10$kaBzhG/1mL6YwFInDb9EW.Bi5qyduK8jdxgm5ZCn1AQPbUs6Oqk3m', 'member', '2026-06-03 06:48:01', '1780470445_Screenshot 2026-03-05 211109.png', 'saya suka!\r\nsaya suka!');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `board_games`
--
ALTER TABLE `board_games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `board_game_id` (`board_game_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `board_games`
--
ALTER TABLE `board_games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`board_game_id`) REFERENCES `board_games` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
