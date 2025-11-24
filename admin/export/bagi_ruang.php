<?php
session_start();
ob_start();

include "../../library/config.php";
?>

<html>
<head>
   <style type="text/css">
      body {
         font-family: Arial, sans-serif;
         font-size: 12px;
      }
      h3 {
         margin-bottom: 5px;
      }
      table {
         border-collapse: collapse;
         width: 190mm;
         margin-top: 10px;
      }
      th, td {
         border: 1px solid #000;
         padding: 5px;
         text-align: left;
      }
      .page-break {
         page-break-after: always;
      }
   </style>
</head>
<body>

<?php
$query_ruang = mysqli_query($mysqli, "SELECT * FROM bagi_ruang");
$batas = 0;

while ($r = mysqli_fetch_array($query_ruang)) {
    // Ambil data siswa per ruang
    $query_siswa = mysqli_query(
        $mysqli,
        "SELECT * FROM siswa LEFT JOIN kelas ON siswa.id_kelas=kelas.id_kelas ORDER BY siswa.id_kelas, siswa.nama LIMIT $batas, $r[jml_siswa]"
    );
?>

   <!-- Bagian 1 ruang (1 halaman) -->
   <div class="page">
      <h3 align="center">DATA PESERTA UJIAN</h3>
      <h3 align="center">RUANG <?php echo $r['ruang']; ?></h3>
      <table>
         <tr>
            <th style="width: 10mm; text-align: center;">No</th>
            <th style="width: 25mm; text-align: center;">NIS</th>
            <th style="width: 100mm; text-align: center;">Nama</th>
            <th style="width: 40mm; text-align: center;">Kelas</th>
         </tr>
         <?php
         $no = 1;
         while ($s = mysqli_fetch_array($query_siswa)) {
            echo "<tr>
               <td align='center'>$no</td>
               <td>{$s['nis']}</td>
               <td>{$s['nama']}</td>
               <td>{$s['kelas']}</td>
            </tr>";
            $no++;
         }
         ?>
      </table>
   </div>

   <!-- Pisahkan halaman -->
   <div class="page-break"></div>

<?php
    $batas += $r['jml_siswa'];
}
?>

</body>
</html>

<?php
require_once '../../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$content = ob_get_clean();
$html2pdf = new Html2Pdf('P', 'A4', 'en');
$html2pdf->writeHTML($content);
$html2pdf->output('Daftar_Siswa_Per_Ruang.pdf');
?>
