<script type="text/javascript" src="js/ujian.js"></script>
<?php
session_start();
include "library/config.php"; 
include "library/cek_blok.php";

if(empty($_SESSION['username']) or empty($_SESSION['password']) ){
   header('location: login.php');
}


//1 Update status siswa dan membuat array data untuk dimasukkan ke tabel nilai
mysqli_query($mysqli, "UPDATE siswa SET status='mengerjakan' WHERE nis='$_SESSION[nis]'");

$rujian = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM ujian WHERE id_ujian='$_GET[ujian]'"));

$arr_soal = array();
$arr_jawaban = array();

//Soal Pilihan Ganda
$qsoal_pg = mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$_GET[ujian]' AND jenis='0' ORDER BY rand() LIMIT $rujian[jml_soal]");
while($rsoal = mysqli_fetch_array($qsoal_pg)){
   $arr_soal[] = $rsoal['id_soal'];
   $arr_jawaban[] = 0;
}

//Soal Pilihan Ganda Kompleks
$qsoal_pgk = mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$_GET[ujian]' AND jenis='2' ORDER BY rand() LIMIT $rujian[jml_soal]");
while($rsoal = mysqli_fetch_array($qsoal_pgk)){
   $arr_soal[] = $rsoal['id_soal'];
   $arr_jawaban[] = 0;
}

//Soal Pernyataan
$qsoal_pny = mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$_GET[ujian]' AND jenis='3' ORDER BY rand() LIMIT $rujian[jml_soal]");
while($rsoal = mysqli_fetch_array($qsoal_pny)){
   $arr_soal[] = $rsoal['id_soal'];
   $arr_jawaban[] = 0;
}


//Soal Esay
$qsoal_esay = mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$_GET[ujian]' AND jenis='1' ORDER BY rand() LIMIT $rujian[jml_soal]");
while($rsoal = mysqli_fetch_array($qsoal_esay)){
   $arr_soal[] = $rsoal['id_soal'];
   $arr_jawaban[] = 0;
}

if(count($arr_soal)==0) die('<div class="alert alert-warning">Belum ada soal pada ujian ini</div>');

$acak_soal = implode(",", $arr_soal);
$jawaban = implode(",", $arr_jawaban);

//2 Memasukkan data ke tabel nilai jika data nilai belum ada
$qnilai = mysqli_query($mysqli, "SELECT * FROM nilai WHERE nis='$_SESSION[nis]' AND id_ujian='$_GET[ujian]'");
if(mysqli_num_rows($qnilai) < 1){
   mysqli_query($mysqli, "INSERT INTO nilai SET nis='$_SESSION[nis]', id_ujian='$_GET[ujian]', acak_soal='$acak_soal', jawaban='$jawaban', sisa_waktu='$rujian[waktu]:00'");
}

//3 Menampilkan judul mapel dan sisa waktu
$qnilai = mysqli_query($mysqli, "SELECT * FROM nilai WHERE nis='$_SESSION[nis]' AND id_ujian='$_GET[ujian]'");
$rnilai = mysqli_fetch_array($qnilai);
$sisa_waktu = explode(":", $rnilai['sisa_waktu']);
$menit = isset($sisa_waktu[0]) ? $sisa_waktu[0] : '00';
$detik = isset($sisa_waktu[1]) ? $sisa_waktu[1] : '00';

echo '<h3 class="page-header">
  <div class="row">
    
    <!-- Mapel -->
    <div class="col-xs-12 col-sm-7">
      <b>Mapel: ' . htmlspecialchars($rujian['nama_mapel']) . '</b>
    </div>

    <!-- Sisa Waktu -->
    <div class="col-xs-12 col-sm-5 text-right">
      <b>
        Sisa Waktu:
        <span class="label label-primary">
          <span class="menit">' . $menit . '</span> :
          <span class="detik">' . $detik . '</span>
        </span>
      </b>
    </div>

  </div>
</h3>

