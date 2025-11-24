<script type="text/javascript" src="../assets/tinymce/tinymce.min.js"> </script>
<script type="text/javascript" src="script/script_soal.js"> </script>
<?php
session_start();
if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['leveluser']!="guru"){
   header('location: ../login.php');
}

//Memanggil library yang diperlukan
include "../../library/config.php";
include "../../library/function_view.php";
include "../../library/function_date.php";
include "../../library/function_form.php";

//Membuat judul, tombol Tambah dan tombol Import
create_title("list", "Manajemen Soal");

open_content();

create_button("success", "plus-circle", "Tambah", "btn-add", "form_add()");
create_button("primary", "download", "Download Format", "btn-export", "form_export()");
create_button("primary", "upload", "Import", "btn-import", "form_import()");

//Menampilkan detail ujian

$ru = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM ujian WHERE id_ujian='$_GET[ujian]'"));
$totalbobot = mysqli_fetch_row(mysqli_query($mysqli, "SELECT SUM(bobot) FROM soal WHERE id_ujian='$_GET[ujian]'"));
$totalsoal = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$_GET[ujian]'"));
echo '<hr/><div class="alert alert-info"><table width="100% no-ajax">
   <tr>
      <td>Judul Ujian</td><td>:<b> '.$ru['judul'].'</b></td>
      <td>Tanggal</td><td>:<b> ' .tgl_indonesia($ru['tanggal']).' </b></td>
      <td>Total Bobot.</td><td><label class="label label-primary">: <b><span class="totalbobot">'.$totalbobot[0].' </span> / 100 </b></label></td>
   </tr>
   <tr>
      <td>Nama Mapel</td><td>:<b> '.$ru['nama_mapel'].'</b></td>
      <td>Jml. Soal</td><td>:<b> <span class="totalsoal" data-max="'.$ru['jml_soal'].'"> '.$totalsoal.' </span>/ '.$ru['jml_soal'].'</b></td>
      <td>Status</td><td>:<b class="status"> ';
   if($totalsoal>=$ru['jml_soal'] or $totalbobot[0] >= 100){
      echo '<span class="text-success"> Lengkap</span>';
   } else {
      echo '<span class="text-danger"> Belum Lengkap</span>';
   }
echo '</b></td>
   </tr>
   
</table>
<input type="hidden" id="id_ujian" value="'.$_GET['ujian'].'">
</div>';

//Membuat header dan footer soal
create_table(array("Soal",  "Bobot", "Aksi"));

open_form("modal_soal", "return save_data()");
   $listjenis = [
      [0, 'Pilihan Ganda'],
      [1, 'Esay'], 
      [2, 'Pilihan Ganda Kompleks'], 
      [3, 'Mencocokkan Pernyataan'], 
   ];
   
   create_combobox("Jenis Soal", "jenis", $listjenis, 4, "", "required");
   create_textarea("Soal", "soal", "richtext");

   echo '<div class="pg_area">';
   create_textarea("Pilihan 1", "pil_1", "richtextsimple");
   create_textarea("Pilihan 2", "pil_2", "richtextsimple");
   create_textarea("Pilihan 3", "pil_3", "richtextsimple");
   create_textarea("Pilihan 4", "pil_4", "richtextsimple");
   create_textarea("Pilihan 5", "pil_5", "richtextsimple");
	
   $list = array();
   for($i=1; $i<=5; $i++){
      $list[] = array($i, $i);
   }
   echo '</div>';

   //Kunci pilihan ganda
   echo '<div class="pg_kunci_area">';
   create_combobox("Kunci Jawaban", "kunci", $list, 4, "", "required");
   echo '</div>';

   //Kunci pilihan ganda kompleks
   echo '<div class="kompleks_kunci_area">';
   $listkunci = [
      [1, 'Pilihan 1'], 
      [2, 'Pilihan 2'], 
      [3, 'Pilihan 3'], 
      [4, 'Pilihan 4'], 
      [5, 'Pilihan 5'],
   ];
   create_checkbox("Kunci Jawaban", "kunci_kompleks", $listkunci);
   echo '</div>';

   //Mencocokkan Pernyataan
   echo '<div class="matching_area">';
   
   create_textbox("Parameter", "parameter", "text", 10, "", "placeholder='Pisahkan dengan koma (,)'");
   create_textarea("Pernyataan 1", "pernyataan_1", "richtextsimple");
   echo '<div class="form-group row mb-2">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-8" id="kunci_pernyataan_1"></div>
      </div>';

   create_textarea("Pernyataan 2", "pernyataan_2", "richtextsimple");
   echo '<div class="form-group row mb-2">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-8" id="kunci_pernyataan_2"></div>
      </div>';

   create_textarea("Pernyataan 3", "pernyataan_3", "richtextsimple");
   echo '<div class="form-group row mb-2">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-8" id="kunci_pernyataan_3"></div>
      </div>';

   create_textarea("Pernyataan 4", "pernyataan_4", "richtextsimple");
   echo '<div class="form-group row mb-2">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-8" id="kunci_pernyataan_4"></div>
      </div>';

   create_textarea("Pernyataan 5", "pernyataan_5", "richtextsimple");
   echo '<div class="form-group row mb-2">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-8" id="kunci_pernyataan_5"></div>
      </div>';
?>


<?php
   echo '</div>';

  /* $blist = array();
	  for($i=1; $i<=100; $i++){
      $blist[] = array($i, $i);
	  
   }
   create_combobox("bobot Nilai", "bobot", $blist, 4, "", "required");*/
   
   create_textbox("Bobot Nilai", "bobot", "number", 4, "", "step='0.01' required");
   
close_form();

//Membuat form import soal
open_form("modal_import", "return import_data()");
   create_textbox("Pilih File .xls", "file", "file", 6, "", "required");
close_form("import", "Import");

close_content();
?>