<?php
include"../../library/config.php";
$rujian=mysqli_fetch_array(mysqli_query($mysqli,"SELECT * FROM ujian WHERE id_ujian='$_GET[ujian]'"));
$rkelas=mysqli_fetch_array(mysqli_query($mysqli,"SELECT * FROM kelas WHERE id_kelas='$_GET[kelas]'"));

header("content-type: application/vnd-ms-excel");
header("content-Disposition: attachment; filename=Nilai$rujian[nama_mapel] Kelas $rkelas[kelas].xls");
session_start();
if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['leveluser']!="guru"){
	header('location: ../login.php');
}
echo '<table border="1">
<tr>
<td>No</td>
<td>NIS</td>
<td>Nama Siswa</td>
<td>Kelas</td>
<td>Nilai</td>
</tr>';
$query=mysqli_query($mysqli,"SELECT * From siswa where id_kelas='$_GET[kelas]'");
$no=1;
while($r=mysqli_fetch_array($query)){
	$n=mysqli_fetch_array(mysqli_query($mysqli,"SELECT * FROM nilai WHERE nis='$r[nis]' and id_ujian='$_GET[ujian]'"));
	$nilaiesay = mysqli_fetch_row(mysqli_query(
		$mysqli,
		"SELECT SUM(jawaban.nilai) 
		FROM jawaban
		JOIN soal ON jawaban.id_soal = soal.id_soal
		WHERE jawaban.nis = '$r[nis]'
		   AND jawaban.id_ujian = '$_GET[ujian]'
		   AND soal.jenis = 1"
  	));

	if($n){
		$total = $nilaipg + $nilaiesay[0];
	}else{
		$total = '';
	}
	echo '<tr>
<td>'.$no.'</td>
<td>'.$r['nis'].'</td>
<td>'.$r['nama'].'</td>
<td>'.$rkelas['kelas'].'</td>
<td>'.$total.'</td>
</tr>';
$no++;
}
echo '</table>';