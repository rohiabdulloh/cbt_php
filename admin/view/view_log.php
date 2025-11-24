<script type="text/javascript" src="script/script_log.js"> </script>

<?php
session_start();
if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['leveluser']!="admin"){
   header('location: login.php');
}

include "../../library/config.php";
include "../../library/function_view.php";


create_title("history", "Log Login");
open_content();

create_table(array("Tanggal", "Jam", "Nama User", "Alamat IP", "Aksi"));
close_content();
?>