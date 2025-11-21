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
<td>Jml. Benar</td>
<td>Nilai PG</td>
<td>Nilai Esay</td>
<td>Total Nilai</td>
</tr>';
$query=mysqli_query($mysqli,"SELECT * From siswa where id_kelas='$_GET[kelas]'");
$no=1;
while($r=mysqli_fetch_array($query)){
	$n=mysqli_fetch_array(mysqli_query($mysqli,"SELECT * FROM nilai WHERE nis='$r[nis]' and id_ujian='$_GET[ujian]'"));
	$nilaiesay = mysqli_fetch_row(mysqli_query($mysqli, "SELECT SUM(nilai) FROM jawaban WHERE nis='$r[nis]' AND id_ujian='$_GET[ujian]'"));

	if($n){
		$jml_benar = $n['jml_benar'];
		$nilaipg = $n['nilai'];
		$total = $nilaipg + $nilaiesay[0];
	}else{
		$jml_benar = '';
		$nilaipg = '';
		$total = '';
	}
	echo '<tr>
<td>'.$no.'</td>
<td>'.$r['nis'].'</td>
<td>'.$r['nama'].'</td>
<td>'.$rkelas['kelas'].'</td>
<td>'.$jml_benar.'</td>
<td>'.$nilaipg.'</td>
<td>'.$nilaiesay[0].'</td>
<td>'.$total.'</td>
</tr>';
$no++;
}
echo '</table>';