<input type="hidden" id="ujian" value="'.$_GET['ujian'].'">
<input type="hidden" id="waktu" value="'.$rujian['waktu'].'">
<input type="hidden" id="minwaktu" value="'.$rujian['minwaktu'].'">
<input type="hidden" id="mulai" value="'.$rujian['mulai'].'">
<input type="hidden" id="selesai" value="'.$rujian['selesai'].'">
<input type="hidden" id="sisa_waktu">';
	
echo '<div class="row">
	<div class="col-md-8"><div class="konten-ujian">';	

//4 Mengambil data soal dari database
$arr_soal = explode(",", $rnilai['acak_soal']);
$arr_jawaban = explode(",", $rnilai['jawaban']);
$arr_class = array();
for($s=0; $s<count($arr_soal); $s++){
   $rsoal = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM soal WHERE id_soal='$arr_soal[$s]'"));

//5 Menampilkan no. soal dan soal	
   $no = $s+1;
   $soal = str_replace("../media", "media", $rsoal['soal']);
   $active = ($no==1) ? "active" : "";
   echo '<div class="blok-soal soal-'.$no.' '.$active.'">
<div class="box">
<div class="row">
   <div class="col-xs-1"><div class="nomor">'.$no.'</div></div>
   <div class="col-xs-11"><div class="soal">'.$soal.'</div> </div>
</div>';

if (isset($arr_jawaban[$s]) && $arr_jawaban[$s] != 0) {
    $arr_class[$no] = "green";
} else {
    $arr_class[$no] = "";
}

//PILIHAN GANDA
if($rsoal['jenis'] == 0){
//6 Membuat array pilihan dan mengacak pilihan
   $arr_pilihan = array();
   $arr_pilihan[] = array("no" => 1, "pilihan" => $rsoal['pilihan_1']);
   $arr_pilihan[] = array("no" => 2, "pilihan" => $rsoal['pilihan_2']);
   $arr_pilihan[] = array("no" => 3, "pilihan" => $rsoal['pilihan_3']);
   $arr_pilihan[] = array("no" => 4, "pilihan" => $rsoal['pilihan_4']);
   $arr_pilihan[] = array("no" => 5, "pilihan" => $rsoal['pilihan_5']);
   shuffle($arr_pilihan);

//7 Menampilkan pilihan	
   $arr_huruf = array("A","B","C","D","E");	
   for($i=0; $i<=4; $i++){
      $checked = (isset($arr_jawaban[$s]) && $arr_jawaban[$s] == $arr_pilihan[$i]['no']) ? "checked" : "";
      $pilihan = str_replace("../media", "media", $arr_pilihan[$i]['pilihan']);
      echo '<div class="row">
         <div class="col-xs-11 col-xs-offset-1">
            <div class="pilihan">
               <input type="radio" name="jawab-'.$no.'" id="huruf-'.$no.'-'.$i.'" '.$checked.'>
               <label for="huruf-'.$no.'-'.$i.'" class="huruf" onclick="kirim_jawaban('.$s.', '.$arr_pilihan[$i]['no'].')"> '.$arr_huruf[$i].' </label>
         
               <div class="teks">'.$pilihan.' </div> 
            </div>
         </div>
      </div>';
   }

//PILIHAN GANDA KOMPLEKS
}elseif($rsoal['jenis'] == 2){   
   $idsoal = $rsoal['id_soal'];
   $jawaban = '';
   $rjawab =  mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM jawaban WHERE 
      id_ujian='$_GET[ujian]' AND 
      nis='$_SESSION[nis]' AND 
      id_soal='$idsoal'"));
   if($rjawab) $jawaban = $rjawab['jawaban'];

   $arr_jawaban = explode(",", $jawaban);

//6 Membuat array pilihan dan mengacak pilihan
   $arr_pilihan = array();
   $arr_pilihan[] = array("no" => 1, "pilihan" => $rsoal['pilihan_1']);
   $arr_pilihan[] = array("no" => 2, "pilihan" => $rsoal['pilihan_2']);
   $arr_pilihan[] = array("no" => 3, "pilihan" => $rsoal['pilihan_3']);
   if(!empty($rsoal['pilihan_4'])) $arr_pilihan[] = array("no" => 4, "pilihan" => $rsoal['pilihan_4']);
   if(!empty($rsoal['pilihan_5'])) $arr_pilihan[] = array("no" => 5, "pilihan" => $rsoal['pilihan_5']);
   shuffle($arr_pilihan);

//7 Menampilkan pilihan	
   
   echo '<div class="row jawaban">
      <form onsubmit="kirim_jawaban_kompleks('.$s.','.$idsoal.', event)"  id="form-jawaban-'.$s.'">';
   for($i=0; $i<count($arr_pilihan); $i++){
      $checked = (in_array($arr_pilihan[$i]['no'], $arr_jawaban)) ? "checked" : "";
      $pilihan = str_replace("../media", "media", $arr_pilihan[$i]['pilihan']);
      $nopilihan = $arr_pilihan[$i]['no'];
      echo '<div class="row">
         <div class="col-xs-11 col-xs-offset-1">
            <div class="pilihan">
               <input type="checkbox" name="jawab-'.$no.'[]" value="'.$nopilihan.'" '.$checked.'>      
               <div class="teks">'.$pilihan.' </div> 
            </div>
         </div>
      </div>';
   }
   echo '<div class="col-xs-12" style="margin-top: 10px;">
         <button class="btn btn-success" type="submit">Kirim Jawaban</button>
      </div>
      </form> </div>';

   
//MENCOCOKKAN PERNYATAAN
}elseif($rsoal['jenis'] == 3){   
   $idsoal = $rsoal['id_soal'];
   $jawaban = '';
   $rjawab =  mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM jawaban WHERE 
      id_ujian='$_GET[ujian]' AND 
      nis='$_SESSION[nis]' AND 
      id_soal='$idsoal'"));
   if($rjawab) $jawaban = $rjawab['jawaban'];

   $arr_jawaban = !empty($rjawab['jawaban']) 
      ? explode(",", $rjawab['jawaban']) 
      : [];

//6 Membuat array pilihan dan mengacak pilihan
   $arr_pilihan = array();
   $arr_pilihan[] = array("no" => 1, "pilihan" => $rsoal['pilihan_1']);
   $arr_pilihan[] = array("no" => 2, "pilihan" => $rsoal['pilihan_2']);
   if(!empty($rsoal['pilihan_3']))$arr_pilihan[] = array("no" => 3, "pilihan" => $rsoal['pilihan_3']);
   if(!empty($rsoal['pilihan_4'])) $arr_pilihan[] = array("no" => 4, "pilihan" => $rsoal['pilihan_4']);
   if(!empty($rsoal['pilihan_5'])) $arr_pilihan[] = array("no" => 5, "pilihan" => $rsoal['pilihan_5']);
   shuffle($arr_pilihan);

//7 Menampilkan pilihan	
   
   echo '<div class="row jawaban">
      <form onsubmit="kirim_jawaban_pernyataan('.$s.','.$idsoal.', event)"  id="form-jawaban-'.$s.'">
      <div class="row">
         <div class="col-xs-10 col-xs-offset-1">
      <table class="table table-bordered" width="100%">
         <tr><th class="text-center">Pernyataan</th>';
      $parameter = explode(",", $rsoal['parameter']);
      for($i=0; $i<count($parameter); $i++){
         echo '<th class="text-center">'.$parameter[$i].'</th>';
      }

   echo '</tr>';
   for($i=0; $i<count($arr_pilihan); $i++){
      $pilihan = str_replace("../media", "media", $arr_pilihan[$i]['pilihan']);
      $nopilihan = $arr_pilihan[$i]['no'];
      echo '<tr>
         <td>'.$pilihan.'</td>';
         for($j=0; $j<count($parameter); $j++){
            $param_no = $j+1;            
            $checked = (isset($arr_jawaban[$nopilihan - 1]) && $param_no == $arr_jawaban[$nopilihan - 1])
               ? "checked"
               : "";
            echo '<td class="text-center">
                  <input type="radio" name="jawab-'.$no.'-'.$nopilihan.'" data-index="'.$nopilihan.'" value="'.$param_no.'" '.$checked.'>
                  </td>  ';
         }
      echo '</tr>';
   }
   echo '</table>
         </div>
      </div>
      <div class="col-xs-11 col-xs-offset-1" style="margin-top: 10px;">
         <button class="btn btn-success" type="submit">Kirim Jawaban</button>
      </div>
      </form> </div>';


//SOAL ESAY
}else{
   $idsoal = $rsoal['id_soal'];
   $jawaban = '';
   $rjawab =  mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM jawaban WHERE 
      id_ujian='$_GET[ujian]' AND 
      nis='$_SESSION[nis]' AND 
      id_soal='$idsoal'"));
   if($rjawab) $jawaban = $rjawab['jawaban'];

   echo '<div class="row jawaban">
      <form onsubmit="kirim_esay('.$s.','.$idsoal.', event)">
      <div class="col-xs-11 col-xs-offset-1" style="margin-top: 10px;">
         <textarea class="form-control jawaban-'.$s.'" rows="3" placeholder="Tulis jawaban...">'. $jawaban .'</textarea>
      </div>
      <div class="col-xs-11 col-xs-offset-1" style="margin-top: 10px;">
         <button class="btn btn-success" type="submit">Kirim Jawaban</button>
      </div>
      </form>
      </div>';
}

//8 Menampilkan tombol sebelumnya, ragu-ragu dan berikutnya
   echo '</div><br/><div class="row"><div class="col-md-3">';
   
   $sebelumnya = $no-1;
   if($no != 1) echo '<a class="btn btn-primary btn-block" onclick="tampil_soal('.$sebelumnya.')">Sebelumnya</a>';
   echo '</div>
   <div class="col-md-4 col-md-offset-1"><label class="btn btn-warning btn-block"> <input type="checkbox" autocomplete="off" onchange="ragu_ragu('.$no.')"> Ragu-ragu </label></div>	
<div class="col-md-3 col-md-offset-1">';
	
   $berikutnya = $no+1;
   if($no != count($arr_soal)){
     echo '<a class="btn btn-primary btn-block" onclick="tampil_soal('.$berikutnya.')"> Berikutnya </a>';
   }else{
     echo '<a class="btn btn-danger btn-end btn-block" onclick="selesaikan()"> Selesai </a>';
     echo '<a class="btn btn-danger btn-time btn-block disabled"> </a>';
   }

   echo '</div></div></div>';
}

