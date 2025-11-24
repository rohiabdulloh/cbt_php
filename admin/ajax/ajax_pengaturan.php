<?php
session_start();
include "../../library/config.php"; // gunakan koneksi yang sama seperti profil

// Ambil data dari form
$tema_admin        = $_POST['tema_admin'];
$tema_login_admin  = $_POST['tema_login_admin'];
$tema_siswa        = $_POST['tema_siswa'];
$tema_login_siswa  = $_POST['tema_login_siswa'];

// Daftar pengaturan yang akan disimpan
$setting_data = [
   'tema_admin'        => $tema_admin,
   'tema_login_admin'  => $tema_login_admin,
   'tema_siswa'        => $tema_siswa,
   'tema_login_siswa'  => $tema_login_siswa
];

$sukses = true;

foreach ($setting_data as $parameter => $nilai) {
   // Cek apakah parameter sudah ada
   $cek = mysqli_query($mysqli, "SELECT * FROM setting WHERE parameter='$parameter'");
   if (mysqli_num_rows($cek) > 0) {
      // Jika sudah ada → update
      $query = mysqli_query($mysqli, "UPDATE setting SET nilai='$nilai' WHERE parameter='$parameter'");
   } else {
      // Jika belum ada → insert
      $query = mysqli_query($mysqli, "INSERT INTO setting (parameter, nilai) VALUES ('$parameter', '$nilai')");
   }

   if(!$query) $sukses = false;
}

if($sukses){
   echo "ok";
}else{
   echo "Gagal menyimpan pengaturan!";
}
?>
