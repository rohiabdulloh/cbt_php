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
//AND ('$_POST[sisa_waktu]'<= '80:50'))
elseif($_GET['action']=="selesai_ujian"){
   $rnilai = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM nilai WHERE id_ujian='$_POST[ujian]' AND nis='$_SESSION[nis]'"));
	
   $arr_soal = explode(",", $rnilai['acak_soal']);
   $jawaban = explode(",", $rnilai['jawaban']);
   $jbenar = 0;
   $nilai = 0;
   for($i=0; $i<count($arr_soal); $i++){
      $rsoal = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$_POST[ujian]' AND id_soal='$arr_soal[$i]'"));
      if($rsoal){
         if($rsoal['jenis']==0){
            if($rsoal['kunci'] == $jawaban[$i]) {
               $jbenar++;
               $nilai = $nilai + $rsoal['bobot'];
            }
         }elseif($rsoal['jenis']==2){
            $cekjawaban = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM jawaban WHERE 
               id_ujian='$_POST[ujian]' AND 
               nis='$_SESSION[nis]' AND 

               id_soal='$arr_soal[$i]'"));
               // Ubah string kunci dan jawaban menjadi array
               $kunci_arr = explode(',', $rsoal['kunci']);
               $jawaban_arr = explode(',', $cekjawaban['jawaban']);

               // Hilangkan spasi jika ada
               $kunci_arr = array_map('trim', $kunci_arr);
               $jawaban_arr = array_map('trim', $jawaban_arr);

               // Urutkan array agar perbandingan tidak tergantung urutan
               sort($kunci_arr);
               sort($jawaban_arr);

               // Bandingkan hasil akhir
               if ($kunci_arr == $jawaban_arr) {
                  $jbenar++;
                  $nilai = $nilai + $rsoal['bobot'];
                  
                  $nilaijawaban = $rsoal['bobot'];
                  mysqli_query($mysqli, "UPDATE jawaban SET nilai='$nilaijawaban' WHERE 
                     id_ujian='$_POST[ujian]' AND 
                     nis='$_SESSION[nis]' AND 
                     id_soal='$arr_soal[$i]'");
               }

         }elseif($rsoal['jenis']==3){
            $cekjawaban = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM jawaban WHERE 
               id_ujian='$_POST[ujian]' AND 
               nis='$_SESSION[nis]' AND 
               id_soal='$arr_soal[$i]'"));
            
            //Pecah kunci dan jawaban berdasarkan koma
            $kunci = explode(',', str_replace(' ', '', $rsoal['kunci']));
            $jawaban = explode(',', str_replace(' ', '', $cekjawaban['jawaban']));

            $minCount = min(count($kunci), count($jawaban));

            $benar = 0;
            for ($j = 0; $j < $minCount; $j++) {
               if ($kunci[$j] == $jawaban[$j]) {
                  $benar++;
               }
            }

            // Jika ada jawaban benar, beri nilai proporsional
            if($benar > 0){
               $total_kunci = count($kunci);
               $persentase_benar = $benar / $total_kunci;
               $nilai_tambah = $rsoal['bobot'] * $persentase_benar;

               $nilai += $nilai_tambah;

               mysqli_query($mysqli, "UPDATE jawaban SET nilai='$nilai_tambah' WHERE 
                     id_ujian='$_POST[ujian]' AND 
                     nis='$_SESSION[nis]' AND 
                     id_soal='$arr_soal[$i]'");
               // Jika semua benar, tambahkan ke jumlah benar penuh
               if($benar == $total_kunci){
                     $jbenar++;
               }
            }
         }
      }	 
   }
	
   mysqli_query($mysqli, "UPDATE nilai SET jml_benar='$jbenar', nilai='$nilai' WHERE id_ujian='$_POST[ujian]' AND nis='$_SESSION[nis]'");
   
   mysqli_query($mysqli, "UPDATE siswa SET status='login' WHERE nis='$_SESSION[nis]'");
   
  echo "ok";
}
?>