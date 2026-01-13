<?php
function hitungSisaWaktu($jam_mulai, $durasi_menit) {
   // waktu sekarang (server)
   $sekarang = date('H:i:s');

   // konversi ke timestamp
   $time_mulai = strtotime($jam_mulai);
   $time_sekarang = strtotime($sekarang);

   // hitung keterlambatan (detik)
   $terlambat = $time_sekarang - $time_mulai;
   if ($terlambat < 0) {
      $terlambat = 0; // ujian belum mulai
   }

   // total waktu ujian (detik)
   $total_detik = $durasi_menit * 60;

   // sisa waktu (detik)
   $sisa_detik = $total_detik - $terlambat;
   if ($sisa_detik < 0) {
      $sisa_detik = 0;
   }

   // konversi ke menit : detik
   $menit = floor($sisa_detik / 60);
   $detik = $sisa_detik % 60;

   // format 2 digit
   return sprintf('%02d:%02d', $menit, $detik);
}
?>