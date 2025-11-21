<?php
session_start();
include "../../library/config.php";
include "../../library/function_view.php";


function simpanData($mode, $mysqli){
   $jenis = addslashes($_POST['jenis']);
   $soal = addslashes($_POST['soal']);

   if($_POST['parameter']) $parameter = addslashes($_POST['parameter']);
   else $parameter = "";

   if($jenis==3){
      $params = explode(",", $parameter);
      $kunci = [];
      for($i=1; $i<=5; $i++){
         $field= "kunci_pernyataan_".($i);
         if(!empty($_POST[$field])) $kunci[] = addslashes($_POST[$field]);
      }

      $kunci = implode(",", $kunci);
      
      $pil_1 = addslashes($_POST['pernyataan_1']);
      $pil_2 = addslashes($_POST['pernyataan_2']);
      $pil_3 = addslashes($_POST['pernyataan_3']);
      $pil_4 = addslashes($_POST['pernyataan_4']);
      $pil_5 = addslashes($_POST['pernyataan_5']);
   }else{
      $pil_1 = addslashes($_POST['pil_1']);
      $pil_2 = addslashes($_POST['pil_2']);
      $pil_3 = addslashes($_POST['pil_3']);
      $pil_4 = addslashes($_POST['pil_4']);
      $pil_5 = addslashes($_POST['pil_5']);

      if($jenis==2){
         $kunci = implode(",", $_POST['kunci_kompleks']);
      }
      else $kunci = isset($_POST['kunci']) ? $_POST['kunci'] : '';
   }

   $bobot = isset($_POST['bobot']) ? str_replace(',','.',$_POST['bobot']) : 0;

   if($mode=='insert'){         
      mysqli_query($mysqli, "INSERT INTO soal SET 
         id_ujian = '$_GET[ujian]',
         jenis = '$jenis',
         soal = '$soal',
         pilihan_1 = '$pil_1',
         pilihan_2 = '$pil_2',
         pilihan_3 = '$pil_3',
         pilihan_4 = '$pil_4',
         pilihan_5 = '$pil_5',
         parameter = '$parameter',
         kunci = '$kunci',
      bobot = '$bobot'");	
   }else{         
      mysqli_query($mysqli, "UPDATE soal SET 
         jenis = '$jenis',
         soal = '$soal',
         pilihan_1 = '$pil_1',
         pilihan_2 = '$pil_2',
         pilihan_3 = '$pil_3',
         pilihan_4 = '$pil_4',
         pilihan_5 = '$pil_5',
         parameter = '$parameter',
         kunci = '$kunci',
      bobot = '$bobot' WHERE id_soal='$_POST[id]'");
   }
   
   $totalbobot = mysqli_fetch_row(mysqli_query($mysqli, "SELECT SUM(bobot) FROM soal WHERE id_ujian='$_GET[ujian]'"));
   $totalsoal = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$_GET[ujian]'"));

   $data = [
      "bobot" => (int) $totalbobot[0] ?? 0,
      "soal"  => (int) $totalsoal
   ];
  
   echo json_encode($data);
}

//Menampilkan data ke tabel
if($_GET['action'] == "table_data"){
   $query = mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$_GET[ujian]' ORDER BY id_soal");
   $data = array();
   $no = 1;
   while($r = mysqli_fetch_array($query)){
      $soal = $r['soal'];
      $bobot = $r['bobot'];
      if($r['jenis'] == 0){
         $soal .= '<ol type="A">';		
         for($i=1; $i<=5; $i++){	
            $kolom = "pilihan_$i";
            if($r['kunci']==$i) $soal .= '<li class="text-primary" style="font-weight: bold">'.$r[$kolom].'</li>';
            else $soal .= '<li>'.$r[$kolom].'</li>';
         }
         $soal .= '</ol>';
      }else if($r['jenis'] == 2){
         $soal .= '<ul type="square">';		
         $kunciArray = explode(',', $r['kunci']);  
         for($i=1; $i<=5; $i++){	
            $kolom = "pilihan_$i";
            if (in_array($i, $kunciArray)) {
               $soal .= '<li class="text-primary" style="font-weight: bold">'.$r[$kolom].'</li>';
            } else {
                $soal .= '<li>'.$r[$kolom].'</li>';
            }
         }
         $soal .= '</ul>';
      }
      else if($r['jenis'] == 3){
         $soal .= '<table class="table table-bordered"><tr><td>Pernyataan</td>';	
         $parameter = array_map('trim', explode(',', $r['parameter'])); 	
         $kunciArray = array_map('trim', explode(',', $r['kunci'])); 
         for($i=0; $i<count($parameter); $i++){
            $soal .= '<td class="text-center">'.$parameter[$i].'</td>';
         }
         $soal .= '</tr>';
         for($i=1; $i<=5; $i++){	
            $kolom = "pilihan_$i";
            if (!empty($r[$kolom])) {             
               $soal .= '<tr><td>' . $r[$kolom] . '</td>';
               for($j=0; $j<count($parameter); $j++){
                  $kunci = isset($kunciArray[$i-1]) ? $kunciArray[$i-1] : '';
                  if($kunci == $j+1){
                     $soal .= '<td class="text-center text-primary" style="font-weight: bold">&#10003;</td>';
                  }else{
                     $soal .= '<td class="text-center">-</td>';
                  }
               }               
               $soal .= '</tr>';
            }
         }
         $soal .= '</table>';
      }
      $row = array();
      $row[] = $no;
      $row[] = $soal;
      $row[] = $bobot;
      $row[] = create_action($r['id_soal']);
      $data[] = $row;
      $no++;
   }	
   $output = array("data" => $data);
   echo json_encode($output);
}

//Menampilkan data ke form edit
elseif($_GET['action'] == "form_data"){
   $query = mysqli_query($mysqli, "SELECT * FROM soal WHERE id_soal='$_GET[id]'");
   $data = mysqli_fetch_array($query);	
   echo json_encode($data);
}

//Menambahkan data soal ke database
elseif($_GET['action'] == "insert"){
       
   simpanData('insert', $mysqli);
   
}

//Mengedit data soal pada database
elseif($_GET['action'] == "update"){
   
   simpanData('update', $mysqli);
      
}

//Menghapus data
elseif($_GET['action'] == "delete"){
   mysqli_query($mysqli, "DELETE FROM soal WHERE id_soal='$_GET[id]'");	
   
   
   $totalbobot = mysqli_fetch_row(mysqli_query($mysqli, "SELECT SUM(bobot) FROM soal WHERE id_ujian='$_GET[ujian]'"));
   $totalsoal = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$_GET[ujian]'"));

   $data = [
      "bobot" => (int) $totalbobot[0] ?? 0,
      "soal"  => (int) $totalsoal
   ];
  
   echo json_encode($data);
}

//Import data dari format Excel
elseif($_GET['action'] == "import"){
   include "../../assets/excel_reader/excel_reader.php";
   $filename = strtolower($_FILES['file']['name']);
   $extensi  = substr($filename,-4);
		
   if($extensi != ".xls"){
      echo "File yang di-upload tidak berformat .xls!'";
   }else{
      $path = "../upload";			
      move_uploaded_file($_FILES['file']['tmp_name'], "$path/$filename");
			
      $file = "../upload/$filename";
			
      $data = new Spreadsheet_Excel_Reader();
      $data->read($file);
      $jdata = $data->rowcount($sheet_index=0);
			
     for($i=2; $i<=$jdata; $i++){		
       $soal = htmlspecialchars(addslashes($data->val($i,2)));
       $pil_1 = htmlspecialchars(addslashes($data->val($i,3)));
       $pil_2 = htmlspecialchars(addslashes($data->val($i,4)));
       $pil_3 = htmlspecialchars(addslashes($data->val($i,5)));
       $pil_4 = htmlspecialchars(addslashes($data->val($i,6)));
       $pil_5 = htmlspecialchars(addslashes($data->val($i,7)));
       $kunci = addslashes($data->val($i,8));
	    $bobot = str_replace(",",".",$data->val($i,9));
	    $jenis = addslashes($data->val($i,10));
	    $parameter = addslashes($data->val($i,11));
				
       mysqli_query($mysqli, "INSERT INTO soal SET 
         id_ujian = '$_GET[ujian]',
         soal = '$soal',
         pilihan_1 = '$pil_1',
         pilihan_2 = '$pil_2',
         pilihan_3 = '$pil_3',
         pilihan_4 = '$pil_4',
         pilihan_5 = '$pil_5',
         kunci = '$kunci',
		   bobot = '$bobot',
         jenis = '$jenis',
         parameter = '$parameter'
      ");	
     }	
    
     unlink($file);
     
      $totalbobot = mysqli_fetch_row(mysqli_query($mysqli, "SELECT SUM(bobot) FROM soal WHERE id_ujian='$_GET[ujian]'"));
      $totalsoal = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$_GET[ujian]'"));

      $data = [
         "bobot" => (int) $totalbobot[0] ?? 0,
         "soal"  => (int) $totalsoal
      ];
   
      echo json_encode($data);
   }

}
?>