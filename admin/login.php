<?php
session_start();
include "../library/config.php"; // koneksi ke database

// Ambil tema login admin dari tabel setting
$q = mysqli_query($mysqli, "SELECT nilai FROM setting WHERE parameter='tema_login_admin'");
$data = mysqli_fetch_array($q);

// Jika tidak ditemukan, default ke 'klasik'
$tema_login_admin = $data ? $data['nilai'] : 'klasik';

// Tentukan file tema yang dipakai
if($tema_login_admin == 'adminlte'){
   include "tema/login_adminlte.php";
}else{
   include "tema/login_klasik.php";
}
?>
