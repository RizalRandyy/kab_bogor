/*
SQLyog Ultimate v12.4.3 (64 bit)
MySQL - 10.4.20-MariaDB : Database - db_kab_bogor
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_kab_bogor` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `db_kab_bogor`;

/*Table structure for table `tb_jenis_item` */

DROP TABLE IF EXISTS `tb_jenis_item`;

CREATE TABLE `tb_jenis_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idKelompokItem` int(11) DEFAULT NULL,
  `idJenisBarang` char(6) DEFAULT NULL,
  `kodeKelompok` char(15) DEFAULT NULL,
  `NamaJenis` varchar(50) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kodeKelompok` (`idKelompokItem`,`idJenisBarang`)
) ENGINE=InnoDB AUTO_INCREMENT=5026 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_jenis_item` */

insert  into `tb_jenis_item`(`id`,`idKelompokItem`,`idJenisBarang`,`kodeKelompok`,`NamaJenis`,`updated_by`,`updated_at`) values 
(1,1,'01','A001.01','Mandor',1,'2023-10-12 15:41:00'),
(2,1,'02','A001.02','Tukang',1,'2023-10-12 15:13:40'),
(4,2,'02','A002.02','Batu Kali',1,'2023-10-12 15:14:19'),
(6,2,'01','A002.01','Batu Split',1,'2023-10-13 22:50:44'),
(7,3,'01','A003.01','Besi Hollow',1,'2023-10-13 23:13:37'),
(8,2,'03','A002.03','Batu Krikil',1,'2023-11-12 14:54:24'),
(12,14,'01','K001.01','Kaca Bening',1,'2023-11-28 17:07:10');

/*Table structure for table `tb_kegiatan` */

DROP TABLE IF EXISTS `tb_kegiatan`;

CREATE TABLE `tb_kegiatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idKegiatan` char(6) DEFAULT NULL,
  `UraianKegiatan` varchar(255) DEFAULT NULL,
  `satuan` char(8) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_kegiatan` */

insert  into `tb_kegiatan`(`id`,`idKegiatan`,`UraianKegiatan`,`satuan`,`updated_by`,`updated_at`) values 
(6,'A0001','Pembuatan Beton','m2',1,'2023-11-11 18:29:41'),
(7,'A0002','Pembuatan Rangka Baja','m2',1,'2023-11-11 18:29:56');

/*Table structure for table `tb_kelompok_item` */

DROP TABLE IF EXISTS `tb_kelompok_item`;

CREATE TABLE `tb_kelompok_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `IdKelItem` char(6) DEFAULT NULL,
  `UraianKelompok` varchar(100) DEFAULT NULL,
  `tipe` char(4) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kodeKelompok` (`IdKelItem`)
) ENGINE=InnoDB AUTO_INCREMENT=5028 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_kelompok_item` */

insert  into `tb_kelompok_item`(`id`,`IdKelItem`,`UraianKelompok`,`tipe`,`updated_by`,`updated_at`) values 
(1,'A001','Pekerja','SBU',1,'2023-10-13 21:28:13'),
(2,'A002','Batu','SSH',1,'2023-10-13 21:29:31'),
(3,'A003','Besi','SSH',1,'2023-10-12 13:10:26'),
(4,'A004','Aspal','SSH',1,'2023-10-13 22:53:24'),
(7,'A005','Pasir','SSH',1,'2023-10-29 20:11:25'),
(14,'K001','Kaca','SSH',1,'2023-11-29 10:59:39');

/*Table structure for table `tb_lokasi` */

DROP TABLE IF EXISTS `tb_lokasi`;

CREATE TABLE `tb_lokasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_toko` varchar(225) DEFAULT NULL,
  `tautan` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_lokasi` */

insert  into `tb_lokasi`(`id`,`nama_toko`,`tautan`,`updated_by`,`updated_at`) values 
(1,'TB. Sinar Abadi Home Care','https://maps.app.goo.gl/uWzd197k7D9VJZwd7',1,'2023-11-30 10:26:04');

/*Table structure for table `tb_manajemen_dashboard` */

DROP TABLE IF EXISTS `tb_manajemen_dashboard`;

CREATE TABLE `tb_manajemen_dashboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idItem` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_manajemen_dashboard` */

insert  into `tb_manajemen_dashboard`(`id`,`idItem`,`updated_by`,`updated_at`) values 
(1,'[\"1\",\"2\",\"3\",\"5\",\"7\",\"10\",\"8\",\"9\"]',1,'2023-11-20 17:05:10');

/*Table structure for table `tb_spesifikasi_item` */

DROP TABLE IF EXISTS `tb_spesifikasi_item`;

CREATE TABLE `tb_spesifikasi_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idJenisItem` int(11) DEFAULT NULL,
  `idSpesifikasi` char(6) DEFAULT NULL,
  `kodeKelompok` char(15) DEFAULT NULL,
  `UraianSpesifikasi` varchar(50) DEFAULT NULL,
  `satuan` char(15) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kodeKelompok` (`idJenisItem`,`idSpesifikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=5030 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_spesifikasi_item` */

insert  into `tb_spesifikasi_item`(`id`,`idJenisItem`,`idSpesifikasi`,`kodeKelompok`,`UraianSpesifikasi`,`satuan`,`updated_by`,`updated_at`) values 
(1,1,'01','A001.01.01','Mandor Lapangan','Borongan',1,'2023-11-11 15:01:04'),
(2,1,'02','A001.01.02','Mandor Lapangan','Harian',1,'2023-11-11 15:01:21'),
(3,2,'01','A001.02.01','Jasa Tukang','Borongan',1,'2023-10-13 21:28:13'),
(5,2,'02','A001.02.02','Jasa Tukang','Harian',1,'2023-10-13 21:28:13'),
(7,6,'01','A002.01.01','Batu split ½ berat 100 gram','100 gram',1,'2023-10-13 22:51:03'),
(8,7,'01','A003.01.01','15 x 30 x 0,60 mm','6 m',1,'2023-10-13 23:14:04'),
(9,7,'02','A003.01.02','15 x 30 x 0,80 mm','6 m',1,'2023-10-13 23:24:09'),
(10,4,'02','A002.02.02','Batu Kali 57','Dum',1,'2023-11-12 14:57:16'),
(16,12,'01','K001.01.01','Kaca Bening Tebal 3mm','m2',1,'2023-11-28 17:07:10'),
(17,12,'02','K001.01.02','Kaca Bening Tebal 5mm','m2',1,'2023-11-28 17:07:10');

/*Table structure for table `tb_standar_biaya` */

DROP TABLE IF EXISTS `tb_standar_biaya`;

CREATE TABLE `tb_standar_biaya` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idASB` char(6) DEFAULT NULL,
  `UraianKegiatan` varchar(255) DEFAULT NULL,
  `satuan` char(8) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_standar_biaya` */

insert  into `tb_standar_biaya`(`id`,`idASB`,`UraianKegiatan`,`satuan`,`updated_by`,`updated_at`) values 
(2,'ASB002','Pembuatan Flyover','m2',1,'2023-11-10 10:34:40'),
(3,'ASB001','Pembuatan Jembatan','m2',1,'2023-11-10 10:35:56');

/*Table structure for table `tb_standar_biaya_thn` */

DROP TABLE IF EXISTS `tb_standar_biaya_thn`;

CREATE TABLE `tb_standar_biaya_thn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idASB` char(6) DEFAULT NULL,
  `tahunASB` char(4) DEFAULT NULL,
  `kodeKelompok` char(6) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_standar_biaya_thn` */

insert  into `tb_standar_biaya_thn`(`id`,`idASB`,`tahunASB`,`kodeKelompok`,`updated_by`,`updated_at`) values 
(1,'3','2023','ASB001',1,'2023-11-12 15:03:48'),
(2,'3','2022','ASB001',1,'2023-11-12 15:04:04'),
(3,'2','2023','ASB002',1,'2023-11-12 15:04:12'),
(4,'2','2022','ASB002',1,'2023-11-12 15:06:29');

