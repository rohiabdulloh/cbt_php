<?php
session_start();
include "library/config.php";
include "library/cek_blok.php";

if(empty($_SESSION['username']) or empty($_SESSION['password']) ){
   header('location: login.php');
}

$kelas = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM kelas WHERE id_kelas='$_SESSION[kelas]'"));
$ujian = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM ujian WHERE id_ujian='$_GET[ujian]'"));
?>

<h3 class="page-header"><i class="glyphicon glyphicon-user"></i> Data Siswa dan Ujian</h3>
<div class="row">
   <div class="col-md-3">NIS</div>
   <div class="col-md-9">: <b><?= $_SESSION['nis']; ?> </b> </div>
</div><br/>
<div class="row">
   <div class="col-md-3">Nama Lengkap</div>
   <div class="col-md-9">: <b><?= $_SESSION['namalengkap']; ?> </b></div>
</div><br/>
<div class="row">
   <div class="col-md-3">Kelas</div>
   <div class="col-md-9">: <b><?= $kelas['kelas']; ?></b></div>
</div><br/>
<div class="row">
   <div class="col-md-3">Nama Mapel</div>
   <div class="col-md-9">: <b><?= $ujian['nama_mapel']; ?></b></div>
</div><br/>
<div class="row">
   <div class="col-md-3">Jml. Soal</div>
   <div class="col-md-9">: <b><?= $ujian['jml_soal']; ?></b></div>
</div><br/>
<div class="row">
   <div class="col-md-3">Waktu Mengerjakan</div>
   <div class="col-md-9">: <b><?= $ujian['waktu']; ?> menit</b></div>
</div><br/>
<div class="row">
   <div class="col-md-3">Jam Mulai</div>
   <div class="col-md-9">: <b><?= substr($ujian['mulai'], 0, 5); ?></b></div>
</div><br/>
<div class="row">
   <div class="col-md-3">Jam Selesai</div>
   <div class="col-md-9">: <b><?= substr($ujian['selesai'], 0 ,5); ?></b></div>
</div><br/>

<div class="row">
   <div class="col-md-12">

<?php	
//Jika nilai sudah ada tampilkan tombol Sudah Mengerjakan, jika belum ada tampilkan tombol Masuk Ujian
$qnilai = mysqli_query($mysqli, "SELECT * FROM nilai WHERE id_ujian='$_GET[ujian]' AND nis='$_SESSION[nis]'");
$tnilai = mysqli_num_rows($qnilai);
$rnilai = mysqli_fetch_array($qnilai);

if($tnilai>0 and $rnilai['nilai'] != "" and $rnilai['nilai'] != null)  echo '<a class="btn btn-danger disabled"> Sudah mengerjakan </a>';
else echo '<a class="btn btn-primary" onclick="show_petunjuk('.$_GET['ujian'].')">
<i class="glyphicon glyphicon-log-in"></i> Masuk Ujian</a>';
?>
	
   </div>
</div><br/>
