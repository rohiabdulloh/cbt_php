var table;

//Menampilkan data dengan plugin datatables dan konfigurasi datepicker
$(function(){
   table = $('.table').DataTable({
      "processing" : true,
      "ajax" : {
         "url" : "ajax/ajax_log.php?action=table_data",
         "type" : "POST"
      }
   });
});

//Ketika tombol hapus diklik
function delete_data(id){
   if(confirm("Apakah yakin data akan dihapus?")){
      $.ajax({
         url : "ajax/ajax_log.php?action=delete&id="+id,
         type : "GET",
         success : function(data){
            table.ajax.reload();
         },
         error : function(){
           showError("Tidak dapat menghapus data!");
         }
      });
   }
}


 