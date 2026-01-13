<?php
session_start();
include "library/config.php";

$id_siswa = $_SESSION['id_siswa'];

$stmt = $mysqli->prepare("SELECT status FROM siswa WHERE id_siswa=? LIMIT 1");
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$res = $stmt->get_result();
$d = $res->fetch_assoc();

if ($d['status'] == 'lock') {
    echo 'lock';
} else {
    echo 'ok';
}
