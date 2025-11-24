<?php
$siswa = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM siswa WHERE nis='$_SESSION[nis]'"));
if($siswa['status']=='Lock'){
   echo "<script>
      alert('Akun anda diblokir sementara oleh operator karena terindikasi kecurangan. Silakan hubungi operator untuk membuka blok!'); 
      window.location = 'login.php';
   </script>";
}
?>
