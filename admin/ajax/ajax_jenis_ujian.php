<?php
session_start();
include "../../library/config.php";
include "../../library/function_view.php";

if($_GET['action'] == "table_data"){
   $query = mysqli_query($mysqli, "SELECT * FROM jenis_ujian ORDER BY id_jenis DESC");
   $data = array();
      $no = 1;
      while($r = mysqli_fetch_array($query)){
         $row = array();
         $row[] = $no;
         $row[] = $r['nama_ujian'];
         $row[] = create_action($r['id_jenis']);
         $data[] = $row;
         $no++;
      }
	
   $output = array("data" => $data);
   echo json_encode($output);
}

elseif($_GET['action'] == "form_data"){
   $query = mysqli_query($mysqli, "SELECT * FROM jenis_ujian WHERE id_jenis='$_GET[id]'");
   $data = mysqli_fetch_array($query);	
   echo json_encode($data);
}

elseif($_GET['action'] == "insert"){   
   mysqli_query($mysqli, "INSERT INTO jenis_ujian SET nama_ujian = '$_POST[ujian]' ");	
}

elseif($_GET['action'] == "update"){
   mysqli_query($mysqli, "UPDATE jenis_ujian  SET nama_ujian= '$_POST[ujian]' WHERE id_jenis='$_POST[id]'");
}

elseif($_GET['action'] == "delete"){
   mysqli_query($mysqli, "DELETE FROM jenis_ujian WHERE id_jenis='$_GET[id]'");	
}
?>
