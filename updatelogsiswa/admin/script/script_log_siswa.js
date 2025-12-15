var table;

//Menampilkan data dengan plugin datatables dan konfigurasi datepicker
$(function(){
   table = $('.table').DataTable({
      "processing" : true,
      "ajax" : {
         "url" : "ajax/ajax_log_siswa.php?action=table_data",
         "type" : "POST"
      }
   });
});


 