/*Table structure for table `tb_standar_biaya_thn_detail` */

DROP TABLE IF EXISTS `tb_standar_biaya_thn_detail`;

CREATE TABLE `tb_standar_biaya_thn_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_standar_biaya_thn` int(11) DEFAULT NULL,
  `id_thn_pekerjaan_detail` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_standar_biaya_thn_detail` */

insert  into `tb_standar_biaya_thn_detail`(`id`,`id_standar_biaya_thn`,`id_thn_pekerjaan_detail`,`updated_by`,`updated_at`) values 
(3,1,'[\"14\",\"10\"]',1,'2023-11-13 15:56:51'),
(4,1,'[]',1,'2023-11-13 16:00:03');

/*Table structure for table `tb_thn_harga` */

DROP TABLE IF EXISTS `tb_thn_harga`;

CREATE TABLE `tb_thn_harga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idSpesifikasi` int(11) DEFAULT NULL,
  `kodeKelompok` char(15) DEFAULT NULL,
  `TahunHarga` char(4) DEFAULT NULL,
  `harga` double DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kodeKelompok` (`idSpesifikasi`,`TahunHarga`)
) ENGINE=InnoDB AUTO_INCREMENT=5042 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_thn_harga` */

insert  into `tb_thn_harga`(`id`,`idSpesifikasi`,`kodeKelompok`,`TahunHarga`,`harga`,`updated_by`,`updated_at`) values 
(1,1,'A001.01.01','2023',5000000,1,'2023-10-18 19:10:47'),
(2,1,'A001.01.01','2022',4000000,1,'2023-10-12 16:47:46'),
(3,2,'A001.01.02','2022',150000,1,'2023-10-12 16:48:29'),
(4,2,'A001.01.02','2023',200000,1,'2023-10-13 09:33:30'),
(5,3,'A001.02.01','2023',4500000,1,'2023-10-13 09:20:15'),
(6,3,'A001.02.01','2022',4300000,1,'2023-10-13 09:20:15'),
(9,7,'A002.01.01','2023',1500000,1,'2023-10-13 22:51:27'),
(10,7,'A002.01.01','2022',1250000,1,'2023-11-20 16:43:58'),
(12,8,'A003.01.01','2022',36000,1,'2023-10-13 23:17:34'),
(13,9,'A003.01.02','2023',49000,1,'2023-10-13 23:25:14'),
(14,9,'A003.01.02','2022',47000,1,'2023-10-13 23:25:34'),
(15,10,'A002.02.02','2023',1000000,1,'2023-11-12 14:57:16'),
(16,10,'A002.02.02','2022',900000,1,'2023-11-12 14:57:16'),
(18,5,'A001.02.02','2023',160000,1,'2023-11-05 19:20:43'),
(19,5,'A001.02.02','2022',150000,1,'2023-11-11 15:07:12'),
(20,7,'A002.01.01','2021',1000000,1,'2023-11-20 16:43:45'),
(27,16,'K001.01.01','2022',85000,1,'2023-11-28 17:07:10'),
(28,16,'K001.01.01','2023',90000,1,'2023-11-28 17:07:10'),
(29,17,'K001.01.02','2022',90000,1,'2023-11-28 17:07:10');

/*Table structure for table `tb_thn_kegiatan` */

DROP TABLE IF EXISTS `tb_thn_kegiatan`;

CREATE TABLE `tb_thn_kegiatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idKegiatan` char(6) DEFAULT NULL,
  `tahunPekerjaan` char(4) DEFAULT NULL,
  `kodeKelompok` char(6) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_thn_kegiatan` */

insert  into `tb_thn_kegiatan`(`id`,`idKegiatan`,`tahunPekerjaan`,`kodeKelompok`,`updated_by`,`updated_at`) values 
(16,'6','2023','A0001',1,'2023-11-11 14:59:44'),
(17,'6','2022','A0001',1,'2023-11-11 14:59:55'),
(18,'6','2021','A0001',1,'2023-11-11 15:00:01'),
(19,'7','2023','A0002',1,'2023-11-11 15:00:06'),
(20,'7','2022','A0002',1,'2023-11-11 15:00:16'),
(21,'7','2021','A0002',1,'2023-11-11 15:00:22');

/*Table structure for table `tb_thn_pekerjaan_detail` */

DROP TABLE IF EXISTS `tb_thn_pekerjaan_detail`;

