<script type="text/javascript" src="script/script_nilai.js"> </script>

<?php
session_start();
if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['leveluser']!="guru"){
   header('location: ../login.php');
}

include "../../library/function_view.php";
include "../../library/function_form.php";

create_title("check", "Hasil Ujian");

open_content();

create_button("primary", "file-excel", "Export", "btn-add", "export_nilai()");
create_button("success", "download", "Download Esay", "btn-download", "download_esay()");
create_button("danger", "trash", "Reset Nilai", "btn-reset", "reset_all()");

echo '<input type="hidden" id="id_ujian" value="'.$_GET['ujian'].'">
<input type="hidden" id="id_kelas" value="'.$_GET['kelas'].'">';
	  
create_table(array("NIS", "Nama Siswa", "Kelas", "Nilai PG", "Nilai PG Kompleks", "Nilai Mencocokkan", "Nilai Esay", "Total Nilai", "Aksi"));


open_form("modal_nilai", "return save_data()");
   echo '<input type="hidden" id="idujian" name="ujian" value="'.$_GET['ujian'].'">
      <input type="hidden" id="nis" name="nis" value="">';
   echo '<div class="form-koreksi"></div>';
close_form();

close_content();
?>
