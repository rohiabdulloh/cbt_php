<?php
session_start();
include "../../library/config.php";
include "../../library/function_date.php";
include "../../library/function_view.php";

//Menampilkan data ke tabel
if($_GET['action'] == "table_data"){
   $query = mysqli_query($mysqli, "SELECT * FROM filesoal t1 LEFT JOIN ujian t2 ON t1.id_ujian=t2.id_ujian 
        WHERE t1.id_user='$_SESSION[iduser]' ORDER BY t1.id_filesoal DESC");
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
      $row[] = create_action($r['id_filesoal']);
      $data[] = $row;
      $no++;
   }
	
   $output = array("data" => $data);
   echo json_encode($output);
}

//Menampilkan data ke form
elseif($_GET['action'] == "form_data"){
   $query = mysqli_query($mysqli, "SELECT * FROM filesoal WHERE id_filesoal='$_GET[id]'");
   $data = mysqli_fetch_array($query);	
   echo json_encode($data);
}

//Menambah data
elseif($_GET['action'] == "insert"){
    $filesoal = $_FILES['filesoal'];
    $filekisi = $_FILES['filekisi'];
    
    // Example destination directory to store uploaded PDF files
    $uploadDir = '../filesoal/';
    $namaFileSoal = $_POST['id_ujian'].'_'. basename($filesoal['name']);
    $namaFileKisi = $_POST['id_ujian'].'_'. basename($filekisi['name']);

    move_uploaded_file($filesoal['tmp_name'], $uploadDir.$namaFileSoal); 
    move_uploaded_file($filekisi['tmp_name'], $uploadDir.$namaFileKisi); 
   
    $tanggal = date('Y-m-d');
    $save = mysqli_query($mysqli, "INSERT INTO filesoal SET
      id_ujian = '$_POST[id_ujian]',
      nama_guru = '$_POST[nama_guru]',
      nip = '$_POST[nip]',
      filesoal = '$namaFileSoal',
      filekisi = '$namaFileKisi',
      tanggal_upload = '$tanggal',
      id_user = '$_SESSION[iduser]'");	

    if($save) echo "ok";
}

//Mengedit data
elseif($_GET['action'] == "update"){
   mysqli_query($mysqli, "UPDATE filesoal SET
      id_ujian = '$_POST[ujian]',
      nama_guru = '$_POST[nama_guru]',
      nip = '$_POST[nip]',
      filesoal = '$_POST[filesoal]',
      filekisi = '$_POST[filekisi]',
      id_user = '$_SESSION[iduser]'
      WHERE id_filesoal='$_POST[id]'");	
}

//Menghapus data
elseif($_GET['action'] == "delete"){
   $file = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM filesoal WHERE id_filesoal='$_GET[id]'"));
   if($file){
    if(file_exists('../filesoal/'.$file['filesoal'])) unlink('../filesoal/'.$file['filesoal']);
    if(file_exists('../filesoal/'.$file['filekisi'])) unlink('../filesoal/'.$file['filekisi']);
   }
   mysqli_query($mysqli, "DELETE FROM filesoal WHERE id_filesoal='$_GET[id]'");	
}
?>
