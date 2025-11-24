<?php
session_start();
include "library/config.php"; // koneksi ke database

// Ambil tema login admin dari tabel setting
$q = mysqli_query($mysqli, "SELECT nilai FROM setting WHERE parameter='tema_login_siswa'");
$data = mysqli_fetch_array($q);

// Jika tidak ditemukan, default ke 'klasik'
$tema_login_siswa = $data ? $data['nilai'] : 'klasik';

// Tentukan file tema yang dipakai
if($tema_login_siswa == 'tka'){
   include "tema/login_tka.php";
}else{
   include "tema/login_klasik.php";
}
?>
