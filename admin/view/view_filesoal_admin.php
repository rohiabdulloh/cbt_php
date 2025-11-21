<script type="text/javascript" src="script/script_filesoal_admin.js"> </script>

<?php
session_start();
if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['leveluser']!="admin"){
   header('location: login.php');
}

include "../../library/config.php";
include "../../library/function_view.php";


create_title("file", "File Soal");
open_content();
create_button("primary", "download", "Export", "btn-download", "export_filesoal()");

create_table(array("Nama Mapel", "Nama Guru", "NIP", "Link Soal", "Link Kisi-kisi", "Tanggal Uplaod", "Aksi"));
close_content();
?>