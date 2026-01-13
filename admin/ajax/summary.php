<?php
include "../library/config.php";

function hitung($db, $status) {
    $q = mysqli_query($db, "SELECT COUNT(*) AS jml FROM siswa WHERE status='$status'");
    if (!$q) return 0;
    $r = mysqli_fetch_assoc($q);
    return (int)$r['jml'];
}

header("Content-Type: application/json");

echo json_encode([
    "login"       => hitung($mysqli, "login"),
    "mengerjakan" => hitung($mysqli, "mengerjakan"),
    "selesai"     => hitung($mysqli, "selesai"),
    "lock"        => hitung($mysqli, "lock")
]);
