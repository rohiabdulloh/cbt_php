<script type="text/javascript" src="script/script_bagiruang_admin.js"> </script>

<?php
session_start();
if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['leveluser']!="admin"){
   header('location: login.php');
}

include "../../library/config.php";
include "../../library/function_view.php";
include "../../library/function_form.php";


create_title("th-large", "Bagi Ruang");

open_content();   
create_button("primary", "download", "Export", "btn-download", "export_bagiruang()");
create_button("success", "refresh", "Reset Bagi Ruang", "btn-refresh", "reset_bagiruang()");

create_table(array("NIS", "Nama Siswa", "Kelompok", "Ruang"));


open_form("modal_bagiruang", "return save_data()");
   create_textbox("Jml. per Ruang", "jml_siswa", "number", 2, "", "required");
   create_textbox("Jml. Kelas X", "kelas_x", "number", 2, "", "required");
   create_textbox("Jml. Kelas XI", "kelas_xi", "number", 2, "", "required");
   create_textbox("Jml. Kelas XII", "kelas_xii", "number", 2, "", "required");
   $list = array();
   $list[] = array(1, 'Zig-zag');
   $list[] = array(2, 'Kanan ke kiri');
   create_combobox("Layout", "layout", $list, 4, "", "required");	
close_form();
close_content();

?>