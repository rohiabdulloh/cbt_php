<?php
session_start();
ob_start();
?>
<html>
<head>
   <style type="text/css">
      .table-border > td{
        border: 1px solid #000;
      }
      .align-top{
        vertical-align: top;
      }
      .text-primary{ 
        font-weight:bold;
        color:blue;
      }
      p{margin-top: 0};
      .kotak{
          display:block; width:10px; height:10px; background:#000;border: 1px solid #000; 
      }
   </style>
</head>
<body>

<?php
   
include "../../library/config.php";
	
$query = mysqli_query($mysqli, "SELECT * FROM siswa t1
    LEFT JOIN kelas t2 ON t1.id_kelas=t2.id_kelas
    WHERE t1.nis='$_GET[nis]'");

$rnilai = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM nilai WHERE id_ujian='$_GET[ujian]' AND nis='$_GET[nis]'"));
$nilai = $rnilai ? $rnilai['nilai'] : 0;
$siswa = mysqli_fetch_array($query);
echo "<table class='table-border' cellspacing='0' cellpadding='3' border='1'>";
echo "<tr>
        <td rowspan='2'>
            <table border='0'>
                <tr><td width='60'>No. Ujian</td><td width='200'>: $siswa[nis]</td></tr>
                <tr><td>Nama</td><td>: $siswa[nama]</td></tr>
                <tr><td>Kelas</td><td>: $siswa[kelas]</td></tr>
            </table>
        </td>
        <td widt='500' rowspan='2' align='center'>
            <table width='100%'>
                <tr>
                    <td align='right' width='35'>
                    <img src='../../images/logo.png' width='30'>
                    </td>
                
                    <td align='center' width=270''>
                    <img src='../../images/logo8.png' width='250'>
                    </td>
                
                    <td align='left' width='45'>
                    <img src='../../images/logo1.png' width='40'>
                    </td>
                </tr>
            </table>
        </td>
        <td width='80' height='20' align='center'>
            NILAI
        </td>
    </tr>
    <tr><td height='40' align='center' style='font-size: 25'>".$nilai."</td></tr>";


$arr_soal = explode(",", $rnilai['acak_soal']);
$arr_jawaban = explode(",", $rnilai['jawaban']);
for($s=0; $s<count($arr_soal); $s++){
    $no = $s + 1;
    $idSoal = $arr_soal[$s];
    $jawabanSiswa = $arr_jawaban[$s];

    $rsoal = mysqli_fetch_array(mysqli_query(
        $mysqli,
        "SELECT * FROM soal WHERE id_soal='$idSoal'"
    ));

    if(!$rsoal) continue;

    echo "<tr><td colspan='3'><table width='100%'>";

    // NOMOR DAN SOAL
    echo "<tr>
            <td width='25' valign='top'><b>$no.</b></td>
            <td width='675' valign='top'><i>$rsoal[soal]</i></td>
         </tr>";

    // ===========================
    //   TIPE 0 – PILIHAN GANDA
    // ===========================
    if($rsoal['jenis'] == 0){

        echo "<tr><td></td><td>";
    
        $arrHuruf = ['A','B','C','D','E'];
    
        echo "<table cellpadding='2' cellspacing='0'>";
    
        for($i=1; $i<=5; $i++){
            $kolom = "pilihan_$i";
            if($rsoal[$kolom] == "") continue;
    
            // pewarnaan
            $style = "";
            if($rsoal['kunci'] == $i){
                $style = "style='color:blue;font-weight:bold'";
            }
            if($jawabanSiswa == $i && $jawabanSiswa != $rsoal['kunci']){
                $style = "style='color:red;font-weight:bold'";
            }
            if($jawabanSiswa == $i && $jawabanSiswa == $rsoal['kunci']){
                $style = "style='color:green;font-weight:bold'";
            }
    
            echo "
                <tr>
                   <td valign='top' width='20'>".$arrHuruf[$i-1].".</td>
                   <td valign='top' width='650' $style>".$rsoal[$kolom]."</td>
                </tr>";
        }
    
        echo "</table></td></tr>";
    }

    // ===========================
    //   TIPE 2 – MULTI KUNCI
    // ===========================
    else if($rsoal['jenis'] == 2){

        $kunciArray = explode(',', $rsoal['kunci']);
    
        echo "<tr><td></td><td>";
        echo "<table cellpadding='2' cellspacing='0'>";
    
        for($i=1; $i<=5; $i++){
            $kolom = "pilihan_$i";
            if($rsoal[$kolom] == "") continue;
    
            $style = in_array($i, $kunciArray)
                ? "style='color:blue;font-weight:bold'"
                : "";
    
            echo "
                <tr>
                    <td valign='top' width='20'><div style='display:block; width:8px; height:8px; border:1px solid #000;'></div></td>
                    <td valign='top' width='650' $style>".$rsoal[$kolom]."</td>
                </tr>";
        }
    
        echo "</table></td></tr>";
    }

    // ===========================
    //   TIPE 3 – MENCOCOKAN
    // ===========================
    else if($rsoal['jenis'] == 3){
        $parameter = array_map('trim', explode(',', $rsoal['parameter']));
        $kunciArray = array_map('trim', explode(',', $rsoal['kunci']));

        echo "<tr><td></td><td>
                <table border='1' cellspacing='0' cellpadding='3'>
                    <tr>
                        <td>Pernyataan</td>";

        // Header parameter
        foreach($parameter as $p){
            echo "<td align='center'>$p</td>";
        }

        echo "</tr>";

        // Baris opsi
        for($i=1; $i<=5; $i++){
            $kolom = "pilihan_$i";
            if($rsoal[$kolom] == "") continue;

            echo "<tr><td width='300'>".$rsoal[$kolom]."</td>";

            for($j=0; $j<count($parameter); $j++){
                $isKunci = (isset($kunciArray[$i-1]) && $kunciArray[$i-1] == $j+1);

                if($isKunci){
                    echo "<td align='center' style='font-weight:bold;color:blue'>v</td>";
                } else {
                    echo "<td align='center'>-</td>";
                }
            }

            echo "</tr>";
        }

        echo "</table></td></tr>";
    }

    // ===========================
    //   TIPE 1 – ESAI
    // ===========================
    else if($rsoal['jenis'] == 1){
        echo "<tr><td></td><td><b>Jawaban:</b><br>";

        // ambil jawaban esai siswa
        $jawabEsai = mysqli_fetch_array(mysqli_query(
            $mysqli,
            "SELECT jawaban FROM jawaban WHERE id_soal='$idSoal' AND nis='$_GET[nis]'"
        ));

        echo "<div style='margin-top:5px;'>".nl2br($jawabEsai['jawaban'])."</div>";
        echo "</td></tr>";
    }

    echo "</table></td></tr>";
}

echo "</table>";
?>
</body>
</html>

<?php
require_once '../../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$content = ob_get_clean();
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('Jawaban Soal Esay.pdf');
?>
