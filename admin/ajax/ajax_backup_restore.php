<?php
session_start();
include "../../library/config.php";

$backup_folder = "../backup/";

if($_GET['action'] == "backup"){

    if(!is_dir($backup_folder)) mkdir($backup_folder);

    $file_name = "backup_" . date("Y-m-d_H-i-s") . ".sql";
    $file_path = $backup_folder . $file_name;

    $tables = array();
    $result = mysqli_query($mysqli, "SHOW TABLES");

    while($row = mysqli_fetch_row($result)){
        $tables[] = $row[0];
    }

    $backup_data = "";

    foreach($tables as $table){

        // Struktur tabel
        $structure = mysqli_query($mysqli, "SHOW CREATE TABLE $table");
        $row = mysqli_fetch_row($structure);

        $backup_data .= "\n\n-- ------------------------------\n";
        $backup_data .= "-- Table structure for `$table`\n";
        $backup_data .= "-- ------------------------------\n\n";

        $backup_data .= "DROP TABLE IF EXISTS `$table`;\n";
        $backup_data .= $row[1] . ";\n\n";

        // Data tabel
        $backup_data .= "-- Dumping data for table `$table`\n\n";

        $data = mysqli_query($mysqli, "SELECT * FROM $table");

        while($row = mysqli_fetch_assoc($data)){
            $cols = array_keys($row);
            $vals = array_values($row);
            $vals = array_map(function($v){
                return addslashes($v);
            }, $vals);

            $backup_data .= "INSERT INTO `$table` (`".implode("`,`",$cols)."`) VALUES ('".implode("','",$vals)."');\n";
        }

        $backup_data .= "\n\n";
    }

    file_put_contents($file_path, $backup_data);

    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$file_name");
    echo $backup_data;
    exit;
}



// =======================
//  RESTORE DATABASE
// =======================

if($_GET['action'] == "restore"){

    if(empty($_FILES['file']['tmp_name'])){
        echo "Tidak ada file yang diupload!";
        exit;
    }

    $sql_content = file_get_contents($_FILES['file']['tmp_name']);

    $queries = explode(";\n", $sql_content);

    mysqli_query($mysqli, "SET FOREIGN_KEY_CHECKS = 0");

    foreach($queries as $query){
        $trim = trim($query);
        if($trim != ""){
            mysqli_query($mysqli, $trim);
        }
    }

    mysqli_query($mysqli, "SET FOREIGN_KEY_CHECKS = 1");

    echo "Restore database berhasil!";
}
?>
