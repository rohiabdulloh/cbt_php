<?php
session_start();
include "../../library/config.php";
include "../../library/function_date.php";
include "../../library/function_view.php";

//Menampilkan data ke tabel
if($_GET['action'] == "table_data"){
   $query = mysqli_query($mysqli, "SELECT * FROM filesoal t1 LEFT JOIN ujian t2 ON t1.id_ujian=t2.id_ujian 
     ORDER BY t1.id_filesoal DESC");
   $data = array();
   $no = 1;
   while($r = mysqli_fetch_array($query)){
      $row = array();
      $row[] = $no;
      $row[] = $r['nama_mapel'];
      $row[] = $r['nama_guru'];
      $row[] = $r['nip'];
      $row[] = '<a target="_blank" href="filesoal/'.$r['filesoal'].'">'.$r['filesoal'].'</a>';
      $row[] = '<a target="_blank" href="filesoal/'.$r['filekisi'].'">'.$r['filekisi'].'</a>';
      $row[] = tgl_indonesia($r['tanggal_upload']);
	  $row[] = '<a class="btn btn-danger" style="margin-left: 5px;" 
	            onclick="delete_data('.$r['id_filesoal'].')">
                     <i class="glyphicon glyphicon-trash"></i>
                </a>';
      $data[] = $row;
      $no++;
   }
	
   $output = array("data" => $data);
   echo json_encode($output);
}


elseif($_GET['action'] == "delete"){
   $query = mysqli_query($mysqli, "SELECT * FROM filesoal WHERE id_filesoal='$_GET[id]'");
   $data = mysqli_fetch_array($query);
   if($data){
	  if(file_exists('../filesoal/'.$data['filesoal'])){
		unlink('../filesoal/'.$data['filesoal']);
	  }
	  if(file_exists('../filesoal/'.$data['filekisi'])){
		unlink('../filesoal/'.$data['filekisi']);
	  }
   }
   mysqli_query($mysqli, "DELETE FROM filesoal WHERE id_filesoal='$_GET[id]'");	
}
?>
