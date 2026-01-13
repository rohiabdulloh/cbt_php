<?php
session_start();
if (
    empty($_SESSION['username']) ||
    empty($_SESSION['password']) ||
    $_SESSION['leveluser'] != "operator"
) {
    header('location: ../login.php');
    exit;
}

include "../../library/config.php";
include "../../library/function_view.php";
include "../../library/function_form.php";

/* ===============================
   PASTIKAN VARIABEL KONEKSI
================================ */


// AUTO DETEKSI KONEKSI
if (isset($koneksi)) {
    $db = $koneksi;
} elseif (isset($mysqli)) {
    $db = $mysqli;
} elseif (isset($link)) {
    $db = $link;
} elseif (isset($conn)) {
    $db = $conn;
} else {
    die("Koneksi database tidak tersedia (cek config.php)");
}

/* ===============================
   HITUNG STATUS SISWA
================================ */
function hitung($db, $status) {
    $q = mysqli_query($db, "SELECT COUNT(*) AS jml FROM siswa WHERE status='$status'");
    if (!$q) return 0;
    $r = mysqli_fetch_assoc($q);
    return $r['jml'];
}

$jml_login  = hitung($db, 'login');
$jml_logout = hitung($db, 'logout');
$jml_kerja  = hitung($db, 'mengerjakan');
$jml_lock   = hitung($db, 'lock');

/* ===============================
   TAMPILAN
================================ */
create_title("list-alt", "Manajemen Siswa");
open_content();
?>

<!-- ===============================
     LOAD JS (WAJIB DI SINI)
================================ -->
<script type="text/javascript" src="script/script_siswa_operator.js"></script>

<!-- ===============================
     INFO JUMLAH STATUS
================================ -->
<!-- ===============================
     INFO JUMLAH STATUS
================================ -->
<div class="row">
    <div class="col-md-3">
        <div class="alert alert-success text-center">
            <b>LOGIN</b><br><?= $jml_login ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="alert alert-danger text-center">
            <b>LOGOUT</b><br><?= $jml_logout ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="alert alert-info text-center">
            <b>MENGERJAKAN</b><br><?= $jml_kerja ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="alert alert-danger text-center">
            <b>TERKUNCI</b><br><?= $jml_lock ?>
        </div>
    </div>
</div>

<?php
/* ===============================
   BUTTON
================================ */
create_button("success", "refresh", "Refresh", "btn-refresh", "refresh_data()");
create_button("warning", "glyphicon glyphicon-off", "RESET / UNLOCK ALL", "btn btn-warning", "UNLock_login()");

/* ===============================
   TABEL
================================ */
create_table(array(
    "NIS",
    "Nama Siswa",
    "Psd",
    "Kls",
    "Ru",
    "Ind",
    "Sts",
    "App",
    "IP Address",
    "Aksi",
    "Lock"
));

close_content();
?>
