-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2024 年 7 月 26 日 12:02
-- サーバのバージョン： 10.4.28-MariaDB
-- PHP のバージョン: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `lesson-management-system`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `CREATE TABLE IF NOT EXISTS stamps`
--

CREATE TABLE `CREATE TABLE IF NOT EXISTS stamps` (
  `id` int(11) NOT NULL,
  `hobby` varchar(255) NOT NULL,
  `shape` varchar(50) NOT NULL,
  `font` varchar(50) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `CREATE TABLEstamps`
--

CREATE TABLE `CREATE TABLEstamps` (
  `id` int(11) NOT NULL,
  `hobby` varchar(255) NOT NULL,
  `shape` varchar(50) NOT NULL,
  `font` varchar(50) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `diary`
--

CREATE TABLE `diary` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `diary`
--

INSERT INTO `diary` (`id`, `user_id`, `date`, `content`, `created_at`, `updated_at`) VALUES
(1, 20, '2024-07-21', '今日もできた', '2024-07-22 02:08:26', '2024-07-22 02:08:43'),
(3, 20, '2024-07-24', 'よかった。', '2024-07-22 02:09:00', '2024-07-22 02:09:00'),
(4, 20, '2024-07-15', '頑張った', '2024-07-22 02:09:24', '2024-07-22 02:09:24'),
(5, 20, '2024-07-22', '昨日、初めて日向くんのタイガーショットを見た。コンクリートにボールがめり込むほどの威力のあるシュートを打てるなんてすごい。僕ももっとドライブシュートを磨かないと。シュート練習の本数を増やしてみよう。', '2024-07-22 02:14:26', '2024-07-26 10:00:01'),
(6, 20, '2024-07-17', '頑張った', '2024-07-23 08:50:24', '2024-07-23 08:50:24');

-- --------------------------------------------------------

--
-- テーブルの構造 `registration`
--

CREATE TABLE `registration` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `kana` varchar(100) NOT NULL,
  `age` int(3) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `stamps`
--

CREATE TABLE `stamps` (
  `id` int(11) NOT NULL,
  `hobby` varchar(255) NOT NULL,
  `shape` varchar(50) NOT NULL,
  `font` varchar(50) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `status` enum('draft','registered') DEFAULT 'draft',
  `encouragement_image_path` varchar(255) DEFAULT NULL,
  `goal_image_path` varchar(255) DEFAULT NULL,
  `encouragement_message` text DEFAULT NULL,
  `goal_message` text DEFAULT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `stamps`
--

INSERT INTO `stamps` (`id`, `hobby`, `shape`, `font`, `image_path`, `created_at`, `user_id`, `status`, `encouragement_image_path`, `goal_image_path`, `encouragement_message`, `goal_message`, `color`) VALUES
(1, 'KUMON', 'circle', 'Dancing Script', '../assets/images/generated_stamps/stamp_1719193108.png', '2024-06-24 01:38:28', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(2, 'KUMON', 'cloud', 'Fredoka One', '/lesson-management-system/assets/images/generated_stamps/stamp_1719194499_6678d3830d4cc.png', '2024-06-24 02:01:39', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(3, 'ダンス', 'circle', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719197700_6678e004a46df.png', '2024-06-24 02:55:00', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(4, 'KUMON', 'cloud', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719217365_66792cd5455f7.png', '2024-06-24 08:22:45', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(5, 'KUMON', 'cloud', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719217378_66792ce242905.png', '2024-06-24 08:22:58', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(6, 'KUMON', 'star', 'Indie Flower', '/assets/images/generated_stamps/stamp_1719217863_66792ec703b80.png', '2024-06-24 08:31:03', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(7, 'daiet', 'cloud', 'Fredoka One', '/assets/images/generated_stamps/stamp_1719218013_66792f5d0e22d.png', '2024-06-24 08:33:33', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(8, 'KUMON', 'heart', 'Roboto', '/assets/images/generated_stamps/stamp_1719218071_66792f974a84e.png', '2024-06-24 08:34:31', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(9, 'KUMON', 'circle', 'Indie Flower', '/assets/images/generated_stamps/stamp_1719218203_6679301b52e58.png', '2024-06-24 08:36:43', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(10, 'ダンス', 'heart', 'Indie Flower', '/assets/images/generated_stamps/stamp_1719218253_6679304d93ad1.png', '2024-06-24 08:37:33', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(11, 'ダンス', 'cloud', 'Fredoka One', '/assets/images/generated_stamps/stamp_1719218386_667930d281fa6.png', '2024-06-24 08:39:46', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(12, 'KUMON', 'star', 'Permanent Marker', '/assets/images/generated_stamps/stamp_1719218556_6679317c0124f.png', '2024-06-24 08:42:36', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(13, 'KUMON', 'cloud', 'Fredoka One', '/assets/images/generated_stamps/stamp_1719229922_66795de237a7d.png', '2024-06-24 11:52:02', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(14, 'ダンス', 'circle', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719279613_667a1ffdd1b01.png', '2024-06-25 01:40:13', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(15, 'KUMON', 'star', 'Fredoka One', '/assets/images/generated_stamps/stamp_1719279933_667a213d7f374.png', '2024-06-25 01:45:33', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(16, '水泳', 'circle', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719280121_667a21f9f37e6.png', '2024-06-25 01:48:42', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(17, '英語', 'star', 'Permanent Marker', '/assets/images/generated_stamps/stamp_1719280590_667a23ce40d9d.png', '2024-06-25 01:56:30', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(18, 'KUMON', 'circle', 'Permanent Marker', '/assets/images/generated_stamps/stamp_1719280743_667a24677c867.png', '2024-06-25 01:59:03', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(19, 'ダンス', 'circle', 'Indie Flower', '/assets/images/generated_stamps/stamp_1719280910_667a250ec5fd6.png', '2024-06-25 02:01:50', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(20, 'ダンス', 'circle', 'Pacifico', '/assets/images/generated_stamps/stamp_1719280931_667a25238e300.png', '2024-06-25 02:02:11', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(21, 'ダンス', 'circle', 'Permanent Marker', '/assets/images/generated_stamps/stamp_1719280947_667a2533535f4.png', '2024-06-25 02:02:27', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(22, 'ダンス', 'circle', 'Roboto', '/assets/images/generated_stamps/stamp_1719280961_667a2541cf940.png', '2024-06-25 02:02:41', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(23, 'KUMON', 'cloud', 'Permanent Marker', '/assets/images/generated_stamps/stamp_1719281287_667a2687312d7.png', '2024-06-25 02:08:07', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(24, 'KUMON', 'circle', 'Permanent Marker', '/assets/images/generated_stamps/stamp_1719281343_667a26bf3cea4.png', '2024-06-25 02:09:03', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(25, 'ダンス', 'circle', 'Permanent Marker', '/assets/images/generated_stamps/stamp_1719281356_667a26ccc8328.png', '2024-06-25 02:09:16', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(26, 'daiet', 'circle', 'Permanent Marker', '/assets/images/generated_stamps/stamp_1719281395_667a26f3197fd.png', '2024-06-25 02:09:55', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(27, 'KUMON', 'circle', 'Pacifico', '/assets/images/generated_stamps/stamp_1719281455_667a272fd9999.png', '2024-06-25 02:10:56', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(28, 'KUMON', 'circle', 'Roboto', '/assets/images/generated_stamps/stamp_1719281480_667a274837c96.png', '2024-06-25 02:11:20', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(29, 'KUMON', 'circle', 'Indie Flower', '/assets/images/generated_stamps/stamp_1719281556_667a279422de1.png', '2024-06-25 02:12:36', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(30, 'KUMON', 'circle', 'Pacifico', '/assets/images/generated_stamps/stamp_1719281569_667a27a1bf7c4.png', '2024-06-25 02:12:49', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(31, 'KUMON', 'circle', 'Permanent Marker', '/assets/images/generated_stamps/stamp_1719281584_667a27b065996.png', '2024-06-25 02:13:04', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(32, 'daiet', 'circle', 'Roboto', '/assets/images/generated_stamps/stamp_1719281600_667a27c0e970e.png', '2024-06-25 02:13:21', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(33, 'KUMON', 'circle', 'Indie Flower', '/assets/images/generated_stamps/stamp_1719281630_667a27dec07c7.png', '2024-06-25 02:13:50', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(34, 'ダンス', 'circle', 'Indie Flower', '/assets/images/generated_stamps/stamp_1719281663_667a27ff7cd01.png', '2024-06-25 02:14:23', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(35, 'HIIT', 'heart', 'Pacifico', '/assets/images/generated_stamps/stamp_1719282403_667a2ae3e4b53.png', '2024-06-25 02:26:44', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(36, 'KUMON', 'circle', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719305635_667a85a3aa665.png', '2024-06-25 08:53:55', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(37, 'KUMON', 'circle', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719305904_667a86b07eac1.png', '2024-06-25 08:58:24', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(38, 'KUMON', 'circle', 'Dancing Script', '/assets/images/generated_stamps/stamp_1719305915_667a86bb9b4c0.png', '2024-06-25 08:58:35', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(39, 'KUMON', 'cloud', 'Dancing Script', '/assets/images/generated_stamps/stamp_1719305930_667a86ca45818.png', '2024-06-25 08:58:50', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(40, 'KUMON', 'circle', 'Fredoka One', '/assets/images/generated_stamps/stamp_1719305943_667a86d7de8b1.png', '2024-06-25 08:59:04', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(41, 'ダンス', 'circle', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719305954_667a86e23c9c3.png', '2024-06-25 08:59:14', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(42, 'ダンス', 'circle', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719306505_667a890980f3b.png', '2024-06-25 09:08:25', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(43, 'ダンス', 'circle', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719306521_667a8919505cb.png', '2024-06-25 09:08:41', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(44, 'ダンス', 'circle', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719312579_667aa0c3a5a3f.png', '2024-06-25 10:49:39', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(45, 'ダンス', 'circle', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719316129_667aaea10958a.png', '2024-06-25 11:48:49', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(46, 'ダンス', 'circle', 'Bebas Neue', '/assets/images/generated_stamps/stamp_1719317010_667ab212b8cc1.png', '2024-06-25 12:03:30', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(47, 'ダンス', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1719318428_667ab79ce1b59.png', '2024-06-25 12:27:09', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(48, 'ダンス', 'circle', 'DancingScript', '/assets/images/generated_stamps/stamp_1719318456_667ab7b87f05f.png', '2024-06-25 12:27:36', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(49, 'ダンス', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1719318479_667ab7cfba917.png', '2024-06-25 12:27:59', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(50, 'ダンス', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1719318655_667ab87fbf25b.png', '2024-06-25 12:30:55', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(51, 'ダンス', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1719318662_667ab8863bbaa.png', '2024-06-25 12:31:02', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(52, 'HIIT', 'circle', 'PermanentMarker', '/assets/images/generated_stamps/stamp_1719364734_667b6c7ed8765.png', '2024-06-26 01:18:55', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(53, 'HIIT', 'circle', 'PermanentMarker', '/assets/images/generated_stamps/stamp_1719364737_667b6c81a1e77.png', '2024-06-26 01:18:57', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(54, 'KUMON', 'cloud', 'Pacifico', '/assets/images/generated_stamps/stamp_1719365252_667b6e84914b2.png', '2024-06-26 01:27:32', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(55, 'daiet', 'heart', 'PermanentMarker', '/assets/images/generated_stamps/stamp_1719365473_667b6f6158251.png', '2024-06-26 01:31:13', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(56, 'HIIT', 'star', 'BebasNeue', '/assets/images/generated_stamps/stamp_1719365639_667b7007cf7fe.png', '2024-06-26 01:33:59', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(57, 'KUMON', 'circle', 'DancingScript', '/assets/images/generated_stamps/stamp_1719365682_667b703298f5a.png', '2024-06-26 01:34:42', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(58, 'KUMON', 'square', 'PermanentMarker', '/assets/images/generated_stamps/stamp_1719365928_667b7128d52be.png', '2024-06-26 01:38:48', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(59, '英語', 'heart', 'DancingScript', '/assets/images/generated_stamps/stamp_1719366550_667b7396e303c.png', '2024-06-26 01:49:11', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(60, 'HIIT', 'cloud', 'Pacifico', '/assets/images/generated_stamps/stamp_1719367341_667b76ad1a62c.png', '2024-06-26 02:02:21', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(61, 'ダンス', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1719401211_667bfafbcee3b.png', '2024-06-26 11:26:51', NULL, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(62, 'ダンス', 'circle', 'DancingScript', '/assets/images/generated_stamps/stamp_1719450691_667cbc4391a1d.png', '2024-06-27 01:11:31', 4, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(63, 'KUMON', 'star', 'Pacifico', '/assets/images/generated_stamps/stamp_1719489045_667d5215ead10.png', '2024-06-27 11:50:46', 7, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(64, 'daiet', 'cloud', 'Pacifico', '/assets/images/generated_stamps/stamp_1719541307_667e1e3b5f068.png', '2024-06-28 02:21:47', 7, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(65, 'Succer', 'cloud', 'Pacifico', '/assets/images/generated_stamps/stamp_1719559775_667e665f15a30.png', '2024-06-28 07:29:35', 8, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(66, 'KUMON', 'star', 'Pacifico', '/assets/images/generated_stamps/stamp_1719631168_667f7d401723d.png', '2024-06-29 03:19:28', 8, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(67, 'HIIT', 'circle', 'FredokaOne', '/assets/images/generated_stamps/stamp_1719883432_668356a8da255.png', '2024-07-02 01:23:52', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(68, 'Aotophagy', 'cloud', 'Pacifico', '/assets/images/generated_stamps/stamp_1719883873_66835861ac24c.png', '2024-07-02 01:31:13', 10, 'registered', NULL, NULL, NULL, NULL, '#000000'),
(69, 'Duolingo', 'heart', 'NotoSansJP', '/assets/images/generated_stamps/stamp_1719884129_66835961c8305.png', '2024-07-02 01:35:29', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(70, 'Gs', 'star', 'DancingScript', '/assets/images/generated_stamps/stamp_1719884361_66835a4986f27.png', '2024-07-02 01:39:21', 10, 'registered', NULL, NULL, NULL, NULL, '#000000'),
(71, 'ダンス', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1719911012_6683c264ea5f1.png', '2024-07-02 09:03:33', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(72, 'ダンス', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1719911348_6683c3b45cfb4.png', '2024-07-02 09:09:08', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(73, 'ダンス', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1719918186_6683de6ac8715.png', '2024-07-02 11:03:06', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(74, '水泳', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1719920557_6683e7ad40575.png', '2024-07-02 11:42:37', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(75, 'daiet', 'cloud', 'BebasNeue', '/lesson-management-system/assets/images/generated_stamps/stamp_1719921204_6683ea342873d.png', '2024-07-02 11:53:24', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(76, '英語', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1719921760_6683ec60734eb.png', '2024-07-02 12:02:40', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(77, 'daiet', 'cloud', 'IndieFlower', '/assets/images/generated_stamps/stamp_1719921779_6683ec73e91db.png', '2024-07-02 12:03:00', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(78, '筋トレ', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720009313_66854261a8012.png', '2024-07-03 12:21:53', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(79, 'test1', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720055271_6685f5e7d331a.png', '2024-07-04 01:07:51', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(80, 'test1', 'cloud', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720055279_6685f5efab420.png', '2024-07-04 01:07:59', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(81, 'test1', 'square', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720055292_6685f5fc4b572.png', '2024-07-04 01:08:12', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(82, 'test1', 'heart', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720055297_6685f601ca14d.png', '2024-07-04 01:08:17', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(83, 'test2', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720056111_6685f92fc880a.png', '2024-07-04 01:21:51', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(84, 'test2', 'circle', 'FredokaOne', '/assets/images/generated_stamps/stamp_1720056191_6685f97fb072b.png', '2024-07-04 01:23:11', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(85, 'test3', 'cloud', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720057503_6685fe9f9bcb4.png', '2024-07-04 01:45:03', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(86, 'test4', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720057753_6685ff99d9594.png', '2024-07-04 01:49:13', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(87, 'test4', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720057785_6685ffb94a851.png', '2024-07-04 01:49:45', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(88, 'test5', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720057801_6685ffc9b0d84.png', '2024-07-04 01:50:01', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(89, 'test5', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720057809_6685ffd160c8c.png', '2024-07-04 01:50:09', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(90, 'test6', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720058167_6686013779b32.png', '2024-07-04 01:56:07', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(91, 'test7', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720058532_668602a4f1c53.png', '2024-07-04 02:02:13', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(92, 't8', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720058833_668603d12d8b7.png', '2024-07-04 02:07:13', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(93, 't8', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720058849_668603e1184af.png', '2024-07-04 02:07:29', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(94, 't8', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720058871_668603f76557b.png', '2024-07-04 02:07:51', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(95, 't13', 'circle', 'BebasNeue', '/lesson-management-system/assets/images/generated_stamps/stamp_1720060306_66860992890c1.png', '2024-07-04 02:31:46', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(96, 't15', 'star', 'BebasNeue', '/lesson-management-system/assets/images/generated_stamps/stamp_1720060668_66860afc59dce.png', '2024-07-04 02:37:48', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(97, 't20', 'circle', 'BebasNeue', '/lesson-management-system/assets/images/generated_stamps/stamp_1720061135_66860ccf88764.png', '2024-07-04 02:45:35', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(98, 't01', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720061419_66860debb6a49.png', '2024-07-04 02:50:19', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(99, 't02', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720061434_66860dfa55914.png', '2024-07-04 02:50:34', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(100, 'tt1', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720081238_66865b56cfe6a.png', '2024-07-04 08:20:38', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(101, 'tt2', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720081278_66865b7eead73.png', '2024-07-04 08:21:19', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(102, 'ttt', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720092294_6686868610880.png', '2024-07-04 11:24:54', 10, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(103, 'ダンス', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720230706_6688a3325b8df.png', '2024-07-06 01:51:46', 12, 'draft', NULL, NULL, NULL, NULL, '#000000'),
(112, '英語', 'heart', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720402868_668b43b49c105.png', '2024-07-08 01:41:08', 13, 'registered', '/uploads/encouragement/668de17e707e4.png', NULL, '昨日より一歩でも半歩でも前に進んでいるね。頑張ったね。', NULL, '#000000'),
(118, '水泳', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720578929_668df371799ba.png', '2024-07-10 02:35:29', 13, 'registered', '/uploads/encouragement/668df3c510ddf.png', NULL, '昨日より一歩でも半歩でも前に進んでいるね。頑張ったね。', NULL, '#000000'),
(119, 'daiet', 'cloud', 'Pacifico', '/assets/images/generated_stamps/stamp_1720661053_668f343dea99a.png', '2024-07-11 01:24:14', 13, 'registered', NULL, NULL, NULL, NULL, '#000000'),
(120, '川中島', 'circle', 'BebasNeue', '/assets/images/generated_stamps/stamp_1720663938_668f3f82e19af.png', '2024-07-11 02:12:19', 14, 'registered', '/uploads/encouragement/668f4083770af.avif', NULL, 'キツツキ戦法見破ってすごいね！', NULL, '#000000'),
(308, 'ドライブシュート', 'heart', 'Pigmo01', '/assets/images/generated_stamps/stamp_1721987423_66a3715f01cca.png', '2024-07-26 09:50:23', 20, 'registered', '/uploads/encouragement/66a372c09b8a8.png', NULL, '昨日より曲がるようになったね！', '', '#0000FF');

-- --------------------------------------------------------

--
-- テーブルの構造 `stamp_continuity`
--

CREATE TABLE `stamp_continuity` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `stamp_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `streak_count` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- トリガ `stamp_continuity`
--
DELIMITER $$
CREATE TRIGGER `update_stamp_usage_after_continuity_insert` AFTER INSERT ON `stamp_continuity` FOR EACH ROW BEGIN
    DECLARE last_usage_date DATE;
    DECLARE current_streak INT;

    -- 最後の使用日を取得
    SELECT MAX(date) INTO last_usage_date
    FROM stamp_continuity
    WHERE stamp_id = NEW.stamp_id AND user_id = NEW.user_id AND date < NEW.date;

    -- 連続日数を計算
    IF last_usage_date IS NULL OR DATEDIFF(NEW.date, last_usage_date) = 1 THEN
        -- 連続している場合
        SELECT IFNULL(MAX(streak_count), 0) + 1 INTO current_streak
        FROM stamp_continuity
        WHERE stamp_id = NEW.stamp_id AND user_id = NEW.user_id AND date = last_usage_date;
    ELSE
        -- 連続が途切れた場合
        SET current_streak = 1;
    END IF;

    -- stamp_usageテーブルを更新
    UPDATE stamp_usage
    SET last_used_date = NEW.date,
        current_streak = current_streak
    WHERE stamp_id = NEW.stamp_id AND user_id = NEW.user_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- テーブルの構造 `stamp_usage`
--

CREATE TABLE `stamp_usage` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stamp_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `frequency_type` enum('daily','weekly','monthly') NOT NULL,
  `frequency_count` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `weekdays` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_goal_date` date DEFAULT NULL,
  `next_goal_cycle_count` int(11) DEFAULT NULL,
  `next_goal_cycle_unit` enum('day','month','year') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `stamp_usage`
--

INSERT INTO `stamp_usage` (`id`, `user_id`, `stamp_id`, `start_date`, `frequency_type`, `frequency_count`, `duration`, `weekdays`, `created_at`, `first_goal_date`, `next_goal_cycle_count`, `next_goal_cycle_unit`) VALUES
(1, 7, 63, '2024-06-28', 'weekly', 2, 60, NULL, '2024-06-28 02:04:17', NULL, NULL, NULL),
(3, 7, 64, '2024-06-28', 'daily', 1, 15, NULL, '2024-06-28 02:48:16', NULL, NULL, NULL),
(10, 8, 65, '2024-06-28', 'daily', 1, 1, NULL, '2024-06-28 12:57:43', NULL, NULL, NULL),
(13, 8, 66, '2024-07-01', 'daily', 1, 30, NULL, '2024-07-01 02:41:16', NULL, NULL, NULL),
(14, 8, 65, '2024-07-01', 'daily', 1, 30, NULL, '2024-07-01 02:41:52', NULL, NULL, NULL),
(15, 10, 67, '2024-07-02', 'daily', 1, 30, NULL, '2024-07-02 01:24:11', NULL, NULL, NULL),
(16, 10, 67, '2020-05-01', 'weekly', 1, 15, NULL, '2024-07-02 01:28:49', NULL, NULL, NULL),
(17, 10, 68, '2020-09-01', 'daily', 1, 30, NULL, '2024-07-02 01:34:37', NULL, NULL, NULL),
(18, 10, 69, '2022-01-01', 'daily', 1, 5, NULL, '2024-07-02 01:36:30', NULL, NULL, NULL),
(19, 10, 74, '2024-03-07', 'weekly', 1, 50, NULL, '2024-07-03 01:16:22', NULL, NULL, NULL),
(20, 10, 68, '2024-07-03', 'daily', 1, 30, NULL, '2024-07-03 01:16:40', NULL, NULL, NULL),
(21, 10, 69, '2022-01-05', 'daily', 1, 5, NULL, '2024-07-03 08:50:29', NULL, NULL, NULL),
(22, 10, 70, '2024-04-13', 'weekly', 1, 240, NULL, '2024-07-03 11:43:21', NULL, NULL, NULL),
(23, 10, 68, '2024-07-02', 'weekly', 1, 1, NULL, '2024-07-03 12:20:51', NULL, NULL, NULL),
(24, 10, 78, '2024-07-03', 'daily', 1, 1, NULL, '2024-07-03 12:23:08', NULL, NULL, NULL),
(25, 10, 84, '2024-07-04', 'daily', 1, 1, NULL, '2024-07-04 01:24:32', NULL, NULL, NULL),
(27, 10, 68, '2024-07-04', 'daily', 1, 30, NULL, '2024-07-04 11:51:33', NULL, NULL, NULL),
(28, 13, 105, '2024-07-10', 'weekly', 1, 2, NULL, '2024-07-07 00:57:58', NULL, NULL, NULL),
(29, 13, 111, '2024-06-01', 'weekly', 1, 15, NULL, '2024-07-07 06:55:43', NULL, NULL, NULL),
(30, 13, 111, '2024-07-07', 'daily', 1, 30, NULL, '2024-07-07 06:56:31', NULL, NULL, NULL),
(31, 13, 105, '2024-07-08', 'daily', 1, 30, NULL, '2024-07-08 01:39:18', NULL, NULL, NULL),
(32, 13, 112, '2024-06-03', 'weekly', 2, 240, NULL, '2024-07-08 01:41:56', NULL, NULL, NULL),
(33, 13, 112, '2024-07-08', 'daily', 1, 30, NULL, '2024-07-08 01:43:05', NULL, NULL, NULL),
(34, 13, 111, '2024-07-08', 'daily', 1, 30, NULL, '2024-07-08 02:04:46', NULL, NULL, NULL),
(35, 13, 113, '2024-03-31', 'weekly', 1, 50, NULL, '2024-07-08 08:36:33', NULL, NULL, NULL),
(36, 13, 113, '2024-07-08', 'weekly', 1, 50, NULL, '2024-07-08 08:56:17', NULL, NULL, NULL),
(37, 13, 114, '2024-05-28', 'weekly', 2, 30, NULL, '2024-07-10 01:30:38', NULL, NULL, NULL),
(38, 13, 114, '2024-07-10', 'weekly', 2, 30, NULL, '2024-07-10 01:46:39', NULL, NULL, NULL),
(39, 13, 112, '2024-07-10', 'daily', 1, 30, NULL, '2024-07-10 01:46:51', NULL, NULL, NULL),
(40, 13, 115, '2024-02-01', 'weekly', 1, 50, NULL, '2024-07-10 02:04:48', NULL, NULL, NULL),
(41, 13, 115, '2024-07-10', 'weekly', 1, 50, NULL, '2024-07-10 02:11:58', NULL, NULL, NULL),
(42, 13, 116, '2024-04-04', 'weekly', 1, 50, NULL, '2024-07-10 02:22:51', NULL, NULL, NULL),
(43, 13, 116, '2024-07-10', 'weekly', 1, 50, NULL, '2024-07-10 02:23:11', NULL, NULL, NULL),
(44, 13, 117, '2024-03-07', 'weekly', 1, 50, NULL, '2024-07-10 02:29:39', NULL, NULL, NULL),
(45, 13, 117, '2024-07-10', 'weekly', 1, 50, NULL, '2024-07-10 02:30:10', NULL, NULL, NULL),
(46, 13, 118, '2024-03-06', 'weekly', 1, 50, NULL, '2024-07-10 02:35:59', NULL, NULL, NULL),
(47, 13, 118, '2024-07-10', 'weekly', 1, 50, NULL, '2024-07-10 02:36:07', NULL, NULL, NULL),
(48, 13, 112, '2024-07-11', 'daily', 1, 30, NULL, '2024-07-11 01:23:24', NULL, NULL, NULL),
(49, 13, 119, '2024-05-30', 'daily', 1, 20, NULL, '2024-07-11 01:24:46', NULL, NULL, NULL),
(50, 13, 119, '2024-07-11', 'daily', 1, 20, NULL, '2024-07-11 01:25:06', NULL, NULL, NULL),
(51, 14, 120, '2024-05-02', 'weekly', 1, 120, NULL, '2024-07-11 02:13:03', NULL, NULL, NULL),
(52, 14, 120, '2024-07-11', 'weekly', 1, 120, NULL, '2024-07-11 02:13:27', NULL, NULL, NULL),
(53, 20, 121, '2024-06-03', 'weekly', 2, 20, NULL, '2024-07-12 09:00:46', NULL, NULL, NULL),
(54, 20, 121, '2024-07-12', 'weekly', 2, 20, NULL, '2024-07-12 09:01:02', NULL, NULL, NULL),
(55, 20, 122, '2024-07-01', 'weekly', 2, 15, NULL, '2024-07-12 09:43:48', NULL, NULL, NULL),
(56, 20, 123, '2024-05-27', 'weekly', 2, 15, NULL, '2024-07-12 09:45:40', NULL, NULL, NULL),
(57, 20, 123, '2024-07-12', 'weekly', 2, 15, NULL, '2024-07-12 09:45:56', NULL, NULL, NULL),
(58, 20, 124, '2024-04-29', 'weekly', 2, 5, NULL, '2024-07-12 09:54:42', NULL, NULL, NULL),
(59, 20, 124, '2024-07-12', 'weekly', 2, 5, NULL, '2024-07-12 09:55:10', NULL, NULL, NULL),
(60, 20, 125, '2024-02-26', 'weekly', 2, 5, NULL, '2024-07-12 12:12:17', NULL, NULL, NULL),
(61, 20, 126, '2024-04-01', 'weekly', 2, 5, NULL, '2024-07-12 12:22:35', NULL, NULL, NULL),
(62, 20, 126, '2024-07-12', 'weekly', 2, 5, NULL, '2024-07-12 12:27:05', NULL, NULL, NULL),
(63, 20, 127, '2024-04-29', 'weekly', 2, 10, NULL, '2024-07-13 01:15:06', NULL, NULL, NULL),
(64, 20, 127, '2024-07-13', 'weekly', 2, 10, NULL, '2024-07-13 01:17:21', NULL, NULL, NULL),
(65, 20, 128, '2024-04-29', 'weekly', 2, 5, NULL, '2024-07-13 01:30:38', NULL, NULL, NULL),
(66, 20, 128, '2024-07-13', 'weekly', 2, 5, NULL, '2024-07-13 01:30:53', NULL, NULL, NULL),
(67, 20, 129, '2024-04-29', 'weekly', 2, 5, NULL, '2024-07-13 02:01:17', NULL, NULL, NULL),
(68, 20, 129, '2024-07-13', 'weekly', 2, 5, NULL, '2024-07-13 02:01:35', NULL, NULL, NULL),
(69, 20, 130, '2024-04-29', 'daily', 2, 5, NULL, '2024-07-13 02:13:54', NULL, NULL, NULL),
(70, 20, 131, '2024-04-29', 'weekly', 2, 5, NULL, '2024-07-13 02:14:56', NULL, NULL, NULL),
(71, 20, 132, '2024-04-29', 'weekly', 2, 5, NULL, '2024-07-13 02:28:00', NULL, NULL, NULL),
(72, 20, 133, '2024-04-29', 'weekly', 2, 5, NULL, '2024-07-13 02:38:58', NULL, NULL, NULL),
(73, 20, 134, '2024-04-29', 'weekly', 2, 5, NULL, '2024-07-13 02:44:20', NULL, NULL, NULL),
(74, 20, 135, '2024-04-29', 'weekly', 2, 5, NULL, '2024-07-13 02:48:35', NULL, NULL, NULL),
(75, 20, 136, '2024-05-27', 'weekly', 2, 5, NULL, '2024-07-13 02:52:29', NULL, NULL, NULL),
(76, 20, 137, '2024-04-29', 'weekly', 2, 5, NULL, '2024-07-13 03:01:11', NULL, NULL, NULL),
(77, 20, 137, '2024-07-14', 'weekly', 2, 5, NULL, '2024-07-13 23:58:54', NULL, NULL, NULL),
(78, 20, 138, '2024-04-29', 'weekly', 2, 30, NULL, '2024-07-14 05:25:42', NULL, NULL, NULL),
(79, 20, 138, '2024-07-14', 'weekly', 2, 30, NULL, '2024-07-14 05:26:30', NULL, NULL, NULL),
(80, 20, 139, '2024-04-29', 'weekly', 2, 30, NULL, '2024-07-14 05:47:14', NULL, NULL, NULL),
(81, 20, 139, '2024-07-14', 'weekly', 2, 30, NULL, '2024-07-14 05:47:47', NULL, NULL, NULL),
(82, 20, 140, '2024-04-29', 'weekly', 2, 15, NULL, '2024-07-14 06:05:22', NULL, NULL, NULL),
(83, 20, 141, '2024-04-29', 'weekly', 2, 20, NULL, '2024-07-14 06:13:09', NULL, NULL, NULL),
(84, 20, 142, '2024-04-29', 'weekly', 2, 30, NULL, '2024-07-14 06:26:17', NULL, NULL, NULL),
(85, 20, 143, '2024-04-29', 'weekly', 2, 20, NULL, '2024-07-14 06:33:05', NULL, NULL, NULL),
(86, 20, 144, '2024-04-29', 'weekly', 2, 30, NULL, '2024-07-14 06:49:47', NULL, NULL, NULL),
(87, 20, 145, '2024-04-29', 'weekly', 2, 10, NULL, '2024-07-14 07:15:41', NULL, NULL, NULL),
(88, 20, 146, '2024-04-29', 'weekly', 2, 20, NULL, '2024-07-14 07:27:10', NULL, NULL, NULL),
(89, 20, 147, '2024-04-02', 'weekly', 2, 15, NULL, '2024-07-15 00:29:51', NULL, NULL, NULL),
(90, 20, 148, '2024-07-07', 'weekly', 2, 10, NULL, '2024-07-15 00:35:04', NULL, NULL, NULL),
(91, 20, 149, '2024-06-30', 'weekly', 2, 5, NULL, '2024-07-15 02:19:16', NULL, NULL, NULL),
(92, 20, 152, '2024-05-27', 'weekly', 2, 10, '[\"mon\",\"thu\"]', '2024-07-15 02:28:05', NULL, NULL, NULL),
(93, 20, 153, '2024-05-01', 'weekly', 2, 10, '[\"wed\",\"thu\"]', '2024-07-15 08:38:31', NULL, NULL, NULL),
(94, 20, 156, '2024-05-01', 'weekly', 2, 30, '[\"wed\",\"thu\"]', '2024-07-15 08:44:52', NULL, NULL, NULL),
(95, 20, 157, '2024-05-01', 'weekly', 2, 20, '[\"wed\",\"thu\"]', '2024-07-15 08:55:26', NULL, NULL, NULL),
(96, 20, 158, '2024-04-30', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-16 01:23:54', NULL, NULL, NULL),
(97, 20, 159, '2024-04-29', 'weekly', 3, 10, '[\"mon\",\"wed\",\"thu\"]', '2024-07-16 01:27:24', NULL, NULL, NULL),
(102, 20, 163, '2024-04-29', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-16 02:30:13', NULL, NULL, NULL),
(103, 20, 164, '2024-04-29', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-16 08:26:32', NULL, NULL, NULL),
(104, 20, 165, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-16 08:28:36', NULL, NULL, NULL),
(105, 20, 166, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-16 08:53:43', NULL, NULL, NULL),
(106, 20, 167, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-16 09:02:10', NULL, NULL, NULL),
(107, 20, 168, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-16 11:18:57', NULL, NULL, NULL),
(108, 20, 169, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-16 11:40:00', NULL, NULL, NULL),
(109, 20, 170, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-16 11:59:30', NULL, NULL, NULL),
(110, 20, 171, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 01:24:11', NULL, NULL, NULL),
(111, 20, 172, '2024-07-01', 'weekly', 3, 10, '[\"tue\",\"wed\",\"thu\"]', '2024-07-17 02:00:25', NULL, NULL, NULL),
(112, 20, 173, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 02:30:04', NULL, NULL, NULL),
(113, 20, 174, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 02:33:03', NULL, NULL, NULL),
(114, 20, 175, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 02:50:29', NULL, NULL, NULL),
(115, 20, 176, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 09:14:03', NULL, NULL, NULL),
(116, 20, 177, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 09:21:12', NULL, NULL, NULL),
(117, 20, 178, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 09:31:11', NULL, NULL, NULL),
(118, 20, 179, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 09:46:28', NULL, NULL, NULL),
(119, 20, 180, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 11:29:57', NULL, NULL, NULL),
(120, 20, 181, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 12:02:01', NULL, NULL, NULL),
(121, 20, 182, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 12:07:15', NULL, NULL, NULL),
(122, 20, 183, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 12:16:29', NULL, NULL, NULL),
(123, 20, 184, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-17 12:18:07', NULL, NULL, NULL),
(124, 20, 185, '2024-07-01', 'weekly', 4, 10, '[\"mon\",\"tue\",\"wed\",\"thu\"]', '2024-07-18 01:14:41', NULL, NULL, NULL),
(125, 20, 186, '2024-07-01', 'weekly', 4, 10, '[\"mon\",\"tue\",\"wed\",\"thu\"]', '2024-07-18 02:40:59', NULL, NULL, NULL),
(126, 20, 187, '2024-07-01', 'weekly', 2, 10, '[\"mon\",\"thu\"]', '2024-07-18 02:47:00', NULL, NULL, NULL),
(127, 20, 279, '2024-07-01', 'weekly', 3, 10, '[\"mon\",\"tue\",\"wed\"]', '2024-07-22 02:28:58', NULL, NULL, NULL),
(128, 20, 269, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-22 08:33:38', '2024-07-31', 1, 'month'),
(129, 20, 280, '2024-07-01', 'weekly', 1, 1, '[\"mon\"]', '2024-07-23 22:54:30', '2024-07-31', 1, 'month'),
(130, 20, 281, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-24 02:07:42', '2024-07-31', 1, 'month'),
(131, 20, 282, '2024-07-02', 'weekly', 1, 10, '[\"tue\"]', '2024-07-24 02:16:10', '2024-07-31', 1, 'month'),
(132, 20, 283, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-24 02:17:38', '2024-07-31', 1, 'month'),
(133, 20, 285, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-24 08:44:46', '2024-07-31', 1, 'month'),
(134, 20, 286, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-24 08:52:41', '2024-07-31', 1, 'month'),
(135, 20, 287, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-24 09:06:33', '2024-07-31', 1, 'month'),
(136, 20, 288, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-24 11:08:49', '2024-07-31', 1, 'month'),
(137, 20, 289, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-24 11:15:47', '2024-07-31', 1, 'month'),
(138, 20, 290, '2024-07-01', 'weekly', 1, 1, '[\"mon\"]', '2024-07-24 11:56:28', '2024-07-31', 1, 'month'),
(139, 20, 292, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-25 01:29:27', '2024-07-25', 1, 'month'),
(140, 20, 293, '2024-07-01', 'weekly', 1, 1, '[\"mon\"]', '2024-07-25 01:51:07', '2024-07-25', 1, 'year'),
(141, 20, 294, '2024-07-01', 'weekly', 1, 1, '[\"mon\"]', '2024-07-25 02:10:40', '2024-07-25', 1, 'year'),
(142, 20, 295, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-25 02:26:21', '2024-07-25', 1, 'year'),
(143, 20, 296, '2024-07-01', 'weekly', 1, 1, '[\"mon\"]', '2024-07-25 02:39:37', '2024-07-25', 1, 'year'),
(144, 20, 297, '2024-07-01', 'weekly', 1, 1, '[\"mon\"]', '2024-07-25 08:30:33', '2024-07-25', 1, 'year'),
(145, 20, 298, '2024-07-01', 'weekly', 1, 1, '[\"mon\"]', '2024-07-25 08:38:29', '2024-07-25', 1, 'year'),
(146, 20, 299, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-25 08:48:05', '2024-07-25', 1, 'year'),
(147, 20, 300, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-25 12:02:34', '2024-07-25', 1, 'year'),
(148, 20, 301, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-25 12:04:34', '2024-07-25', 1, 'year'),
(149, 20, 302, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-26 01:20:52', '2024-07-26', 1, 'year'),
(150, 20, 303, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-26 02:32:52', '2024-07-26', 1, 'year'),
(151, 20, 304, '2024-07-01', 'weekly', 1, 10, '[\"mon\"]', '2024-07-26 02:47:29', '2024-07-26', 1, 'year'),
(152, 20, 308, '2024-07-01', 'weekly', 3, 30, '[\"mon\",\"tue\",\"wed\"]', '2024-07-26 09:51:10', '2024-09-30', 3, 'month');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `kana` varchar(255) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `age` int(3) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `kana`, `nickname`, `age`, `gender`, `address`, `email`, `phone`, `password`, `created_at`) VALUES
(1, 'Hideto Mochizuki', 'ッ', NULL, 8, 'female', '南長崎6-14-13', 'mochizukihideto02@yahoo.co.jp', '08050006102', '$2y$10$LmqylThh1tceDL1ezlDfWe3yJT/TecjiDeSIMwcFNTW5RkVnlG272', '2024-06-23 07:28:32'),
(2, '田中　太郎', 'タナカ　タロウ', NULL, 7, 'male', '福岡県糟屋郡１−１−１', '1234567@gmail.com', '08011111111', '$2y$10$UdEYUrNva8oyRiYu/XwWb.YzKA69mLiog4ZmYFNuaunl5xrhMpvjm', '2024-06-25 02:07:34'),
(3, '三村', 'ミムラ', NULL, 12, 'male', '福岡県糟屋郡１−１−１3', '33333@gmail.com', '08011111113', '$2y$10$xtDsoDpG5lNC6Or8F54t1.CVMIaeD9txobkPy7Yeip/ek6YGJcDXa', '2024-06-26 09:06:30'),
(4, '佐藤さん', 'サトウ', NULL, 15, 'male', '福岡県糟屋郡１−１−１4', '7777777@gmail.com', '08011111114', '$2y$10$ZhItvoOmEgOywVZVFNZLRuB8fzK5Gfn7jZ2tSACn.708AfOYqr6UK', '2024-06-26 11:52:25'),
(5, '中田', 'ナカタ', NULL, 40, 'male', '福岡県糟屋郡１−１−16', '12345@gmail.com', '080111111116', '$2y$10$BeNebTsZgxjgJLY1VfF3QOzKXZ7M2slvSdeO4Hkz101.mAhleBWoG', '2024-06-27 09:32:41'),
(7, '三浦', 'ミウラ', NULL, 60, 'male', '福岡県糟屋郡１−１−16', '00000@gmail.com', '08011111115', '$2y$10$gdANsjTNUKHa0qtnhw8xqOZOz2c375K7mdLhEdV9Vx96yBD7jT8y2', '2024-06-27 11:49:59'),
(8, '城', 'ジョウ', NULL, 45, 'male', '福岡県糟屋郡１−１−16', '23456@gmail.com', '08011111115', '$2y$10$SdEls7Q5wPAN9t9eM556MuoKyCeScMnwAx3j3reeTZlgbNiumXp2G', '2024-06-28 07:29:07'),
(10, '望月秀人', 'モチヅキヒデト', NULL, 47, 'male', '東京都豊島区南長崎6-14-13-401', 'mochizukihideto02@gmail.com', '08050006102', '$2y$10$eZTwN./8WCCjl7.bveZvKOeDDEqa1h6KmtkLpieMcXunZl1myurGG', '2024-07-02 01:22:51'),
(11, '澤田', 'サワダ', 'さわちゃん', 46, 'female', '大森', 'oomori@gmail.com', '0801111130', '$2y$10$0J4qPyWQhoiuDaF.6g7HBO3p8XkfkSPHzi/wCgolh0A9sC9abhXTS', '2024-07-02 02:20:53'),
(12, '立花', 'タチバナ', '戦国最強の男', 1, 'male', '福岡県糟屋郡１−１−166', 'muneshige4@gmail.com', '08011111125', '$2y$10$tnuBKGa.mfOelJQAbwbcy.29ogo6rBBmMG4IIPcJCTvHsrk1CABLe', '2024-07-05 07:44:31'),
(13, '最上', 'モガミ', 'モガミ', 1, 'male', '秋田県', 'mogami@gmail.com', '08011111112', '$2y$10$6pH9.X8w0s.blVOF4w1hYOOuhhgUGEA/AxS9PE9fQhxD8WPbfJ3DC', '2024-07-07 00:57:06'),
(14, '$2y$10$hjifT4Q0EoBtyMOplZBAvuazqM1EWS6yoW6CYcXhpFpjsjmCLiAuK', '$2y$10$0IowaJZH9kHjtZRF4MKz5e3YMgDtt9aBesBpcAhmN.GS0.j5ynncS', 'グンシン', 40, 'male', '$2y$10$OBZ3apol7AApnJGU9OWq/ecFIHrTpHo7en1HtXzCdY1JOWaqyGsza', '$2y$10$WNTyBuEIk3twdhUC9WWcLeW.dqrg5YO7bidgZ9RaSPW24RwEhI27C', '$2y$10$Lsoi3lu2wHQWimV8dKqT8e058d60mc7DixiQdkTKbZOjZASm6LiBW', '$2y$10$kGlxme8Qozf35hQiPxuBsehJoBMzJBxQ8kactAnk9BXy733JWdBVy', '2024-07-11 01:43:44'),
(15, 'Y25Qd2dpSTl0ZHE2Q1lOTHAzR1Vndz09OjrlLRjFBihTrhvqvB3s2MEW', 'eDcvNkpnOXlhWmp1RHM4eWhLamE1TDk2Mm9FUko5cy9taytISG12c2FqZz06OojRPipNOFlvg0AOGGB2Wxs=', 'けんちゃん', 40, 'male', 'VjhkVVlyYU5Vb2NaSUJ4RFR5clJuQT09OjqTz9ej/K6WNzKh+TWl2Htd', 'S0trMTQ1ejRiTWpoSHNMYkZIQTFaQT09Ojqn1iu9/GD9Hc9PQCV70TEj', 'RFoyWjI2RSsvZXVaYzhvSHlxT0FYUT09OjqB3uqLTz2H/5THKjl/5LRx', '$2y$10$S8NysuWsZ2TvincBZrJYp.ALeeoUS0Zsf5QxHP1iEM1k2MVnDiQu6', '2024-07-11 11:18:28'),
(16, 'NW1aQTdkeG5pT3l3bXo3ekh3ckx3dz09OjrX0iLcXIokGw6tTRN+Vcjg', 'UUYxTE5xU0g1eWxWMW5odVRGYjdQQT09OjrcRfy/PJukkD8K1MwndVQw', 'アサダ', 7, 'male', 'T1hoRGNaSHFiNkJ5NUNhMHpVM0htUT09OjqIjmL0sDffsrbX99i+buQw', 'R0w0bEFiNEQ3NER6aUxzOUNuV1c2UT09OjqcKXwIU+0iz+Uco6ElTg7R', 'VEpsbGk0UWNPWFNwMWEwUDR1eTlIUT09Ojr+OXAeE2uFyVILgGFbVvxz', '$2y$10$MBGnjytw4kGuazZJ1SQAHui9PKe0ABFEunzy/3ELAAG3B45e8paQ2', '2024-07-11 11:46:23'),
(17, 'MzJiZnk1K211ak9RLzZ2eGJmalZlUT09OjqzSNpZgZs2f17ZmVoX/CUe', 'aGJrZnc1dXNvMG5PeWlxdlRpM0xTUT09Ojpc4R0LQNcGMJtWKtGWwiCp', '勝頼', 20, 'male', 'bExQVnphWk94VkJHN0dKNlkycDBPZz09OjoKSDKHoZsUzmbUF7IKnAh8', 'eDhEclNGOFYwalpyK1RSYkI2b2l6OVN5QWdodUkvdWJveXNITVVEbGV6VT06Oi5TleJICRBiB/D+EA2A03A=', 'alN0SGZQSkhiYkJDeWFZY1pZUG9jQT09OjocU2wdFyqFqzzspvuOn8Xk', '$2y$10$phkEiUHsHHwWSGwLYXMdLeNVxi6Bk8w4Xxaaf5MwoLQf22zdUB.2a', '2024-07-12 01:09:20'),
(18, 'ZWdsY0dJZGtvbWlKaG9BaHE5UCszQT09OjrTawTSYd8zMYNTXt6g1bvW', 'b21Iekc0Y3RwN2s4NGlkeXRiMWFsZGp2c1lWRnVxN0IwVC9PVVRGSjZkWT06OulQN7K4wVlwyoaSY2Q/TPI=', '真田昌幸', 50, 'male', 'TWZoT0ljczV0TThZZElFSEVBUUFmUT09OjocxPjbw33gDgKms/Q28i9x', 'aGJaWmFjR0g3SU9zRVlzeHJtSUFSTzFtdHZSbGJtWmxCbmJTcjA4U2gxRT06Oi9WxQIzKfkhe3SM1epy73o=', 'ZnhxWUJWNjE5TUgvZTBmMi9yZFJvdz09OjqRpAaJpQxUNnZm/uBOa5bk', '$2y$10$yyqNGrWcTThLOU9n/q17yOASqb6TqMG3hmRa/tkzD0exK6mQg6.CG', '2024-07-12 01:40:13'),
(19, 'RFFMQWxvS3ErU2pOUFBkenl2MFdpUT09Ojp7Nsm3MjFtFl0MynZG/1t1', 'SU5YRXpMUkZkQy9yQXdrOVlPMlRVUT09OjoY5SJ3HJ7aoLAHFK8U6IDh', 'ナナ', 25, 'female', 'QlNaVFluN3YxSHJaM3pDTHVQQU5EUT09Ojo9bzQdBV93CRMR0ZeuZMqv', 'MklDTkQrK0dtOVdDNGE5K0RNVERQdz09Ojrn3wdkeGUUpmIWyYW9YPCn', 'Q0NpWUNrRmlMV2lhRmdvVHkwcFRQQT09Ojrmej+Ksz42Ru6heob6Ggbg', '$2y$10$Ba0PgihsNLDDFGp4i2QYLuvFjx9ht/1DZ1ybiSUttLacxuRaUhtnG', '2024-07-12 01:50:45'),
(20, 'c01tV01lK2t5U1dNUnRKLzA2SmRvQT09OjqwV9h2Urg2D0z7YuFHgZSK', 'OUtIRUVvTjBBcUxCVjd0Q1FSMkdzUT09OjrmPf8KuU8cfM6cutC9qGXs', 'つばさ', 7, 'male', 'SWhpYXZMM05Gdml1SG5JY0lHSk0vZz09Ojr/cIEIaMGkBkoDGvpdHx5p', 'cFo4NzFSYnFzWU5DVThtYTdhRVMwcENpUUhtaVd0NnI1SnR4YzU4OGluOD06Ojn3E5dpKfS/mHNmJgIQ/YA=', 'YUZhbzJxcC9PNHJqUXFrSmtmRVdvQT09Ojrj8LUizHdJ8XfZEZ/UY8DZ', '$2y$10$8dzDUS1LAlM3WsCZDTQRtetUpkC4R0F3Ks9tbhGJbbDnyyR.xiMsK', '2024-07-12 02:00:15');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `CREATE TABLE IF NOT EXISTS stamps`
--
ALTER TABLE `CREATE TABLE IF NOT EXISTS stamps`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `CREATE TABLEstamps`
--
ALTER TABLE `CREATE TABLEstamps`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `diary`
--
ALTER TABLE `diary`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`date`);

--
-- テーブルのインデックス `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- テーブルのインデックス `stamps`
--
ALTER TABLE `stamps`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `stamp_continuity`
--
ALTER TABLE `stamp_continuity`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`stamp_id`,`date`),
  ADD KEY `stamp_id` (`stamp_id`);

--
-- テーブルのインデックス `stamp_usage`
--
ALTER TABLE `stamp_usage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_stamp_date` (`user_id`,`stamp_id`,`start_date`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `CREATE TABLE IF NOT EXISTS stamps`
--
ALTER TABLE `CREATE TABLE IF NOT EXISTS stamps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `CREATE TABLEstamps`
--
ALTER TABLE `CREATE TABLEstamps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `diary`
--
ALTER TABLE `diary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- テーブルの AUTO_INCREMENT `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `stamps`
--
ALTER TABLE `stamps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- テーブルの AUTO_INCREMENT `stamp_continuity`
--
ALTER TABLE `stamp_continuity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- テーブルの AUTO_INCREMENT `stamp_usage`
--
ALTER TABLE `stamp_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `diary`
--
ALTER TABLE `diary`
  ADD CONSTRAINT `diary_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `stamp_continuity`
--
ALTER TABLE `stamp_continuity`
  ADD CONSTRAINT `stamp_continuity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stamp_continuity_ibfk_2` FOREIGN KEY (`stamp_id`) REFERENCES `stamps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