echo '</div></div>
	<div class="col-md-4"><div class="nomor-ujian">';

//9 Menampilkan nomor ujian
for($j=1; $j<=$s; $j++){
   echo '<div class="blok-nomor"><div class="box"> <a class="tombol-nomor tombol-'.$j.' '.$arr_class[$j].'" onclick="tampil_soal('.$j.')">'.$j.'</a></div></div>';
}
echo '</div></div></div>';

//10 Menampilkan modal ketika selesai ujian
echo '<div class="modal fade" id="modal-selesai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg">
   <div class="modal-content">
   <form  onsubmit="return selesai_ujian('.$_GET['ujian'].')">
      
<div class="modal-header">
  <h3 class="modal-title">Selesai Ujian</h3>
</div>
		
<div class="modal-body">
   <p>Pastikan semua soal telah dikerjakan sebelum mengklik selesai. Setelah klik selesai Anda tidak dapat mengerjakan ujian lagi. Yakin akan menyelesaikan ujian? </p>
   <div class="chekbox-selesai"><input type="checkbox" required> Saya yakin akan menyelesaikan ujian.</div>
</div>
		
<div class="modal-footer">
   <button type="submit" class="btn btn-danger" onclick="return selesai_ujian('.$_GET['ujian'].')"> Selesai </button>
   <button type="button" class="btn btn-warning" data-dismiss="modal"> Batal </button>
</div>
		
</form></div></div></div>';
?>
