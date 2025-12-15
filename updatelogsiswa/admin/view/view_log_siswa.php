<script type="text/javascript" src="script/script_log_siswa.js"> </script>

<?php
session_start();
if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['leveluser']!="operator"){
   header('location: login.php');
}

include "../../library/config.php";
include "../../library/function_view.php";


create_title("history", "Log Login Siswa");
open_content();

create_table(array("Tanggal", "Jam", "NIS", "Nama Siswa", "Kelas", "Alamat IP"));
close_content();
?>