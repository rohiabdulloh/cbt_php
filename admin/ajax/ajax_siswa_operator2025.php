<?php
session_start();
include "../../library/config.php";

//Menampilkan data pada tabel
if($_GET['action'] == "table_data"){
   $query = mysqli_query($mysqli, "SELECT * FROM siswa ORDER BY nis");
   $data = array();
   $no = 1;
   while($r = mysqli_fetch_array($query)){
      $kelas = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM kelas WHERE id_kelas='$r[id_kelas]'"));

      if(strtolower($r['status'] == "login")) $status = '<b class="text-primary">login</b>';
      elseif(strtolower($r['status'] == "mengerjakan")) $status = '<b class="text-danger">mengerjakan</b>';
	   elseif(strtolower($r['status'] == "lock")) $status = '<b class="text-danger">Terkunci</b>';
	   elseif($r['jmlog'] >= 3 ) $status = '<b class="text-danger">Terkunci</b>';
      else $status = '<b class="text-muted">off</b>';
	 
      $row = array();
      $row[] = $no;
      $row[] = $r['nis'];
      $row[] = $r['nama'];
      $row[] = substr(md5($r['nis']),0,5);
      $row[] = $kelas['kelas'];
	  $row[] = $r['no_ruang'];
	  $row[] = $r['jmlog'];
      $row[] = $status;
      $row[] = '<a class="btn btn-danger" onclick="reset_login('.$r['nis'].')"><i class="glyphicon glyphicon-off"></i> Reset/Unlock</a>';
	  $row[] = '<a class="btn btn-default active" onclick="Lock_login('.$r['nis'].')"><i class="glyphicon glyphicon-off"></i> Lock System </a>';
      $data[] = $row;
      $no++;
   }
	
   $output = array("data" => $data);
   echo json_encode($output);
}


//Reset login
elseif($_GET['action'] == "reset_login"){
   mysqli_query($mysqli, "UPDATE siswa set status='off' , jmlog = 0  WHERE nis='$_GET[nis]'");
}

elseif($_GET['action'] == "Lock_login"){
   mysqli_query($mysqli, "UPDATE siswa set status='lock' , jmlog = 3  WHERE nis='$_GET[nis]'");
}

elseif($_GET['action'] == "UNLock_login"){
   mysqli_query($mysqli, "UPDATE siswa set status='off' , jmlog = 0  WHERE status<>'0'");
}


?>