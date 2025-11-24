<?php
session_start();
include "../../library/config.php";
include "../../library/function_view.php";

if($_GET['action'] == "table_data"){
   
   $query = mysqli_query($mysqli, "SELECT * FROM bagi_ruang");
   $data = array();
   $no = 1;
   $batas = 0;
   while($r = mysqli_fetch_array($query)){
      $querysiswa = mysqli_query($mysqli, "SELECT * FROM siswa ORDER BY id_kelas, nama limit $batas, $r[jml_siswa]");
      while($s = mysqli_fetch_array($querysiswa)){
         $row = array();
         $row[] = $no;
         $row[] = $s['nis'];
         $row[] = $s['nama'];
         $row[] = $r['kelompok'];
         $row[] = $r['ruang'];
         $data[] = $row;
         $no++;
      }
      
      $batas += $r['jml_siswa'];
   }
	
   $output = array("data" => $data);
   echo json_encode($output);
}

elseif($_GET['action'] == "update"){
   function bagi_ruang($sisa, $jmlsiswa, $kelompok, $layout, $mysqli){
      $ruang = 1;
      while($sisa > 0){
         if($sisa>$jmlsiswa) $jml = $jmlsiswa;
         else $jml = $sisa;
   
         mysqli_query($mysqli, "INSERT INTO bagi_ruang SET
            ruang='$ruang',
            jml_siswa='$jml',
            layout='$layout',
            kelompok='$kelompok'
         ");

         $sisa -= $jmlsiswa;
         $ruang++;
      }
   }

   mysqli_query($mysqli, "DELETE FROM bagi_ruang WHERE id_bagi>0");
   $jmlsiswa   = $_POST['jml_siswa'];
   $kls_x      = $_POST['kelas_x'];
   $kls_xi     = $_POST['kelas_xi'];
   $kls_xii    = $_POST['kelas_xii'];
   $layout     = $_POST['layout'];

   bagi_ruang($kls_x, $jmlsiswa, 1, $layout, $mysqli);
   bagi_ruang($kls_xi, $jmlsiswa, 2, $layout, $mysqli);
   bagi_ruang($kls_xii, $jmlsiswa, 3, $layout, $mysqli);
   
   echo "ok";
}

?>
