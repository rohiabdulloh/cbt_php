<?php
session_start();
include "../../library/config.php";
include "../../library/function_date.php";
include "../../library/function_view.php";

// Menampilkan data log login ke tabel
if($_GET['action'] == "table_data"){
    // Ambil data log login dan gabungkan dengan tabel user
    $query = mysqli_query($mysqli, "
        SELECT l.*, u.nama 
        FROM log_login l 
        LEFT JOIN user u ON l.id_user = u.id_user 
        ORDER BY l.id_log DESC
    ");

    $data = array();
    $no = 1;
    while($r = mysqli_fetch_array($query)){
        $row = array();
        $row[] = $no;                        // No
        $row[] = tgl_indonesia($r['tanggal']); // Tanggal (format Indonesia)
        $row[] = $r['jam'];                  // Jam
        $row[] = $r['nama'];                 // Nama User
        $row[] = $r['ip_address'];   
        $row[] = create_action($r['id_log'], false, true);        
        $data[] = $row;
        $no++;
    }

    $output = array("data" => $data);
    echo json_encode($output);
}
elseif($_GET['action'] == "delete"){
   mysqli_query($mysqli, "DELETE FROM log_login WHERE id_log='$_GET[id]'");	
}
?>
