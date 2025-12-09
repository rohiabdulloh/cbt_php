<?php
session_start();
ob_start();
?>
<html>
<head>
   <style type="text/css">
      .box{
         border: 1px solid #000;
      }
      .header td{
         border-bottom: 1px solid #000;
      }
      .box td{
         padding: 1px 5px;
      }
   </style>
</head>
<body>

<?php
   
include "../../library/config.php";
	
$query = mysqli_query($mysqli, "select * from siswa where id_kelas='$_GET[kelas]'");
$no = 1;
echo "<table width='20%' cellspacing='5'><tr>";
while($r = mysqli_fetch_array($query)){
	 $nama = substr($r['nama'], 0, 30);
   $password = substr(md5($r['nis']), 0, 5);
   $kls = mysqli_fetch_array(mysqli_query($mysqli, "select * from kelas where id_kelas='$r[id_kelas]'"));	
   echo"<td class='box' width='150'>

<table width='50%' style='width: 450px' cellspacing='0'>
 
<tr class='header'>
   <td width='60' align='center'>
      <img src='../../images/logo.png' width='30'>
   </td>
 
   <td width='60' align='center'>
      <img src='../../images/logo8.png' width='200'>
   </td>

   <td width='40' align='center'>
      <img src='../../images/logo1.png' width='40'>
   </td>
</tr>

<tr class='bBottom'>
   <td width='60' align='center'>
      
   </td>
 
   <td width='60' align='center'>
      <img src='../../images/logo9.png' width='190'>
   </td>

   <td width='40' align='center'>
      
   </td>
</tr>

</table>

<table width='100%' cellspacing='0'>
<tr><td width='25%'>Nama</td><td>: $nama</td></tr>
<tr><td>Kelas</td><td>: $kls[kelas]</td></tr>
<tr><td>Ruang</td><td>: $r[no_ruang]</td></tr>
</table>

<table width='50%' style='width: 350px' cellspacing='0'>
 
<tr class='bBottom'>
   <td width='60' align='center'>
     <img src='../../images/barcode.png' width='65'>
   </td>
 
   <td width='60' align='center'>
     
   </td>

   <td width='0' align='center'>
      <img src='../../images/ttd.png' width='150'>
   </td>
</tr>
</table>





</td>";

  if($no%2==0) echo "</tr><tr>";
  $no++;

}
echo "</tr></table>";
?>
</body>
</html>

<?php
require_once '../../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$content = ob_get_clean();
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('Kartu Peserta.pdf');
?>
