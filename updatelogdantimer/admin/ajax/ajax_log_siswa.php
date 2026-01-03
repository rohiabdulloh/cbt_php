<?php
session_start();
include "../../library/config.php";
include "../../library/function_date.php"; // kalau mau format tanggal indonesia

// Menampilkan data log login ke tabel
if($_GET['action'] == "table_data"){
    // Ambil data log login dan gabungkan dengan tabel user
    $query = mysqli_query($mysqli, "
        SELECT l.*, s.nama, s.nis, k.kelas AS kelas
        FROM log_login l 
        JOIN siswa s ON l.id_user = s.nis
        JOIN kelas k ON s.id_kelas = k.id_kelas
        ORDER BY l.id_log DESC
    ");

    $data = array();
    $no = 1;
    while($r = mysqli_fetch_array($query)){
        $row = array();
        $row[] = $no;                        
        $row[] = tgl_indonesia($r['tanggal']);
        $row[] = $r['jam'];                  
        $row[] = $r['nis'];                 
        $row[] = $r['nama'];                
        $row[] = $r['kelas'];                 
        $row[] = $r['ip_address'];         
        $data[] = $row;
        $no++;
    }

    $output = array("data" => $data);
    echo json_encode($output);
}
?>
