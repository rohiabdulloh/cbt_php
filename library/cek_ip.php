<?php
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

include "library/config.php";

if (!isset($_SESSION['nis'])) {
   //echo "ok";
   exit;
}

$ip  = $_SERVER['REMOTE_ADDR'];
$nis = $_SESSION['nis'];

$sql = "SELECT nama 
        FROM siswa 
        WHERE nis = ? 
        AND ip = ? 
        AND status = 'lock' 
        LIMIT 1";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $nis, $ip);
$stmt->execute();
$result = $stmt->get_result();

//if ($result->num_rows > 0) {
//   $data = $result->fetch_assoc();
 //  $nama = $data['nama'];

  // session_destroy();
  // echo "Sistem terkunci! $nama ,jawaban tidak akan terkirim krn terdeteksi melakukan kecurangan, Silakan hubungi proktor.";
  // exit;
//}
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $nama = $data['nama'];
    echo "Sistem terkunci! $nama ,jawaban tidak akan terkirim krn terdeteksi melakukan kecurangan, Silakan hubungi proktor.";
   
    exit;
}

//echo "ok";
$stmt->close();


