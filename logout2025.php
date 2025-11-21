<?php
  session_start();
  include "library/config.php";
  mysqli_query($mysqli, "UPDATE siswa SET status='off', jmlog=0 WHERE nis='$_SESSION[nis]'");
  
  session_destroy();
  echo "<script>
   alert('Anda keluar dari ujian!'); 
   window.location = 'login.php';
   </script>";
?>
