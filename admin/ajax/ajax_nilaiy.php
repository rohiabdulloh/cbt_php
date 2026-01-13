<?php
session_start();
include "../../library/config.php";

if($_GET['action'] == "table_data"){
   $query = mysqli_query($mysqli, "SELECT * FROM siswa WHERE id_kelas='$_GET[kelas]'");
   $data = array();
   $no = 1;
   $rkelas=mysqli_fetch_array(mysqli_query($mysqli,"SELECT * FROM kelas WHERE id_kelas='$_GET[kelas]'"));
   while($r = mysqli_fetch_array($query)){
      $n = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM nilai WHERE nis='$r[nis]' AND id_ujian='$_GET[ujian]'"));

      $nilaiesay = mysqli_fetch_row(mysqli_query(
            $mysqli,
            "SELECT SUM(jawaban.nilai) 
            FROM jawaban
            JOIN soal ON jawaban.id_soal = soal.id_soal
            WHERE jawaban.nis = '$r[nis]'
               AND jawaban.id_ujian = '$_GET[ujian]'
               AND soal.jenis = 1"
      ));
      
      $nilaikompleks = mysqli_fetch_row(mysqli_query(
         $mysqli,
         "SELECT SUM(jawaban.nilai) 
         FROM jawaban
         JOIN soal ON jawaban.id_soal = soal.id_soal
         WHERE jawaban.nis = '$r[nis]'
            AND jawaban.id_ujian = '$_GET[ujian]'
            AND soal.jenis = 2"
      ));

      
      $nilaimencocokkan = mysqli_fetch_row(mysqli_query(
         $mysqli,
         "SELECT SUM(jawaban.nilai) 
         FROM jawaban
         JOIN soal ON jawaban.id_soal = soal.id_soal
         WHERE jawaban.nis = '$r[nis]'
            AND jawaban.id_ujian = '$_GET[ujian]'
            AND soal.jenis = 3"
      ));

      $nilaipg = $n['nilai'] - $nilaikompleks[0] - $nilaimencocokkan[0];

      $row = array();
      $row[] = $no;
      $row[] = $r['nis'];
      $row[] = $r['nama'];
	   $row[] = $rkelas['kelas'];
      if($n){	
         $row[] = $nilaipg;
         $row[] = $nilaikompleks[0];
         $row[] = $nilaimencocokkan[0];
         $row[] = $nilaiesay[0];
         $row[] = $n['nilai'] + $nilaiesay[0];
      }else{		
         $row[] = '';
         $row[] = '';
         $row[] = '';
         $row[] = '';
         $row[] = '';
      }
         $aksi = '<div style="display: flex">';         
         if($nilaiesay[0]!=null){
            $aksi .= '<a class="btn btn-primary" onclick="koreksi_jawaban('.$r['nis'].')">
                     <i class="fa fa-check"></i>
                  </a>';  
         }             
            $aksi .= '  <a class="btn btn-info" style="margin-left: 5px;" onclick="detail_jawaban('.$r['nis'].')">
                           <i class="fa fa-list"></i>
                        </a>
                        <a class="btn btn-success" style="margin-left: 5px;" onclick="download_jawaban('.$r['nis'].')">
                           <i class="fa fa-download"></i>
                        </a>';
            if($n){
               $aksi .= '<a class="btn btn-danger" style="margin-left: 5px;" onclick="reset_nilai('.$r['nis'].')">
                           <i class="fa fa-close"></i>
                        </a>';
            }
            $aksi .= '     </div> ';
         $row[] = $aksi;
     
	   $no++;
      $data[] = $row;
   }
   $output = array("data" => $data);
   echo json_encode($output);
}


elseif($_GET['action'] == "form_data"){
   $nis = $_GET['nis'];
   $idujian = $_GET['ujian'];
   
   $query = mysqli_query($mysqli, "SELECT * FROM jawaban t1
      LEFT JOIN soal t2 
      ON t1.id_soal=t2.id_soal 
      WHERE t1.id_ujian='$idujian' AND t1.nis='$nis'");
   $table = '<table class="table table-bordered">';
   $no = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$idujian' AND jenis='0'"));
   while($rj = mysqli_fetch_array($query)){
      $no++;
      $table .= '<tr>
         <td width="20">'.$no.'.</td>
         <td><i>'.$rj['soal'].'</i>
            Jawaban: '.$rj['jawaban'].'
         </td>
         <td style="width: 100px;">
            <input type="text" class="form-control" name="nilai_'.$rj['id_soal'].'" value="'.$rj['nilai'].'">
         </td>
      </tr>';
   }
   $table .= '</table>';
   $data = ['nis'=>$nis, 'table'=>$table];	
   echo json_encode($data);
}

elseif($_GET['action'] == "update"){
   $idujian = $_POST['ujian'];
   $nis = $_POST['nis'];
   $query = mysqli_query($mysqli, "SELECT * FROM jawaban WHERE id_ujian='$idujian' AND nis='$nis'");
   
   while($rj = mysqli_fetch_array($query)){
      $key = 'nilai_'.$rj['id_soal'];
      $nilai = $_POST[$key];
      mysqli_query($mysqli, "UPDATE jawaban set nilai='$nilai' WHERE 
         id_ujian='$idujian' AND
         nis='$nis' AND
         id_soal='$rj[id_soal]'");
   }
}


elseif($_GET['action'] == "reset"){
   $idujian = $_GET['ujian'];
   $nis = $_GET['nis'];
   mysqli_query($mysqli, "UPDATE nilai SET nilai=0 WHERE id_ujian='$idujian' AND nis='$nis'");
}


elseif($_GET['action'] == "reset_all"){
   $idujian = $_GET['ujian'];
   $kelas = $_GET['kls'];
   $query = mysqli_query($mysqli, "SELECT * FROM siswa WHERE id_kelas='$kelas'");
   
   while($s = mysqli_fetch_array($query)){
      mysqli_query($mysqli, "UPDATE nilai SET nilai=0 WHERE id_ujian='$idujian' AND nis='$s[nis]'");
   }
}
?>
