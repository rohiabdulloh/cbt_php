<?php
session_start();
ob_start();

//Mengatur batas login
$timeout = $_SESSION['timeout'];
if(time()<$timeout){
   $_SESSION['timeout'] = time()+5000;
}else{
   $_SESSION['login'] = 0;
}

//Mengecek status login
if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['login']==0){
   header('location: login.php');
}

include "../library/config.php"; // koneksi ke database

// Ambil tema login admin dari tabel setting
$q = mysqli_query($mysqli, "SELECT nilai FROM setting WHERE parameter='tema_admin'");
$data = mysqli_fetch_array($q);

// Jika tidak ditemukan, default ke 'klasik'
$tema_admin = $data ? $data['nilai'] : 'klasik';

// Tentukan file tema yang dipakai
if($tema_admin == 'adminlte'){
   include "tema/adminlte.php";
}else{
   include "tema/klasik.php";
}
?>
