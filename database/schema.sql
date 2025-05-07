-- --------------------------------------------------------
-- Сервер:                       127.127.126.2
-- Версія сервера:               11.7.2-MariaDB - mariadb.org binary distribution
-- ОС сервера:                   Win64
-- HeidiSQL Версія:              12.10.0.7040
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for robert
CREATE DATABASE IF NOT EXISTS `robert` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin */;
USE `robert`;

-- Dumping structure for таблиця robert.languages
DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `code` char(2) NOT NULL,
  `title` varchar(50) NOT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table robert.languages: ~2 rows (приблизно)
INSERT INTO `languages` (`code`, `title`) VALUES
	('en', 'English'),
	('uk', 'Ukrainian');

-- Dumping structure for таблиця robert.segments
DROP TABLE IF EXISTS `segments`;
CREATE TABLE IF NOT EXISTS `segments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` char(2) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK__languages` (`language`),
  CONSTRAINT `FK__languages` FOREIGN KEY (`language`) REFERENCES `languages` (`code`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table robert.segments: ~0 rows (приблизно)

-- Dumping structure for таблиця robert.translations
DROP TABLE IF EXISTS `translations`;
CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `segment_id` int(11) NOT NULL,
  `language` char(2) NOT NULL,
  `status` enum('pending','complete') DEFAULT 'pending',
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_translations_segments` (`segment_id`),
  KEY `FK_translations_languages` (`language`),
  CONSTRAINT `FK_translations_languages` FOREIGN KEY (`language`) REFERENCES `languages` (`code`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_translations_segments` FOREIGN KEY (`segment_id`) REFERENCES `segments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table robert.translations: ~0 rows (приблизно)

-- Dumping structure for таблиця robert.versions
DROP TABLE IF EXISTS `versions`;
CREATE TABLE IF NOT EXISTS `versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translation_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK__translations` (`translation_id`),
  CONSTRAINT `FK__translations` FOREIGN KEY (`translation_id`) REFERENCES `translations` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table robert.versions: ~0 rows (приблизно)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
