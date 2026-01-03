<?php
session_start();
include "library/config.php";

if(empty($_SESSION['username']) or empty($_SESSION['password']) ){
   header('location: login.php');
}

//Memproses data ajax ketika memilih salah satu jawaban
if($_GET['action']=="kirim_jawaban"){   
   $siswa = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM siswa WHERE nis='$_SESSION[nis]'"));
   if($siswa['status']=='Lock'){
      echo "lock";
   }else{
      $rnilai = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM nilai WHERE id_ujian='$_POST[ujian]' AND nis='$_SESSION[nis]'"));
      
      $jawaban = explode(",", $rnilai['jawaban']);
      $index = $_POST['index'];	
      $jawaban[$index] = $_POST['jawab'];
      
      $jawabanfix = implode(",", $jawaban);
      mysqli_query($mysqli, "UPDATE nilai SET jawaban='$jawabanfix', sisa_waktu='$_POST[sisa_waktu]' WHERE id_ujian='$_POST[ujian]' AND nis='$_SESSION[nis]'");
      
      echo "ok";
   }
}
//Memproses data ajax ketika kirim jawaban esay
elseif($_GET['action']=="kirim_esay"){   
   $siswa = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM siswa WHERE nis='$_SESSION[nis]'"));
   if($siswa['status']=='Lock'){
      echo "lock";
   }else{
      $rnilai = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM nilai WHERE id_ujian='$_POST[ujian]' AND nis='$_SESSION[nis]'"));
      
      $jawaban = explode(",", $rnilai['jawaban']);
      $index = $_POST['index'];	
      $jawaban[$index] = 1;
      
      $jawabanfix = implode(",", $jawaban);
      mysqli_query($mysqli, "UPDATE nilai SET jawaban='$jawabanfix', sisa_waktu='$_POST[sisa_waktu]' WHERE id_ujian='$_POST[ujian]' AND nis='$_SESSION[nis]'");
      
      $cekjawaban = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM jawaban WHERE 
         id_ujian='$_POST[ujian]' AND 
         nis='$_SESSION[nis]' AND 
         id_soal='$_POST[idsoal]'"));

      if($cekjawaban>=1){
         mysqli_query($mysqli, "UPDATE jawaban set jawaban='$_POST[jawab]' WHERE 
            id_ujian='$_POST[ujian]' AND 
            nis='$_SESSION[nis]' AND 
            id_soal='$_POST[idsoal]'");
      }else{
         mysqli_query($mysqli, "INSERT INTO jawaban set 
            id_ujian='$_POST[ujian]', 
            nis='$_SESSION[nis]',
            id_soal='$_POST[idsoal]',
            jawaban='$_POST[jawab]'");
      }
      echo "ok";
   }
}

//Memproses data ajax ketika memilih salah satu jawaban
elseif($_GET['action']=="kirim_jawaban_kompleks" or $_GET['action']=="kirim_jawaban_pernyataan"){   
   $siswa = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM siswa WHERE nis='$_SESSION[nis]'"));
   if($siswa['status']=='Lock'){
      echo "lock";
   }else{
      $rnilai = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM nilai WHERE id_ujian='$_POST[ujian]' AND nis='$_SESSION[nis]'"));
      
      $jawaban = explode(",", $rnilai['jawaban']);
      $index = $_POST['index'];	
      $jawaban[$index] = 1;
      
      $jawabanfix = implode(",", $jawaban);
      mysqli_query($mysqli, "UPDATE nilai SET jawaban='$jawabanfix', sisa_waktu='$_POST[sisa_waktu]' WHERE id_ujian='$_POST[ujian]' AND nis='$_SESSION[nis]'");
      
      $cekjawaban = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM jawaban WHERE 
         id_ujian='$_POST[ujian]' AND 
         nis='$_SESSION[nis]' AND 
         id_soal='$_POST[idsoal]'"));

      if($cekjawaban>=1){
         mysqli_query($mysqli, "UPDATE jawaban set jawaban='$_POST[jawab]' WHERE 
            id_ujian='$_POST[ujian]' AND 
            nis='$_SESSION[nis]' AND 
            id_soal='$_POST[idsoal]'");
      }else{
         mysqli_query($mysqli, "INSERT INTO jawaban set 
            id_ujian='$_POST[ujian]', 
            nis='$_SESSION[nis]',
            id_soal='$_POST[idsoal]',
            jawaban='$_POST[jawab]'");
      }
      echo "ok";
   }
}

//Memproses data ajax ketika menyelesaikan ujian
elseif($_GET['action']=="selesai_ujian"){

   mysqli_query($mysqli, "UPDATE nilai SET jml_benar='0', nilai='0' WHERE id_ujian='$_POST[ujian]' AND nis='$_SESSION[nis]'");
   
   mysqli_query($mysqli, "UPDATE siswa SET status='login' WHERE nis='$_SESSION[nis]'");
   
  echo "ok";
}

//Update waktu tersisa
elseif ($_GET['action'] == "update_waktu") {

   mysqli_query($mysqli, "
      UPDATE nilai 
      SET sisa_waktu='$_POST[sisa_waktu]'
      WHERE id_ujian='$_POST[ujian]' 
      AND nis='$_SESSION[nis]'
   ");

   echo "ok";
}
?>