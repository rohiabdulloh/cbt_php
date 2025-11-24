<script type="text/javascript" src="script/script_jenis_ujian.js"> </script>

<?php
session_start();
if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['leveluser']!="admin"){
   header('location: ../login.php');
}

include "../../library/function_view.php";
include "../../library/function_form.php";

create_title("book", "Jenis Ujian");

open_content();
create_button("success", "plus-circle", "Tambah", "btn-add", "form_add()");

create_table(array("Nama Ujian", "Aksi"));

open_form("modal_ujian", "return save_data()");
   create_textbox("Nama Ujian", "ujian", "text", 4, "", "required");
close_form();
close_content();
?>