CREATE TABLE `tb_thn_pekerjaan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_thn_kegiatan` char(4) DEFAULT NULL,
  `id_thn_harga` text DEFAULT NULL,
  `total_item` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_thn_pekerjaan_detail` */

insert  into `tb_thn_pekerjaan_detail`(`id`,`id_thn_kegiatan`,`id_thn_harga`,`total_item`,`updated_by`,`updated_at`) values 
(10,'19','[\"4\",\"18\",\"13\"]','[\"30\",\"30\",\"100\"]',1,'2023-11-11 19:32:21'),
(11,'17','[\"2\",\"6\",\"10\",\"16\"]','[\"1\",\"1\",\"50\",\"70\"]',1,'2023-11-11 19:34:06'),
(12,'20','[\"3\",\"19\",\"14\"]','[\"30\",\"30\",\"100\"]',1,'2023-11-11 19:32:04'),
(14,'16','[\"1\",\"5\",\"9\",\"15\"]','[\"1\",\"1\",\"50\",\"70\"]',1,'2023-11-13 15:48:49'),
(16,'20','[]','[\"1\"]',1,'2023-11-13 16:06:59');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick_name` varchar(10) DEFAULT NULL,
  `full_name` varchar(30) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `phone` bigint(15) DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `email_kode` varchar(10) DEFAULT NULL,
  `email_active` int(11) DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `duplicate` (`email`,`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

/*Data for the table `users` */

insert  into `users`(`id`,`nick_name`,`full_name`,`email`,`phone`,`photo`,`password`,`role_id`,`status`,`email_kode`,`email_active`,`last_login`,`created_at`) values 
(1,'Admin','Administrator','trisnatya@gmail.com',628978566779,'','$2a$08$MzAzZGViY2NkMTdlZjNlO.2iXgRjLGsXO4NcjghbnEmKQD0wa52dS',1,1,NULL,1,'2023-11-30 10:07:16','2023-09-09 21:57:09'),
(7,'Irwin','Irwin','asd@asd.hd',6289785667795,'','$2a$08$YmZmOThlNjgwZDBkZDBjM.WZO6HG44xIkbd3Sdha.zLNdzFr/TYo2',8,1,NULL,1,'2023-11-27 19:11:51',NULL),
(8,'Admin','Administrator','admin@admin.com',6289123123,'','$2a$08$ZWEyNTk3YWFlM2I4N2FmYOCxqyHeR/hvCSmK2GWzjIOuokdKIxcla',2,1,NULL,1,'2023-11-29 21:18:17',NULL),
(9,'trisnatya','Trisnatya Mahardhika','trisnatya.mahardhika@gmail.com',6289785667799,'','$2a$08$MzBlZTdmY2IzMjU5NzIwYu9hmlEvo.ikB.FbdAeFDQZd5GaD331e.',3,1,NULL,1,'2023-10-30 21:21:23','2023-10-23 04:18:47');

/*Table structure for table `users_log` */

DROP TABLE IF EXISTS `users_log`;

CREATE TABLE `users_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `browser` text DEFAULT NULL,
  `folder_access` varchar(100) DEFAULT NULL,
  `controller_name` varchar(100) DEFAULT NULL,
  `methode` varchar(100) DEFAULT NULL,
  `access_time` datetime DEFAULT NULL,
  `post_data` text DEFAULT NULL,
  `get_data` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10423 DEFAULT CHARSET=utf8mb4;

/*Data for the table `users_log` */

insert  into `users_log`(`id`,`users_id`,`name`,`email`,`ip`,`browser`,`folder_access`,`controller_name`,`methode`,`access_time`,`post_data`,`get_data`) values 
(10223,NULL,NULL,NULL,'192.168.1.102','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','login','login','masuk','2023-11-27 14:47:11','[]','[]'),
(10224,NULL,NULL,NULL,'192.168.1.102','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','login','login','masuk','2023-11-27 14:47:55','[]','[]'),
(10225,7,'Irwin','asd@asd.hd','192.168.1.102','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-27 14:47:57','[]','[]'),
(10226,7,'Irwin','asd@asd.hd','192.168.1.102','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','logout','login','logout','2023-11-27 14:48:16','[]','[]'),
(10227,NULL,NULL,NULL,'192.168.1.102','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','login','login','masuk','2023-11-27 14:48:35','[]','[]'),
(10228,8,'Administrator','admin@admin.com','192.168.1.102','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-27 14:48:37','[]','[]'),
(10229,8,'Administrator','admin@admin.com','192.168.1.102','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','logout','login','logout','2023-11-27 14:49:10','[]','[]'),
(10230,NULL,NULL,NULL,'192.168.1.102','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','login','login','masuk','2023-11-27 14:49:20','[]','[]'),
(10231,NULL,NULL,NULL,'192.168.1.102','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','login','login','masuk','2023-11-27 14:50:10','[]','[]'),
(10232,NULL,NULL,NULL,'192.168.1.102','PostmanRuntime/7.35.0','login','login','masuk','2023-11-27 15:02:10','[]','{\"email\":\"test\",\"password\":\"test\"}'),
(10233,NULL,NULL,NULL,'192.168.1.102','PostmanRuntime/7.35.0','login','login','masuk','2023-11-27 15:03:13','[]','[]'),
(10234,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-27 16:49:11','[]','[]'),
(10235,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-27 19:10:51','[]','[]'),
(10236,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-27 19:11:32','[]','[]'),
(10237,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','login','login','masuk','2023-11-27 19:11:51','[]','[]'),
(10238,7,'Irwin','asd@asd.hd','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-27 19:11:55','[]','[]'),
(10239,7,'Irwin','asd@asd.hd','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','perkiraan_hps','perkiraan_hps','kegiatan','2023-11-27 19:12:02','[]','[]'),
(10240,7,'Irwin','asd@asd.hd','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','hspk','hspk','getData','2023-11-27 19:12:22','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10241,7,'Irwin','asd@asd.hd','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','hspk','hspk','getById','2023-11-27 19:12:39','[]','{\"id\":\"bEpsNXE0SDJpT3YwVUNpUnlrclFhdz09\"}'),
(10242,7,'Irwin','asd@asd.hd','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-27 19:13:57','[]','[]'),
(10243,7,'Irwin','asd@asd.hd','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','logout','login','logout','2023-11-27 19:26:27','[]','[]'),
(10244,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','login','login','masuk','2023-11-27 19:27:08','[]','[]'),
(10245,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-27 19:27:12','[]','[]'),
(10246,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','jenis_item','jenis_item','getData','2023-11-27 19:27:26','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10247,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-27 19:41:30','[]','[]'),
(10248,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','hspk','hspk','getData','2023-11-27 19:41:37','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10249,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-27 19:41:48','[]','[]'),
(10250,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-27 19:41:51','[]','[]'),
(10251,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','hspk','hspk','getData','2023-11-27 19:41:58','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10252,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','hspk','hspk','getById','2023-11-27 19:42:01','[]','{\"id\":\"bEpsNXE0SDJpT3YwVUNpUnlrclFhdz09\"}'),
(10253,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','perkiraan_hps','perkiraan_hps','kegiatan','2023-11-27 19:42:11','[]','[]'),
(10254,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-27 19:43:53','[]','[]'),
(10255,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-27 19:44:01','[]','[]'),
(10256,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-27 19:44:18','[]','[]'),
(10257,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-27 19:44:19','[]','[]'),
(10258,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','jenis_item','jenis_item','getData','2023-11-27 19:44:23','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10259,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-27 19:45:16','[]','[]'),
(10260,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','perkiraan_hps','perkiraan_hps','kegiatan','2023-11-27 19:45:34','[]','[]'),
(10261,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 19:46:14','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10262,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','hspk','hspk','getData','2023-11-27 19:46:19','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10263,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','hspk','hspk','getById','2023-11-27 19:59:44','[]','{\"id\":\"bEpsNXE0SDJpT3YwVUNpUnlrclFhdz09\"}'),
(10264,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','kegiatan_hspk_detail','kegiatan_hspk_detail','getData','2023-11-27 20:01:13','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10265,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:05:53','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10266,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','hspk','hspk','getById','2023-11-27 20:09:01','[]','{\"id\":\"bEpsNXE0SDJpT3YwVUNpUnlrclFhdz09\"}'),
(10267,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','form','2023-11-27 20:09:05','[]','{\"id\":\"L2ZHKzU1c1p0Mzlpc0J2ZmF0UDJiQT09\"}'),
(10268,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getById','2023-11-27 20:09:06','[]','{\"id\":\"L2ZHKzU1c1p0Mzlpc0J2ZmF0UDJiQT09\"}'),
(10269,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','kel_spesifikasi','2023-11-27 20:09:06','[]','[]'),
(10270,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:09:14','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10271,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','form','2023-11-27 20:09:21','[]','{\"id\":\"L2ZHKzU1c1p0Mzlpc0J2ZmF0UDJiQT09\"}'),
(10272,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getById','2023-11-27 20:09:21','[]','{\"id\":\"L2ZHKzU1c1p0Mzlpc0J2ZmF0UDJiQT09\"}'),
(10273,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','kel_spesifikasi','2023-11-27 20:09:21','[]','[]'),
(10274,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:09:40','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10275,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','user_role','user_role','get_role','2023-11-27 20:12:04','[]','{\"column\":\"true\",\"keyword\":\"{}\",\"limit\":\"10\",\"page\":\"1\"}'),
(10276,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','user_role','user_role','edit_role','2023-11-27 20:12:07','[]','[]'),
(10277,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','user_role','user_role','edit_role','2023-11-27 20:12:17','{\"role_name\":\"admin\",\"access\":{\"jenis_item\":{\"jenis_item\":\"on\",\"accessadd_jenis_item\":\"on\",\"accessedit_jenis_item\":\"on\",\"accessdelete_jenis_item\":\"on\"},\"kegiatan_asb\":{\"kegiatan_asb\":\"on\",\"accessadd_kegiatan_asb\":\"on\",\"accessedit_kegiatan_asb\":\"on\",\"accessdelete_kegiatan_asb\":\"on\"},\"kegiatan_asb_detail\":{\"kegiatan_asb_detail\":\"on\",\"accessadd_kegiatan_asb_detail\":\"on\",\"accessedit_kegiatan_asb_detail\":\"on\",\"accessdelete_kegiatan_asb_detail\":\"on\"},\"kegiatan_hspk\":{\"kegiatan_hspk\":\"on\",\"accessadd_kegiatan_hspk\":\"on\",\"accessedit_kegiatan_hspk\":\"on\",\"accessdelete_kegiatan_hspk\":\"on\"},\"kegiatan_hspk_detail\":{\"kegiatan_hspk_detail\":\"on\",\"accessadd_kegiatan_hspk_detail\":\"on\",\"accessedit_kegiatan_hspk_detail\":\"on\",\"accessdelete_kegiatan_hspk_detail\":\"on\"},\"kelompok_item\":{\"kelompok_item\":\"on\",\"accessadd_kelompok_item\":\"on\",\"accessedit_kelompok_item\":\"on\",\"accessdelete_kelompok_item\":\"on\"},\"manajemen_dashboard\":{\"manajemen_dashboard\":\"on\",\"accessadd_manajemen_dashboard\":\"on\",\"accessedit_manajemen_dashboard\":\"on\",\"accessdelete_manajemen_dashboard\":\"on\"},\"perkiraan_hps\":{\"perkiraan_hps\":\"off\",\"accessadd_perkiraan_hps\":\"off\",\"accessedit_perkiraan_hps\":\"off\",\"accessdelete_perkiraan_hps\":\"off\"},\"spesifikasi_harga\":{\"spesifikasi_harga\":\"on\",\"accessadd_spesifikasi_harga\":\"on\",\"accessedit_spesifikasi_harga\":\"on\",\"accessdelete_spesifikasi_harga\":\"on\"},\"spesifikasi_item\":{\"spesifikasi_item\":\"on\",\"accessadd_spesifikasi_item\":\"on\",\"accessedit_spesifikasi_item\":\"on\",\"accessdelete_spesifikasi_item\":\"on\"},\"ssh\":{\"ssh\":\"on\",\"accessadd_ssh\":\"on\",\"accessedit_ssh\":\"on\",\"accessdelete_ssh\":\"on\"},\"tahun_kegiatan_asb\":{\"tahun_kegiatan_asb\":\"on\",\"accessadd_tahun_kegiatan_asb\":\"on\",\"accessedit_tahun_kegiatan_asb\":\"on\",\"accessdelete_tahun_kegiatan_asb\":\"on\"},\"tahun_kegiatan_hspk\":{\"tahun_kegiatan_hspk\":\"on\",\"accessadd_tahun_kegiatan_hspk\":\"on\",\"accessedit_tahun_kegiatan_hspk\":\"on\",\"accessdelete_tahun_kegiatan_hspk\":\"on\"},\"user_log\":{\"user_log\":\"on\"},\"user_manage\":{\"user_manage\":\"on\",\"accessadd_user_manage\":\"on\",\"accessedit_user_manage\":\"on\",\"accessdelete_user_manage\":\"on\"},\"user_role\":{\"user_role\":\"on\",\"accessadd_user_role\":\"on\",\"accessedit_user_role\":\"on\",\"accessdelete_user_role\":\"on\"}}}','[]'),
(10278,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','user_role','user_role','get_role','2023-11-27 20:12:18','[]','{\"column\":\"true\",\"keyword\":\"{}\",\"limit\":\"10\",\"page\":\"1\"}'),
(10279,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','user_role','user_role','edit_role','2023-11-27 20:13:58','[]','[]'),
(10280,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','user_role','user_role','edit_role','2023-11-27 20:14:02','{\"role_name\":\"admin\",\"access\":{\"jenis_item\":{\"jenis_item\":\"on\",\"accessadd_jenis_item\":\"on\",\"accessedit_jenis_item\":\"on\",\"accessdelete_jenis_item\":\"on\"},\"kegiatan_asb\":{\"kegiatan_asb\":\"on\",\"accessadd_kegiatan_asb\":\"on\",\"accessedit_kegiatan_asb\":\"on\",\"accessdelete_kegiatan_asb\":\"on\"},\"kegiatan_asb_detail\":{\"kegiatan_asb_detail\":\"on\",\"accessadd_kegiatan_asb_detail\":\"on\",\"accessedit_kegiatan_asb_detail\":\"on\",\"accessdelete_kegiatan_asb_detail\":\"on\"},\"kegiatan_hspk\":{\"kegiatan_hspk\":\"on\",\"accessadd_kegiatan_hspk\":\"on\",\"accessedit_kegiatan_hspk\":\"on\",\"accessdelete_kegiatan_hspk\":\"on\"},\"kegiatan_hspk_detail\":{\"kegiatan_hspk_detail\":\"on\",\"accessadd_kegiatan_hspk_detail\":\"on\",\"accessedit_kegiatan_hspk_detail\":\"on\",\"accessdelete_kegiatan_hspk_detail\":\"on\"},\"kelompok_item\":{\"kelompok_item\":\"on\",\"accessadd_kelompok_item\":\"on\",\"accessedit_kelompok_item\":\"on\",\"accessdelete_kelompok_item\":\"on\"},\"manajemen_dashboard\":{\"manajemen_dashboard\":\"on\",\"accessadd_manajemen_dashboard\":\"on\",\"accessedit_manajemen_dashboard\":\"on\",\"accessdelete_manajemen_dashboard\":\"on\"},\"perkiraan_hps\":{\"perkiraan_hps\":\"off\",\"accessadd_perkiraan_hps\":\"off\",\"accessedit_perkiraan_hps\":\"off\",\"accessdelete_perkiraan_hps\":\"off\"},\"spesifikasi_harga\":{\"spesifikasi_harga\":\"on\",\"accessadd_spesifikasi_harga\":\"on\",\"accessedit_spesifikasi_harga\":\"on\",\"accessdelete_spesifikasi_harga\":\"on\"},\"spesifikasi_item\":{\"spesifikasi_item\":\"on\",\"accessadd_spesifikasi_item\":\"on\",\"accessedit_spesifikasi_item\":\"on\",\"accessdelete_spesifikasi_item\":\"on\"},\"tahun_kegiatan_asb\":{\"tahun_kegiatan_asb\":\"on\",\"accessadd_tahun_kegiatan_asb\":\"on\",\"accessedit_tahun_kegiatan_asb\":\"on\",\"accessdelete_tahun_kegiatan_asb\":\"on\"},\"tahun_kegiatan_hspk\":{\"tahun_kegiatan_hspk\":\"on\",\"accessadd_tahun_kegiatan_hspk\":\"on\",\"accessedit_tahun_kegiatan_hspk\":\"on\",\"accessdelete_tahun_kegiatan_hspk\":\"on\"},\"user_log\":{\"user_log\":\"on\"},\"user_manage\":{\"user_manage\":\"on\",\"accessadd_user_manage\":\"on\",\"accessedit_user_manage\":\"on\",\"accessdelete_user_manage\":\"on\"},\"user_role\":{\"user_role\":\"on\",\"accessadd_user_role\":\"on\",\"accessedit_user_role\":\"on\",\"accessdelete_user_role\":\"on\"}}}','[]'),
(10281,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','user_role','user_role','get_role','2023-11-27 20:14:03','[]','{\"column\":\"true\",\"keyword\":\"{}\",\"limit\":\"10\",\"page\":\"1\"}'),
(10282,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:15:46','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10283,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:16:11','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10284,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:16:33','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10285,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:16:45','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10286,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','hspk','hspk','getData','2023-11-27 20:18:12','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10287,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:18:15','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10288,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getById','2023-11-27 20:18:20','[]','{\"id\":\"L2ZHKzU1c1p0Mzlpc0J2ZmF0UDJiQT09\"}'),
(10289,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:18:57','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10290,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:20:14','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10291,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:20:19','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10292,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','logout','login','logout','2023-11-27 20:20:24','[]','[]'),
(10293,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-27 20:20:28','[]','[]'),
(10294,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-27 20:20:41','[]','[]'),
(10295,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-27 20:20:43','[]','[]'),
(10296,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:20:49','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10297,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:20:58','[]','{\"keyword\":\"{\\\"harga\\\":\\\"500000\\\"}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10298,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:21:06','[]','{\"keyword\":\"{\\\"harga\\\":\\\"50000\\\"}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10299,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:21:06','[]','{\"keyword\":\"{\\\"harga\\\":\\\"50000\\\"}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10300,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:21:15','[]','{\"keyword\":\"{\\\"harga\\\":\\\"\\\"}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10301,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:21:18','[]','{\"keyword\":\"{\\\"harga\\\":\\\"\\\",\\\"TahunHarga\\\":\\\"202\\\"}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10302,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:21:21','[]','{\"keyword\":\"{\\\"harga\\\":\\\"\\\",\\\"TahunHarga\\\":\\\"2022\\\"}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10303,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:21:27','[]','{\"keyword\":\"{\\\"harga\\\":\\\"4.000\\\",\\\"TahunHarga\\\":\\\"2022\\\"}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10304,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:21:33','[]','{\"keyword\":\"{\\\"harga\\\":\\\"4000\\\",\\\"TahunHarga\\\":\\\"2022\\\"}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10305,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','spesifikasi_harga','spesifikasi_harga','getData','2023-11-27 20:21:39','[]','{\"keyword\":\"{\\\"harga\\\":\\\"4000\\\",\\\"TahunHarga\\\":\\\"\\\"}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10306,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:21:59','[]','{\"keyword\":\"{\\\"harga\\\":\\\"5000\\\"}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10307,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:22:06','[]','{\"keyword\":\"{\\\"harga\\\":\\\"5000000\\\"}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10308,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:22:08','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10309,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:23:39','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10310,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:23:50','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10311,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:24:57','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10312,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:25:06','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10313,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:25:10','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10314,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-27 20:25:14','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"2\"}'),
(10315,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:29:09','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10316,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:29:33','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10317,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','kegiatan_asb_detail','kegiatan_asb_detail','getData','2023-11-27 20:29:42','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10318,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:31:29','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10319,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','kegiatan_asb_detail','kegiatan_asb_detail','form','2023-11-27 20:31:45','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10320,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','kegiatan_asb_detail','kegiatan_asb_detail','kegiatan','2023-11-27 20:31:47','[]','[]'),
(10321,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','kegiatan_asb_detail','kegiatan_asb_detail','getById','2023-11-27 20:31:48','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10322,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','kegiatan_asb_detail','kegiatan_asb_detail','kegiatan','2023-11-27 20:31:48','[]','[]'),
(10323,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','hspk','hspk','getById','2023-11-27 20:31:51','[]','{\"id\":\"bEpsNXE0SDJpT3YwVUNpUnlrclFhdz09\"}'),
(10324,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:36:26','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10325,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:36:28','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10326,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:38:14','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10327,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:38:21','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10328,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:38:28','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10329,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:38:46','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10330,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:38:49','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10331,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:39:07','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10332,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:40:52','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10333,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:40:53','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10334,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:44:18','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10335,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:45:04','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10336,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:45:08','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10337,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:47:43','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10338,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:47:45','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10339,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:49:01','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10340,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:49:04','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10341,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:49:26','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10342,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:53:38','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10343,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:53:41','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10344,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','kegiatan_asb_detail','kegiatan_asb_detail','form','2023-11-27 20:54:33','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10345,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:54:38','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10346,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','kegiatan_asb_detail','kegiatan_asb_detail','kegiatan','2023-11-27 20:54:40','[]','[]'),
(10347,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:54:41','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10348,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','kegiatan_asb_detail','kegiatan_asb_detail','getById','2023-11-27 20:54:42','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10349,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','kegiatan_asb_detail','kegiatan_asb_detail','kegiatan','2023-11-27 20:54:42','[]','[]'),
(10350,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 20:55:13','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10351,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 20:55:15','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10352,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 21:19:01','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10353,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 21:19:03','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10354,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 21:19:55','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10355,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 21:19:58','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10356,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getData','2023-11-27 21:20:13','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10357,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','asb','asb','getById','2023-11-27 21:20:15','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10358,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-28 09:34:12','[]','[]'),
(10359,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-28 09:34:15','[]','[]'),
(10360,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-28 09:36:21','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10361,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','ssh','ssh','getData','2023-11-28 09:36:38','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10362,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-28 13:40:21','[]','[]'),
(10363,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-28 13:46:39','[]','[]'),
(10364,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-28 13:46:59','[]','[]'),
(10365,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-28 13:47:00','[]','[]'),
(10366,1,'Administrator','trisnatya@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-28 13:47:30','[]','[]'),
(10367,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-28 13:50:00','[]','[]'),
(10368,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-28 13:50:25','[]','[]'),
(10369,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-28 19:27:54','[]','[]'),
(10370,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-28 19:28:27','[]','[]'),
(10371,NULL,NULL,NULL,'192.168.100.50','PostmanRuntime/7.35.0','login','login','masuk','2023-11-28 19:46:45','[]','[]'),
(10372,NULL,NULL,NULL,'192.168.100.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-28 19:49:19','[]','[]'),
(10373,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-28 19:50:23','[]','[]'),
(10374,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-28 19:50:47','[]','[]'),
(10375,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','login','login','masuk','2023-11-28 19:51:45','[]','[]'),
(10376,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-28 19:51:48','[]','[]'),
(10377,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','ssh','ssh','getData','2023-11-28 19:51:56','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10378,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','ssh','ssh','getData','2023-11-28 19:52:33','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"2\"}'),
(10379,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','perkiraan_hps','perkiraan_hps','kegiatan','2023-11-28 19:52:56','[]','[]'),
(10380,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','asb','asb','getData','2023-11-28 19:54:42','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10381,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','asb','asb','getById','2023-11-28 19:54:54','[]','{\"id\":\"R3FvU29PaEpJMXVPMVp6STJvQWxzdz09\"}'),
(10382,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','jenis_item','jenis_item','getData','2023-11-28 19:55:12','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10383,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','kelompok_item','kelompok_item','getData','2023-11-28 19:55:23','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10384,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','kelompok_item','kelompok_item','form','2023-11-28 19:55:33','[]','[]'),
(10385,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','kelompok_item','kelompok_item','getData','2023-11-28 19:55:50','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10386,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','spesifikasi_item','spesifikasi_item','getData','2023-11-28 19:55:57','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10387,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','spesifikasi_harga','spesifikasi_harga','getData','2023-11-28 19:56:33','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10388,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-28 19:57:36','[]','[]'),
(10389,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-28 21:36:14','[]','[]'),
(10390,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','login','login','masuk','2023-11-28 21:37:11','[]','[]'),
(10391,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-28 21:37:15','[]','[]'),
(10392,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','jenis_item','jenis_item','getData','2023-11-28 21:37:22','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10393,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','dashboard','dashboard','data','2023-11-28 21:43:31','[]','[]'),
(10394,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','ssh','ssh','getData','2023-11-28 21:43:45','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10395,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0','ssh','ssh','getData','2023-11-28 21:46:13','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10396,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','dashboard','dashboard','data','2023-11-29 10:48:09','[]','[]'),
(10397,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-29 10:48:23','[]','[]'),
(10398,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','dashboard','dashboard','data','2023-11-29 12:26:07','[]','[]'),
(10399,NULL,NULL,NULL,'::1','Mozilla/5.0 (Linux; U; Android 11; en-us; CPH2127 Build/RKQ1.201217.002) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.88 Mobile Safari/537.36 HeyTapBrowser/45.10.3.4.1','dashboard','dashboard','data','2023-11-29 21:04:06','[]','[]'),
(10400,NULL,NULL,NULL,'::1','Mozilla/5.0 (Linux; U; Android 11; en-us; CPH2127 Build/RKQ1.201217.002) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.88 Mobile Safari/537.36 HeyTapBrowser/45.10.3.4.1','dashboard','dashboard','data','2023-11-29 21:04:18','[]','[]'),
(10401,NULL,NULL,NULL,'::1','Mozilla/5.0 (Linux; U; Android 11; en-us; CPH2127 Build/RKQ1.201217.002) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.88 Mobile Safari/537.36 HeyTapBrowser/45.10.3.4.1','dashboard','dashboard','data','2023-11-29 21:04:28','[]','[]'),
(10402,NULL,NULL,NULL,'::1','Mozilla/5.0 (Linux; U; Android 11; en-us; CPH2127 Build/RKQ1.201217.002) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.88 Mobile Safari/537.36 HeyTapBrowser/45.10.3.4.1','login','login','masuk','2023-11-29 21:06:38','[]','[]'),
(10403,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Linux; U; Android 11; en-us; CPH2127 Build/RKQ1.201217.002) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.88 Mobile Safari/537.36 HeyTapBrowser/45.10.3.4.1','dashboard','dashboard','data','2023-11-29 21:06:44','[]','[]'),
(10404,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Linux; U; Android 11; en-us; CPH2127 Build/RKQ1.201217.002) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.88 Mobile Safari/537.36 HeyTapBrowser/45.10.3.4.1','ssh','ssh','getData','2023-11-29 21:07:02','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\",\"tipe\":\"SSH\"}'),
(10405,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Linux; U; Android 11; en-us; CPH2127 Build/RKQ1.201217.002) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.88 Mobile Safari/537.36 HeyTapBrowser/45.10.3.4.1','ssh','ssh','getData','2023-11-29 21:07:08','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\",\"tipe\":\"SSH\"}'),
(10406,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Linux; U; Android 11; en-us; CPH2127 Build/RKQ1.201217.002) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.88 Mobile Safari/537.36 HeyTapBrowser/45.10.3.4.1','dashboard','dashboard','data','2023-11-29 21:10:18','[]','[]'),
(10407,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','dashboard','dashboard','data','2023-11-29 21:16:40','[]','[]'),
(10408,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','dashboard','dashboard','data','2023-11-29 21:16:50','[]','[]'),
(10409,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','login','login','masuk','2023-11-29 21:17:40','[]','[]'),
(10410,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','login','login','masuk','2023-11-29 21:18:16','[]','[]'),
(10411,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','dashboard','dashboard','data','2023-11-29 21:18:22','[]','[]'),
(10412,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','dashboard','dashboard','data','2023-11-29 21:18:30','[]','[]'),
(10413,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','ssh','ssh','getData','2023-11-29 21:18:43','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\",\"tipe\":\"SSH\"}'),
(10414,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','ssh','ssh','getData','2023-11-29 21:18:50','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\",\"tipe\":\"SBU\"}'),
(10415,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','ssh','ssh','getData','2023-11-29 21:18:51','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\",\"tipe\":\"SBU\"}'),
(10416,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','ssh','ssh','getData','2023-11-29 21:18:57','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\",\"tipe\":\"SBU\"}'),
(10417,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','ssh','ssh','getData','2023-11-29 21:19:06','[]','{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\",\"tipe\":\"SBU\"}'),
(10418,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','perkiraan_hps','perkiraan_hps','kegiatan','2023-11-29 21:19:17','[]','[]'),
(10419,8,'Administrator','admin@admin.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0','perkiraan_hps','perkiraan_hps','kegiatan','2023-11-29 21:19:38','[]','[]'),
(10420,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-30 09:04:55','[]','[]'),
(10421,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-30 10:07:07','[]','[]'),
(10422,NULL,NULL,NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','login','login','masuk','2023-11-30 10:07:16','[]','[]');

/*Table structure for table `users_role` */

DROP TABLE IF EXISTS `users_role`;

CREATE TABLE `users_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `role_access` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `users_role` */

insert  into `users_role`(`id`,`name`,`role_access`,`updated_at`,`created_at`) values 
(1,'admin','{\"jenis_item\":{\"jenis_item\":\"on\",\"accessadd_jenis_item\":\"on\",\"accessedit_jenis_item\":\"on\",\"accessdelete_jenis_item\":\"on\"},\"kegiatan_asb\":{\"kegiatan_asb\":\"on\",\"accessadd_kegiatan_asb\":\"on\",\"accessedit_kegiatan_asb\":\"on\",\"accessdelete_kegiatan_asb\":\"on\"},\"kegiatan_asb_detail\":{\"kegiatan_asb_detail\":\"on\",\"accessadd_kegiatan_asb_detail\":\"on\",\"accessedit_kegiatan_asb_detail\":\"on\",\"accessdelete_kegiatan_asb_detail\":\"on\"},\"kegiatan_hspk\":{\"kegiatan_hspk\":\"on\",\"accessadd_kegiatan_hspk\":\"on\",\"accessedit_kegiatan_hspk\":\"on\",\"accessdelete_kegiatan_hspk\":\"on\"},\"kegiatan_hspk_detail\":{\"kegiatan_hspk_detail\":\"on\",\"accessadd_kegiatan_hspk_detail\":\"on\",\"accessedit_kegiatan_hspk_detail\":\"on\",\"accessdelete_kegiatan_hspk_detail\":\"on\"},\"kelompok_item\":{\"kelompok_item\":\"on\",\"accessadd_kelompok_item\":\"on\",\"accessedit_kelompok_item\":\"on\",\"accessdelete_kelompok_item\":\"on\"},\"lokasi_toko\":{\"lokasi_toko\":\"on\",\"accessadd_lokasi_toko\":\"on\",\"accessedit_lokasi_toko\":\"on\",\"accessdelete_lokasi_toko\":\"on\"},\"manajemen_dashboard\":{\"manajemen_dashboard\":\"on\",\"accessadd_manajemen_dashboard\":\"on\",\"accessedit_manajemen_dashboard\":\"on\",\"accessdelete_manajemen_dashboard\":\"on\"},\"perkiraan_hps\":{\"perkiraan_hps\":\"off\",\"accessadd_perkiraan_hps\":\"off\",\"accessedit_perkiraan_hps\":\"off\",\"accessdelete_perkiraan_hps\":\"off\"},\"spesifikasi_harga\":{\"spesifikasi_harga\":\"on\",\"accessadd_spesifikasi_harga\":\"on\",\"accessedit_spesifikasi_harga\":\"on\",\"accessdelete_spesifikasi_harga\":\"on\"},\"spesifikasi_item\":{\"spesifikasi_item\":\"on\",\"accessadd_spesifikasi_item\":\"on\",\"accessedit_spesifikasi_item\":\"on\",\"accessdelete_spesifikasi_item\":\"on\"},\"tahun_kegiatan_asb\":{\"tahun_kegiatan_asb\":\"on\",\"accessadd_tahun_kegiatan_asb\":\"on\",\"accessedit_tahun_kegiatan_asb\":\"on\",\"accessdelete_tahun_kegiatan_asb\":\"on\"},\"tahun_kegiatan_hspk\":{\"tahun_kegiatan_hspk\":\"on\",\"accessadd_tahun_kegiatan_hspk\":\"on\",\"accessedit_tahun_kegiatan_hspk\":\"on\",\"accessdelete_tahun_kegiatan_hspk\":\"on\"},\"user_log\":{\"user_log\":\"on\"},\"user_manage\":{\"user_manage\":\"on\",\"accessadd_user_manage\":\"on\",\"accessedit_user_manage\":\"on\",\"accessdelete_user_manage\":\"on\"},\"user_role\":{\"user_role\":\"on\",\"accessadd_user_role\":\"on\",\"accessedit_user_role\":\"on\",\"accessdelete_user_role\":\"on\"}}','2023-11-30 10:06:42','2023-10-30 13:04:44'),
(2,'Administrator','{\"jenis_item\":{\"jenis_item\":\"on\",\"accessadd_jenis_item\":\"on\",\"accessedit_jenis_item\":\"on\",\"accessdelete_jenis_item\":\"on\"},\"kegiatan_asb\":{\"kegiatan_asb\":\"on\",\"accessadd_kegiatan_asb\":\"on\",\"accessedit_kegiatan_asb\":\"on\",\"accessdelete_kegiatan_asb\":\"on\"},\"kegiatan_asb_detail\":{\"kegiatan_asb_detail\":\"on\",\"accessadd_kegiatan_asb_detail\":\"on\",\"accessedit_kegiatan_asb_detail\":\"on\",\"accessdelete_kegiatan_asb_detail\":\"on\"},\"kegiatan_hspk\":{\"kegiatan_hspk\":\"on\",\"accessadd_kegiatan_hspk\":\"on\",\"accessedit_kegiatan_hspk\":\"on\",\"accessdelete_kegiatan_hspk\":\"on\"},\"kegiatan_hspk_detail\":{\"kegiatan_hspk_detail\":\"on\",\"accessadd_kegiatan_hspk_detail\":\"on\",\"accessedit_kegiatan_hspk_detail\":\"on\",\"accessdelete_kegiatan_hspk_detail\":\"on\"},\"kelompok_item\":{\"kelompok_item\":\"on\",\"accessadd_kelompok_item\":\"on\",\"accessedit_kelompok_item\":\"on\",\"accessdelete_kelompok_item\":\"on\"},\"lokasi_toko\":{\"lokasi_toko\":\"on\",\"accessadd_lokasi_toko\":\"on\",\"accessedit_lokasi_toko\":\"on\",\"accessdelete_lokasi_toko\":\"on\"},\"manajemen_dashboard\":{\"manajemen_dashboard\":\"off\",\"accessadd_manajemen_dashboard\":\"off\",\"accessedit_manajemen_dashboard\":\"off\",\"accessdelete_manajemen_dashboard\":\"off\"},\"perkiraan_hps\":{\"perkiraan_hps\":\"off\",\"accessadd_perkiraan_hps\":\"off\",\"accessedit_perkiraan_hps\":\"off\",\"accessdelete_perkiraan_hps\":\"off\"},\"spesifikasi_harga\":{\"spesifikasi_harga\":\"on\",\"accessadd_spesifikasi_harga\":\"on\",\"accessedit_spesifikasi_harga\":\"on\",\"accessdelete_spesifikasi_harga\":\"on\"},\"spesifikasi_item\":{\"spesifikasi_item\":\"on\",\"accessadd_spesifikasi_item\":\"on\",\"accessedit_spesifikasi_item\":\"on\",\"accessdelete_spesifikasi_item\":\"on\"},\"tahun_kegiatan_asb\":{\"tahun_kegiatan_asb\":\"on\",\"accessadd_tahun_kegiatan_asb\":\"on\",\"accessedit_tahun_kegiatan_asb\":\"on\",\"accessdelete_tahun_kegiatan_asb\":\"on\"},\"tahun_kegiatan_hspk\":{\"tahun_kegiatan_hspk\":\"on\",\"accessadd_tahun_kegiatan_hspk\":\"on\",\"accessedit_tahun_kegiatan_hspk\":\"on\",\"accessdelete_tahun_kegiatan_hspk\":\"on\"},\"user_log\":{\"user_log\":\"on\"},\"user_manage\":{\"user_manage\":\"on\",\"accessadd_user_manage\":\"on\",\"accessedit_user_manage\":\"on\",\"accessdelete_user_manage\":\"on\"},\"user_role\":{\"user_role\":\"on\",\"accessadd_user_role\":\"on\",\"accessedit_user_role\":\"on\",\"accessdelete_user_role\":\"on\"}}','2023-11-30 10:06:53','2023-10-30 14:16:08'),
(4,'Vendor','{\"jenis_item\":{\"jenis_item\":\"on\",\"accessadd_jenis_item\":\"off\",\"accessedit_jenis_item\":\"off\",\"accessdelete_jenis_item\":\"off\"},\"kegiatan_asb\":{\"kegiatan_asb\":\"on\",\"accessadd_kegiatan_asb\":\"off\",\"accessedit_kegiatan_asb\":\"off\",\"accessdelete_kegiatan_asb\":\"off\"},\"kegiatan_asb_detail\":{\"kegiatan_asb_detail\":\"on\",\"accessadd_kegiatan_asb_detail\":\"off\",\"accessedit_kegiatan_asb_detail\":\"off\",\"accessdelete_kegiatan_asb_detail\":\"off\"},\"kegiatan_hspk\":{\"kegiatan_hspk\":\"on\",\"accessadd_kegiatan_hspk\":\"off\",\"accessedit_kegiatan_hspk\":\"off\",\"accessdelete_kegiatan_hspk\":\"off\"},\"kegiatan_hspk_detail\":{\"kegiatan_hspk_detail\":\"on\",\"accessadd_kegiatan_hspk_detail\":\"off\",\"accessedit_kegiatan_hspk_detail\":\"off\",\"accessdelete_kegiatan_hspk_detail\":\"off\"},\"kelompok_item\":{\"kelompok_item\":\"on\",\"accessadd_kelompok_item\":\"off\",\"accessedit_kelompok_item\":\"off\",\"accessdelete_kelompok_item\":\"off\"},\"manajemen_dashboard\":{\"manajemen_dashboard\":\"off\",\"accessadd_manajemen_dashboard\":\"off\",\"accessedit_manajemen_dashboard\":\"off\",\"accessdelete_manajemen_dashboard\":\"off\"},\"perkiraan_hps\":{\"perkiraan_hps\":\"off\",\"accessadd_perkiraan_hps\":\"off\",\"accessedit_perkiraan_hps\":\"off\",\"accessdelete_perkiraan_hps\":\"off\"},\"spesifikasi_harga\":{\"spesifikasi_harga\":\"on\",\"accessadd_spesifikasi_harga\":\"off\",\"accessedit_spesifikasi_harga\":\"off\",\"accessdelete_spesifikasi_harga\":\"off\"},\"spesifikasi_item\":{\"spesifikasi_item\":\"on\",\"accessadd_spesifikasi_item\":\"off\",\"accessedit_spesifikasi_item\":\"off\",\"accessdelete_spesifikasi_item\":\"off\"},\"tahun_kegiatan_asb\":{\"tahun_kegiatan_asb\":\"on\",\"accessadd_tahun_kegiatan_asb\":\"off\",\"accessedit_tahun_kegiatan_asb\":\"off\",\"accessdelete_tahun_kegiatan_asb\":\"off\"},\"tahun_kegiatan_hspk\":{\"tahun_kegiatan_hspk\":\"on\",\"accessadd_tahun_kegiatan_hspk\":\"off\",\"accessedit_tahun_kegiatan_hspk\":\"off\",\"accessdelete_tahun_kegiatan_hspk\":\"off\"},\"user_log\":{\"user_log\":\"off\"},\"user_manage\":{\"user_manage\":\"off\",\"accessadd_user_manage\":\"off\",\"accessedit_user_manage\":\"off\",\"accessdelete_user_manage\":\"off\"},\"user_role\":{\"user_role\":\"off\",\"accessadd_user_role\":\"off\",\"accessedit_user_role\":\"off\",\"accessdelete_user_role\":\"off\"}}','2023-11-20 14:01:05','2023-10-30 09:47:59'),
(8,'Bag Irigasi','{\"jenis_item\":{\"jenis_item\":\"off\",\"accessadd_jenis_item\":\"off\",\"accessedit_jenis_item\":\"off\",\"accessdelete_jenis_item\":\"off\"},\"kegiatan_asb\":{\"kegiatan_asb\":\"off\",\"accessadd_kegiatan_asb\":\"off\",\"accessedit_kegiatan_asb\":\"off\",\"accessdelete_kegiatan_asb\":\"off\"},\"kegiatan_asb_detail\":{\"kegiatan_asb_detail\":\"off\",\"accessadd_kegiatan_asb_detail\":\"off\",\"accessedit_kegiatan_asb_detail\":\"off\",\"accessdelete_kegiatan_asb_detail\":\"off\"},\"kegiatan_hspk\":{\"kegiatan_hspk\":\"off\",\"accessadd_kegiatan_hspk\":\"off\",\"accessedit_kegiatan_hspk\":\"off\",\"accessdelete_kegiatan_hspk\":\"off\"},\"kegiatan_hspk_detail\":{\"kegiatan_hspk_detail\":\"off\",\"accessadd_kegiatan_hspk_detail\":\"off\",\"accessedit_kegiatan_hspk_detail\":\"off\",\"accessdelete_kegiatan_hspk_detail\":\"off\"},\"kelompok_item\":{\"kelompok_item\":\"off\",\"accessadd_kelompok_item\":\"off\",\"accessedit_kelompok_item\":\"off\",\"accessdelete_kelompok_item\":\"off\"},\"manajemen_dashboard\":{\"manajemen_dashboard\":\"off\",\"accessadd_manajemen_dashboard\":\"off\",\"accessedit_manajemen_dashboard\":\"off\",\"accessdelete_manajemen_dashboard\":\"off\"},\"perkiraan_hps\":{\"perkiraan_hps\":\"off\",\"accessadd_perkiraan_hps\":\"off\",\"accessedit_perkiraan_hps\":\"off\",\"accessdelete_perkiraan_hps\":\"off\"},\"spesifikasi_harga\":{\"spesifikasi_harga\":\"off\",\"accessadd_spesifikasi_harga\":\"off\",\"accessedit_spesifikasi_harga\":\"off\",\"accessdelete_spesifikasi_harga\":\"off\"},\"spesifikasi_item\":{\"spesifikasi_item\":\"off\",\"accessadd_spesifikasi_item\":\"off\",\"accessedit_spesifikasi_item\":\"off\",\"accessdelete_spesifikasi_item\":\"off\"},\"tahun_kegiatan_asb\":{\"tahun_kegiatan_asb\":\"off\",\"accessadd_tahun_kegiatan_asb\":\"off\",\"accessedit_tahun_kegiatan_asb\":\"off\",\"accessdelete_tahun_kegiatan_asb\":\"off\"},\"tahun_kegiatan_hspk\":{\"tahun_kegiatan_hspk\":\"off\",\"accessadd_tahun_kegiatan_hspk\":\"off\",\"accessedit_tahun_kegiatan_hspk\":\"off\",\"accessdelete_tahun_kegiatan_hspk\":\"off\"},\"user_log\":{\"user_log\":\"off\"},\"user_manage\":{\"user_manage\":\"off\",\"accessadd_user_manage\":\"off\",\"accessedit_user_manage\":\"off\",\"accessdelete_user_manage\":\"off\"},\"user_role\":{\"user_role\":\"off\",\"accessadd_user_role\":\"off\",\"accessedit_user_role\":\"off\",\"accessdelete_user_role\":\"off\"}}','2023-11-20 14:00:59','2023-11-13 19:13:24');

/* Trigger structure for table `tb_jenis_item` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `update_tb_jenis_item` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `update_tb_jenis_item` AFTER UPDATE ON `tb_jenis_item` FOR EACH ROW 
BEGIN
    UPDATE tb_spesifikasi_item
    SET kodeKelompok = CONCAT(NEW.kodeKelompok, '.', tb_spesifikasi_item.idSpesifikasi)
    WHERE tb_spesifikasi_item.idJenisItem = NEW.id;
END */$$


DELIMITER ;

/* Trigger structure for table `tb_jenis_item` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `delete_tb_jenis_item` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `delete_tb_jenis_item` AFTER DELETE ON `tb_jenis_item` FOR EACH ROW 
BEGIN
    DELETE FROM tb_spesifikasi_item
    WHERE idJenisItem = OLD.id;
END */$$


DELIMITER ;

/* Trigger structure for table `tb_kegiatan` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `update_tb_kegiatan` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `update_tb_kegiatan` AFTER UPDATE ON `tb_kegiatan` FOR EACH ROW 
BEGIN
    UPDATE tb_thn_kegiatan
    SET kodeKelompok = NEW.idKegiatan
    WHERE idKegiatan = NEW.id;
END */$$


DELIMITER ;

/* Trigger structure for table `tb_kegiatan` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `delete_tb_kegiatan` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `delete_tb_kegiatan` AFTER DELETE ON `tb_kegiatan` FOR EACH ROW 
BEGIN
    DELETE FROM tb_thn_kegiatan
    WHERE idKegiatan = OLD.id;
END */$$


DELIMITER ;

/* Trigger structure for table `tb_kelompok_item` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `update_tb_kelompok_item` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `update_tb_kelompok_item` AFTER UPDATE ON `tb_kelompok_item` FOR EACH ROW 
BEGIN
    UPDATE tb_jenis_item
    SET kodeKelompok = CONCAT(NEW.IdKelItem, '.', tb_jenis_item.idJenisBarang)
    WHERE tb_jenis_item.idKelompokItem = NEW.id;
END */$$


DELIMITER ;

/* Trigger structure for table `tb_kelompok_item` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `delete_tb_kelompok_item` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `delete_tb_kelompok_item` AFTER DELETE ON `tb_kelompok_item` FOR EACH ROW 
BEGIN
    DELETE FROM tb_jenis_item
    WHERE idKelompokItem = OLD.id;
END */$$


DELIMITER ;

/* Trigger structure for table `tb_spesifikasi_item` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `update_tb_spesifikasi_item` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `update_tb_spesifikasi_item` AFTER UPDATE ON `tb_spesifikasi_item` FOR EACH ROW 
BEGIN
    UPDATE tb_thn_harga
    SET kodeKelompok = NEW.kodeKelompok
    WHERE tb_thn_harga.idSpesifikasi = NEW.id;
END */$$


DELIMITER ;

/* Trigger structure for table `tb_spesifikasi_item` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `delete_tb_spesifikasi_item` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `delete_tb_spesifikasi_item` AFTER DELETE ON `tb_spesifikasi_item` FOR EACH ROW 
BEGIN
    DELETE FROM tb_thn_harga
    WHERE idSpesifikasi = OLD.id;
END */$$


DELIMITER ;

/* Trigger structure for table `tb_thn_kegiatan` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `delete_tb_thn_kegiatan` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `delete_tb_thn_kegiatan` AFTER DELETE ON `tb_thn_kegiatan` FOR EACH ROW 
BEGIN
    DELETE FROM tb_thn_pekerjaan_detail
    WHERE id_thn_kegiatan = OLD.id;
END */$$


DELIMITER ;

/* Procedure structure for procedure `CalculateDynamicTotalHarga` */

/*!50003 DROP PROCEDURE IF EXISTS  `CalculateDynamicTotalHarga` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `CalculateDynamicTotalHarga`()
BEGIN
  DECLARE i INT DEFAULT 0;
  DECLARE id_harga INT;
  DECLARE total DECIMAL(10, 2) DEFAULT 0;
  
  CREATE TEMPORARY TABLE IF NOT EXISTS DynamicTotalHarga (
    id_thn_pekerjaan_detail INT,
    total DECIMAL(10, 2)
  );
  
  -- Loop untuk setiap baris dalam tabel tb_thn_pekerjaan_detail
  WHILE i < (SELECT COUNT(*) FROM tb_thn_pekerjaan_detail) DO
    SET id_harga = JSON_UNQUOTE(JSON_EXTRACT((SELECT id_thn_harga FROM tb_thn_pekerjaan_detail LIMIT i, 1), CONCAT('$[', i, ']')));
    SET total = total + IFNULL((SELECT harga FROM tb_thn_harga WHERE id = id_harga), 0);
    
    INSERT INTO DynamicTotalHarga (id_thn_pekerjaan_detail, total) VALUES (i + 1, total);
    
    SET i = i + 1;
  END WHILE;
  
  -- Kembalikan hasil
  SELECT * FROM DynamicTotalHarga;
  
  -- Hapus tabel temporer
  DROP TEMPORARY TABLE DynamicTotalHarga;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
