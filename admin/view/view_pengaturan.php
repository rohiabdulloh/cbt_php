<script type="text/javascript" src="script/script_pengaturan.js"></script>

<?php
session_start();
if(empty($_SESSION['username']) or empty($_SESSION['password'])){
   header('location: ../login.php');
}

include "../../library/config.php"; 
include "../../library/function_view.php";
include "../../library/function_form.php";

create_title("cog", "Pengaturan Tema");

open_content();

// ambil nilai dari tabel pengaturan
function get_setting($mysqli, $param) {
   $q = mysqli_query($mysqli, "SELECT nilai FROM setting WHERE parameter='$param'");
   $r = mysqli_fetch_array($q);
   return isset($r['nilai']) ? $r['nilai'] : '';
}

$tema_admin         = get_setting($mysqli, 'tema_admin');
$tema_login_admin   = get_setting($mysqli, 'tema_login_admin');
$tema_siswa         = get_setting($mysqli, 'tema_siswa');
$tema_login_siswa   = get_setting($mysqli, 'tema_login_siswa');

// buka form
echo '<form id="form-pengaturan" class="form-horizontal">';

// daftar pilihan combobox
$list_tema_admin = [
   ['klasik', 'Klasik'],
   ['adminlte', 'AdminLTE']
];

$list_tema_login_admin = [
   ['klasik', 'Klasik'],
   ['adminlte', 'AdminLTE']
];

$list_tema_siswa = [
   ['klasik', 'Klasik'],
   ['tka', 'TKA']
];

$list_tema_login_siswa = [
   ['klasik', 'Klasik'],
   ['tka', 'TKA']
];

// tampilkan input combobox
create_combobox("Tema Admin", "tema_admin", $list_tema_admin, 4, "", "", $tema_admin);
create_combobox("Tema Login Admin", "tema_login_admin", $list_tema_login_admin, 4, "", "", $tema_login_admin);
create_combobox("Tema Siswa", "tema_siswa", $list_tema_siswa, 4, "", "", $tema_siswa);
create_combobox("Tema Login Siswa", "tema_login_siswa", $list_tema_login_siswa, 4, "", "", $tema_login_siswa);

// tombol simpan
echo '<div class="form-group mt-4">
   <div class="col-md-2 col-md-offset-2">
      <button class="btn btn-primary btn-icon"> Simpan Pengaturan</button>
   </div>
</div>';

echo '</form>';
close_content();
?>
