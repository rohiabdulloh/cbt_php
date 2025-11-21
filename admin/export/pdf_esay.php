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
	
$totalnilai = mysqli_fetch_row(mysqli_query($mysqli, "SELECT SUM(nilai) FROM jawaban WHERE id_ujian='$_GET[ujian]' AND nis='$_GET[nis]'"));
$query = mysqli_query($mysqli, "SELECT * FROM siswa t1
    LEFT JOIN kelas t2 ON t1.id_kelas=t2.id_kelas
    WHERE t1.nis='$_GET[nis]'");
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
    <tr><td height='40' align='center' style='font-size: 25'>".$totalnilai[0]."</td></tr>";

$qjawab = mysqli_query($mysqli, "SELECT * FROM jawaban t1
    LEFT JOIN soal t2 ON t1.id_soal=t2.id_soal
    WHERE t1.nis='$_GET[nis]' AND t1.id_ujian='$_GET[ujian]'");
$no = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM soal WHERE id_ujian='$_GET[ujian]' AND jenis='0'"));
while($j=mysqli_fetch_array($qjawab)){
    $no++;
    echo "<tr>
        <td colspan='3'>
            <table><tr>
            <td width='25' class='align-top' align='center'><p>$no.</p></td>
            <td width='675' class='align-top'>
                <i> $j[soal]</i>
                Jawaban: $j[jawaban] 
            </td>
            <td>
                $j[nilai]
            </td>
            </tr></table>
        </td></tr>";
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
