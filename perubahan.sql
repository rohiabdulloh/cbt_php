DROP TABLE IF EXISTS `filesoal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filesoal` (
  `id_filesoal` int(5) NOT NULL AUTO_INCREMENT,
  `id_ujian` int(5) NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `filesoal` varchar(100) NOT NULL,
  `filekisi` varchar(100) NOT NULL,
  `tanggal_upload` date NOT NULL,
  `id_user` int(5) NOT NULL,
  PRIMARY KEY (`id_filesoal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `bagi_ruang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bagi_ruang` (
  `id_bagi` int(5) NOT NULL AUTO_INCREMENT,
  `ruang` int(3) NOT NULL,
  `jml_siswa` int(3) NOT NULL,
  `layout` int(1) NOT NULL,
  `kelompok` int(2) NOT NULL,
  PRIMARY KEY (`id_bagi`)
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;



DROP TABLE IF EXISTS `jenis_ujian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jenis_ujian` (
  `id_jenis` int(5) NOT NULL AUTO_INCREMENT,
  `nama_ujian` varchar(100) NOT NULL,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;



DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parameter` varchar(100) NOT NULL,
  `nilai` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` VALUES (1,'tema_admin','klasik'),(2,'tema_login_admin','adminlte'),(3,'tema_siswa','klasik'),(4,'tema_login_siswa','klasik');
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;

ALTER TABLE `soal` CHANGE `kunci` `kunci` VARCHAR(20) NOT NULL;

ALTER TABLE `soal` ADD `parameter` VARCHAR(200) NULL AFTER `pilihan_5`;
