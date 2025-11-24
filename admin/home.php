<?php
session_start();

if(empty($_SESSION['username']) or empty ($_SESSION['password'])){
   header('location: login.php');
}
?>

<div class="jumbotron mt-4">
   <div class="container text-center">
      <h2>Selamat Datang <b> <?= $_SESSION['namalengkap']; ?> </b>!</h2>
      <p>Anda login sebagai <b> <?= $_SESSION['leveluser']; ?> </b></p>
   </div>
</div>

