var menit, detik;
var ujian, sisa_waktu;
var t, jamskr, menitskr, detikskr;
var mulai, selesai;
var counterUpdate;

//Mengatur agar waktu ujian berjalan mundur
$(function(){		
   $('.btn-end').hide();
   $('.btn-time').show();

   setInterval(function(){
      //Skrip menggunakan sisa waktu

	   menit = parseInt($('.menit').text());	
      detik = parseInt($('.detik').text());
	  
      detik--;
      if(detik<0 && menit>0){
         menit--;
         detik = 59;
      }

      if(menit<=0) menit = 0;
      if(menit==0 && detik<=0) detik = 0;
      if(menit<10) menit = "0"+menit;
      if(detik<10) detik = "0"+detik;

      $('.menit').text(menit);
	   $('.detik').text(detik);
		
      $('#sisa_waktu').val(menit+':'+detik);
		
      //Skrip menggunakan jam sekarang
      /*t = new Date();
      jamskr = t.getHours();
      menitskr = t.getMinutes();
      detikskr = t.getSeconds();
      
      if(jamskr<10) jamskr = "0"+jamskr;
      if(menitskr<10) menitskr = "0"+menitskr;
      if(detikskr<10) detikskr = "0"+detikskr;
      $('.jam').text(jamskr);
      $('.menit').text(menitskr);
      $('.detik').text(detikskr);
      */


      //Menghitung minimal waktu
      
      waktu = parseInt($('#waktu').val());
      minwaktu = parseInt($('#minwaktu').val());
      
      var menitTerpakai = waktu - menit;

      if (menitTerpakai > minwaktu) {
         $('.btn-time').hide();
         $('.btn-end').show();
      }
      $('.btn-time').text('(Tunggu ' + (minwaktu - menitTerpakai) + ' menit lagi)');
      

      //Mendeteksi waktu habis
      if(menit == "00" && detik == "00"){
         selesaikan();
         $('#modal-selesai .modal-title').text("Waktu Habis!");
         $('#modal-selesai .modal-body').text("Waktu Habis. Klik Selesai untuk memproses nilai!");
         $('#modal-selesai .btn-warning').hide();
      }

   }, 1000);
});

//Ketika tombol nomor soal atau tombol navigasi diklik
function tampil_soal(no){
   $('.blok-soal').removeClass('active');	
   $('.soal-'+no).addClass('active');	
}

//Ketika ragu-ragu dicentang
function ragu_ragu(no){
   if($('.tombol-'+no).hasClass('yellow')){
      $('.tombol-'+no).removeClass('yellow');
   }else{
      $('.tombol-'+no).addClass('yellow');
   }
}

//Ketika ujian selesai
function selesaikan(){
   $('#modal-selesai').modal({
      'show' : true,
      'backdrop' : 'static'
   });
}

//Ketika memilih jawaban
function kirim_jawaban(index, jawab){
   ujian = $('#ujian').val();
   sisa_waktu = $('#sisa_waktu').val();
   $.ajax({
      url: "ajax_ujian.php?action=kirim_jawaban",
      type: "POST",
      data: "ujian=" + ujian + "&index=" + index + "&sisa_waktu=" + sisa_waktu + "&jawab=" + jawab,
      success: function(data){
         if(data=="ok"){
            no = index+1;
            $('.tombol-'+no).addClass("green");
         }else if(data=='lock'){
            alert('Akun anda diblokir sementara oleh operator karena terindikasi kecurangan. Silakan hubungi operator untuk membuka blok!');
            window.location = 'login.php';
         }else{
            alert(data);
         }
      },
      error: function(){
         alert('Tidak dapat mengirim jawaban!');
      }
   });
}


//Ketika mengirim jawaban esay
function kirim_esay(index, idsoal, event){
   event.preventDefault();
   ujian = $('#ujian').val();
   sisa_waktu = $('#sisa_waktu').val();
   $.ajax({
      url: "ajax_ujian.php?action=kirim_esay",
      type: "POST",
      data: "ujian=" + ujian + "&index=" + index + "&idsoal=" + idsoal + "&sisa_waktu=" + sisa_waktu + "&jawab=" + $('.jawaban-'+index).val(),
      success: function(data){
         if(data=="ok"){
            no = index+1;
            $('.tombol-'+no).addClass("green");
         }else if(data=='lock'){
            alert('Akun anda diblokir sementara oleh operator karena terindikasi kecurangan. Silakan hubungi operator untuk membuka blok!');
            window.location = 'login.php';
         }else{
            alert(data);
         }
      },
      error: function(){
         alert('Tidak dapat mengirim jawaban!');
      }
   });
}

//Ketika mengirim jawaban kompleks
function kirim_jawaban_kompleks(index, idsoal, event){
   event.preventDefault();
   ujian = $('#ujian').val();
   sisa_waktu = $('#sisa_waktu').val();

   // Ambil semua checkbox di form sesuai index
   let jawaban = [];
   $('#form-jawaban-' + index + ' input[type="checkbox"]:checked').each(function(){
      jawaban.push($(this).val());
   });

   $.ajax({
      url: "ajax_ujian.php?action=kirim_jawaban_kompleks",
      type: "POST",
       data: {
         ujian: ujian,
         index: index,
         idsoal: idsoal,
         sisa_waktu: sisa_waktu,
         jawab: jawaban.join(",")
      },
      success: function(data){
         if(data=="ok"){
            no = index+1;
            $('.tombol-'+no).addClass("green");
         }else if(data=='lock'){
            alert('Akun anda diblokir sementara oleh operator karena terindikasi kecurangan. Silakan hubungi operator untuk membuka blok!');
            window.location = 'login.php';
         }else{
            alert(data);
         }
      },
      error: function(){
         alert('Tidak dapat mengirim jawaban!');
      }
   });
}

//Ketika mengirim jawaban pernyataan
function kirim_jawaban_pernyataan(index, idsoal, event){
   event.preventDefault();
   ujian = $('#ujian').val();
   sisa_waktu = $('#sisa_waktu').val();

   // Ambil semua checkbox di form sesuai index
   let jawaban = [];   
   $('#form-jawaban-' + index + ' input[type="radio"]:checked').each(function() {
      let nopilihan = $(this).data('index'); 
      jawaban[nopilihan - 1] = $(this).val();
   });

   $.ajax({
      url: "ajax_ujian.php?action=kirim_jawaban_pernyataan",
      type: "POST",
       data: {
         ujian: ujian,
         index: index,
         idsoal: idsoal,
         sisa_waktu: sisa_waktu,
         jawab: jawaban.join(",")
      },
      success: function(data){
         if(data=="ok"){
            no = index+1;
            $('.tombol-'+no).addClass("green");
         }else if(data=='lock'){
            alert('Akun anda diblokir sementara oleh operator karena terindikasi kecurangan. Silakan hubungi operator untuk membuka blok!');
            window.location = 'login.php';
         }else{
            alert(data);
         }
      },
      error: function(){
         alert('Tidak dapat mengirim jawaban!');
      }
   });
}

