
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `payment_amount` float NOT NULL,
  `payment_tax` float NOT NULL,
  `payment_notax` float NOT NULL,
  `payment_fee` float NOT NULL,
  `currency_code` varchar(3) COLLATE utf8_bin NOT NULL,
  `item_number` text COLLATE utf8_bin NOT NULL,
  `item_name` text COLLATE utf8_bin NOT NULL,
  `custom` text COLLATE utf8_bin NOT NULL,
  `receiver_email` text COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `address` varchar(255) COLLATE utf8_bin NOT NULL,
  `country` varchar(255) COLLATE utf8_bin NOT NULL,
  `city` varchar(255) COLLATE utf8_bin NOT NULL,
  `txn_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `payer_email` text COLLATE utf8_bin NOT NULL,
  `buyer_model` varchar(255) COLLATE utf8_bin NOT NULL,
  `buyer_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `receiverclub_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `amount` double NOT NULL,
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
  `season_id` bigint(20) NOT NULL,
  `sandbox` tinyint(1) NOT NULL,
  `url_requester` longtext COLLATE utf8_bin NOT NULL,
  `paypallcalback` longtext COLLATE utf8_bin NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `payer` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;