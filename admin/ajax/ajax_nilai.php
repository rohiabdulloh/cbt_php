<?php
session_start();
include "../../library/config.php";

function hitung_nilai($mysqli, $nis, $id_ujian) {
    // Escape input (menggunakan _esc di semua query)
    $id_ujian_esc = mysqli_real_escape_string($mysqli, $id_ujian);
    $nis_esc = mysqli_real_escape_string($mysqli, $nis);
    
    // Inisialisasi variabel (Hanya menggunakan yang dipakai di loop)
    $jbenar = 0; // Total jawaban benar (PG murni + yang full score di non-PG)
    $nilai = 0;  // Total akumulasi nilai

    // A. Ambil data Nilai Awal (acak_soal, jawaban PG)
    $q_nilai = mysqli_query($mysqli, "SELECT acak_soal, jawaban FROM nilai WHERE id_ujian='$id_ujian_esc' AND nis='$nis_esc'");
    $rnilai = mysqli_fetch_array($q_nilai);

    // --- PERBAIKAN 1: CEK NULL $rnilai ---
    if (!$rnilai) {
        return false; // Keluar jika data nilai tidak ditemukan
    }

    $arr_soal = explode(",", $rnilai['acak_soal']);
    $jawaban_user_str = explode(",", $rnilai['jawaban']);
    
    // Konversi array ID soal menjadi string berformat: 'id1','id2','id3' untuk klausa IN
    $arr_soal_escaped = array_map(function($id) use ($mysqli) {
        return "'" . mysqli_real_escape_string($mysqli, $id) . "'";
    }, $arr_soal);
    $soal_list_in = implode(',', $arr_soal_escaped);


    // 2. AMBIL SEMUA DATA SOAL SEKALIGUS (SEBELUM LOOP)
    // --- PERBAIKAN 3: Gunakan $id_ujian_esc ---
    $q_soal = mysqli_query($mysqli, "SELECT id_soal, jenis, kunci, bobot FROM soal 
        WHERE id_soal IN ($soal_list_in) AND id_ujian='$id_ujian_esc'");
    
    $soal_data = [];
    while ($rsoal = mysqli_fetch_array($q_soal)) {
        $soal_data[$rsoal['id_soal']] = $rsoal;
    }


    // 3. AMBIL SEMUA DATA JAWABAN TAMBAHAN (PG Kompleks & Isian) SEKALIGUS (SEBELUM LOOP)
    // --- PERBAIKAN 3: Gunakan $id_ujian_esc dan $nis_esc ---
    $q_jawaban = mysqli_query($mysqli, "SELECT id_soal, jawaban FROM jawaban 
        WHERE id_soal IN ($soal_list_in) AND id_ujian='$id_ujian_esc' AND nis='$nis_esc'");
    
    $jawaban_data = [];
    while ($rjawaban = mysqli_fetch_array($q_jawaban)) {
        $jawaban_data[$rjawaban['id_soal']] = $rjawaban;
    }


    // --- MEMULAI LOOP (TANPA QUERY SELECT) ---
    for($i = 0; $i < count($arr_soal); $i++){
        $current_id_soal = $arr_soal[$i];
        
        $rsoal = isset($soal_data[$current_id_soal]) ? $soal_data[$current_id_soal] : null; 

        if ($rsoal) {
            $current_jawaban_user = isset($jawaban_user_str[$i]) ? $jawaban_user_str[$i] : null;

            if ($rsoal['jenis'] == 0) { // Pilihan Ganda Murni
                if ($rsoal['kunci'] == $current_jawaban_user) {
                    $jbenar++;
                    $nilai = $nilai + $rsoal['bobot'];
                }

            } elseif ($rsoal['jenis'] == 2) { // Pilihan Ganda Kompleks
                
                $cekjawaban = isset($jawaban_data[$current_id_soal]) ? $jawaban_data[$current_id_soal] : null;
                
                if ($cekjawaban) {
                    $kunci_arr = array_map('trim', explode(',', $rsoal['kunci']));
                    $jawaban_arr = array_map('trim', explode(',', $cekjawaban['jawaban']));
                    sort($kunci_arr);
                    sort($jawaban_arr);
                    
                    $nilaijawaban = 0;
                    if ($kunci_arr == $jawaban_arr) {
                        $jbenar++;
                        $nilai = $nilai + $rsoal['bobot'];
                        $nilaijawaban = $rsoal['bobot'];
                    }
                    
                    // UPDATE Jawaban Jenis 2 
                    $current_id_soal_esc = mysqli_real_escape_string($mysqli, $current_id_soal);
                    mysqli_query($mysqli, "UPDATE jawaban SET nilai='$nilaijawaban' WHERE 
                        id_ujian='$id_ujian_esc' AND 
                        nis='$nis_esc' AND 
                        id_soal='$current_id_soal_esc'");
                }

            } elseif ($rsoal['jenis'] == 3) { // Isian Singkat
                
                $cekjawaban = isset($jawaban_data[$current_id_soal]) ? $jawaban_data[$current_id_soal] : null;

                if ($cekjawaban) {
                    $kunci = explode(',', str_replace(' ', '', $rsoal['kunci']));
                    $jawaban_isian = explode(',', str_replace(' ', '', $cekjawaban['jawaban']));

                    $minCount = min(count($kunci), count($jawaban_isian));
                    $benar = 0;
                    for ($j = 0; $j < $minCount; $j++) {
                        if ($kunci[$j] == $jawaban_isian[$j]) {
                            $benar++;
                        }
                    }

                    if($benar > 0){
                        $total_kunci = count($kunci);
                        $persentase_benar = $benar / $total_kunci;
                        $nilai_tambah = $rsoal['bobot'] * $persentase_benar;

                        $nilai += $nilai_tambah;
                        
                        // UPDATE Jawaban Jenis 3
                        $current_id_soal_esc = mysqli_real_escape_string($mysqli, $current_id_soal);
                        mysqli_query($mysqli, "UPDATE jawaban SET nilai='$nilai_tambah' WHERE 
                            id_ujian='$id_ujian_esc' AND 
                            nis='$nis_esc' AND 
                            id_soal='$current_id_soal_esc'");
                        
                        if($benar == $total_kunci){
                            $jbenar++;
                        }
                    } else {
                        // Jika tidak ada yang benar, pastikan nilai jawaban di DB adalah 0
                        $current_id_soal_esc = mysqli_real_escape_string($mysqli, $current_id_soal);
                        mysqli_query($mysqli, "UPDATE jawaban SET nilai='0' WHERE 
                            id_ujian='$id_ujian_esc' AND 
                            nis='$nis_esc' AND 
                            id_soal='$current_id_soal_esc'");
                    }
                }
            }
        } 
    }
    
    // F. Update Nilai Akhir di tabel 'nilai'
    mysqli_query($mysqli, "UPDATE nilai SET jml_benar='$jbenar', nilai='$nilai' WHERE id_ujian='$id_ujian_esc' AND nis='$nis_esc'");
    
    return true; 
}

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

      $n_mencocokkan = $nilaimencocokkan[0] ? $nilaimencocokkan[0] : 0;
      $n_kompleks = $nilaikompleks[0] ? $nilaikompleks[0] : 0;
      $n_esay = $nilaiesay[0] ? $nilaiesay[0] : 0;
      $nilaipg = $n ? $n['nilai'] - $n_kompleks - $n_mencocokkan : 0;

      $row = array();
      $row[] = $no;
      $row[] = $r['nis'];
      $row[] = $r['nama'];
	   $row[] = $rkelas['kelas'];
      if($n){	
         $row[] = $nilaipg;
         $row[] = $n_kompleks;
         $row[] = $n_mencocokkan;
         $row[] = $n_esay;
         $row[] = $n['nilai'] + $n_esay;
      }else{		
         $row[] = '';
         $row[] = '';
         $row[] = '';
         $row[] = '';
         $row[] = '';
      }
         $aksi = '<div style="display: flex">';         
         if($n_esay!=null){
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
      WHERE t1.id_ujian='$idujian' AND t1.nis='$nis' AND t2.jenis='1'");
   $table = '<table class="table table-bordered">';
   $no = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$idujian' AND jenis!='1'"));
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
   mysqli_query($mysqli, "UPDATE jawaban SET nilai=0 WHERE id_ujian='$idujian' AND nis='$nis'");
}


elseif($_GET['action'] == "reset_all"){
   $idujian = $_GET['ujian'];
   $kelas = $_GET['kls'];
   $query = mysqli_query($mysqli, "SELECT * FROM siswa WHERE id_kelas='$kelas'");
   
   while($s = mysqli_fetch_array($query)){
      mysqli_query($mysqli, "UPDATE nilai SET nilai=0 WHERE id_ujian='$idujian' AND nis='$s[nis]'");
      mysqli_query($mysqli, "UPDATE jawaban SET nilai=0 WHERE id_ujian='$idujian' AND nis='$s[nis]'");
   }
}


elseif($_GET['action'] == "update_nilai"){
    $idujian = mysqli_real_escape_string($mysqli, $_GET['ujian']); 
    $idkelas = mysqli_real_escape_string($mysqli, $_GET['kls']);
    
    $jumlah_siswa_diproses = 0;

    // 1. Ambil semua NIS siswa yang ada di kelas ini
    $q_siswa = mysqli_query($mysqli, "SELECT nis FROM siswa WHERE id_kelas='$idkelas'");
    
    if (mysqli_num_rows($q_siswa) > 0) {
        while ($r_siswa = mysqli_fetch_array($q_siswa)) {
            $nis_siswa = $r_siswa['nis'];
            
            // 2. Panggil fungsi perhitungan ulang untuk setiap siswa
            $berhasil = hitung_nilai($mysqli, $nis_siswa, $idujian);
            
            if ($berhasil) {
                $jumlah_siswa_diproses++;
            }
        }
        
        echo json_encode([
            'success' => true, 
            'message' => "Perhitungan ulang nilai selesai untuk $jumlah_siswa_diproses siswa di kelas ini."
        ]);
        
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Tidak ada siswa ditemukan di kelas ini.'
        ]);
    }
}
?>
