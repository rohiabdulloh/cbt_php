<script type="text/javascript" src="script/script_filesoal_teacher.js"> </script>

<?php
session_start();
if(empty($_SESSION['username']) or empty($_SESSION['password']) or $_SESSION['leveluser']!="guru"){
   header('location: login.php');
}

include "../../library/config.php";
include "../../library/function_view.php";
include "../../library/function_form.php";

create_title("file", "Upload File Soal");

open_content();
create_button("success", "plus-circle", "Tambah", "btn-add", "form_add()");

echo '<hr/><div class="alert alert-info"><ul>
<li>Klik tambah untuk menambahkan file soal dan kisi-kisi soal!</li>
</ul></div>';

create_table(array("Nama Mapel", "Nama Guru", "NIP", "Link Soal", "Link Kisi-kisi", "Tanggal Uplaod", "Aksi"));

//membuat form tambah dan edit data
open_form("modal_filesoal", "return save_data()");
    $qujian = mysqli_query($mysqli, "SELECT * FROM ujian ORDER BY id_ujian desc");
    $list = array();
    while($ru = mysqli_fetch_array($qujian)){
        $list[] = array($ru['id_ujian'], $ru['nama_mapel']);
    }

   create_combobox("Nama Mapel", "ujian", $list, 6, "", "required");	
   create_textbox("Nama Guru", "nama_guru", "text", 6, "", "required");
   create_textbox("NIP", "nip", "text", 4, "", "required");
   create_textbox("File Soal", "filesoal", "file", 6, "", "required accept='.pdf,.doc,.docx,.xls,.xlsx'");
   create_textbox("File Kisi-kisi", "filekisi", "file", 6, "", "required accept='.pdf,.doc,.docx,.xls,.xlsx'");
close_form();
close_content();
?>