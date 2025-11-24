<link rel="stylesheet" type="text/css" href="../assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<script type="text/javascript" src="../assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript" src="script/script_ujian.js"> </script>

<?php
session_start();
if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['leveluser']!="admin"){
   header('location: ../login.php');
}

//include library yang diperlukan
include "../../library/config.php";
include "../../library/function_view.php";
include "../../library/function_form.php";

//membuat judul dan tombol tambah
create_title("book", "Manajemen Ujian");
open_content();

create_button("success", "plus-circle", "Tambah", "btn-add", "form_add()");

//membuat header dan footer tabel
create_table(array("Judul", "Nama Mapel", "Tanggal", "Waktu", "Waktu Min", "Mulai", "Selesai", "Jml. Soal", "Pengampu", "Aksi"));

//membuat form tambah dan edit data
open_form("modal_ujian", "return save_data()");
   create_textbox("Judul", "judul", "text", 4, "", "required");
   create_textbox("Nama Mapel", "mapel", "text", 4, "", "required");
   create_textbox("Tanggal", "tanggal", "text", 4, "datepicker", "required");
   create_textbox("Waktu (menit)", "waktu", "number", 2, "", "required");
   create_textbox("Waktu Minimal (menit)", "minwaktu", "number", 2, "", "required");
   create_textbox("Mulai", "mulai", "time", 2, "", "required");
   create_textbox("Selesai", "selesai", "time", 2, "", "required");
   create_textbox("Jml. Soal", "jml_soal", "number", 2, "", "required");
	
   $quser = mysqli_query($mysqli, "SELECT * FROM user WHERE level='guru'");
   $list = array();
   while($ru = mysqli_fetch_array($quser)){
      $list[] = array($ru['id_user'], $ru['nama']);
   }
   create_combobox("Pengampu", "pengampu", $list, 4, "", "required");	
close_form();

close_content();
?>
