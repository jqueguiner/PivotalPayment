-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           5.6.12-log - MySQL Community Server (GPL)
-- Serveur OS:                   Win64
-- HeidiSQL Version:             8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Export de la structure de table frq. transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `amount` float NOT NULL,
  `uniqueRef` varchar(50) COLLATE utf8_bin NOT NULL,
  `responseCode` varchar(50) COLLATE utf8_bin NOT NULL,
  `responseText` varchar(50) COLLATE utf8_bin NOT NULL,
  `approvalCode` varchar(50) COLLATE utf8_bin NOT NULL,
  `dateTime` varchar(50) COLLATE utf8_bin NOT NULL,
  `AVSResponse` varchar(50) COLLATE utf8_bin NOT NULL,
  `CVVResponse` varchar(50) COLLATE utf8_bin NOT NULL,
  `currency` varchar(3) COLLATE utf8_bin NOT NULL,
  `hash` varchar(50) COLLATE utf8_bin NOT NULL,
  `status` tinyint(1) NOT NULL,
  `error` varchar(50) COLLATE utf8_bin NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `payer` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- L'exportation de données n'été pas sélectionné.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
