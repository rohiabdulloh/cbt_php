<?php
session_start();
ob_start();
?>
<html>
<head>
   <style type="text/css">
      table{
        border-collapse: collapse;
      }
      .table-border > td{
        border: 1px solid #000;
      }
      .align-top{
        vertical-align: top;
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
                <tr><td width='60'>No. Ujian</td><td width='230'>: $siswa[nis]</td></tr>
                <tr><td>Nama</td><td>: $siswa[nama]</td></tr>
                <tr><td>Kelas</td><td>: $siswa[kelas]</td></tr>
            </table>
        </td>
        <td rowspan='2' align='center'>
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
    $rsoal = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM soal WHERE id_soal='$arr_soal[$s]'"));
    if($rsoal){
        if($rsoal['jenis'] == 0){
            echo "<tr>
                <td colspan='3'>
                    <table>
                    <tr>
                        <td width='25' class='align-top' align='center'><p>$no.</p></td>
                        <td width='675' class='align-top' colspan='2'><i> $rsoal[soal]</i></td>
                    </tr>";
            $arr_huruf = [1=>'A', 'B', 'C', 'D', 'E'];
            for($i=1; $i<=5; $i++){	
                $kolom = "pilihan_$i";
                if($i==$rsoal['kunci']){
                    $warna = "blue";
                    $tebal = "bold";
                    if($arr_jawaban[$s]==$i) $warna = "green";
                }else{
                    $warna = "black";
                    $tebal = "normal";
                    if($i==$arr_jawaban[$s]){
                        $warna = "red";
                        $tebal = "bold";
                    }
                }
                
                echo "<tr style='color: $warna; font-weight: $tebal;'>
                    <td></td>
                    <td width='25'><p>$arr_huruf[$i].</p></td>
                    <td width='650'>$rsoal[$kolom]</td>
                </tr>";
            }
            echo "</table></td></tr>";
        }
    }
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
