var save_method, table;

//Menampilkan data dengan plugin datatables dan konfigurasi datepicker
$(function(){
   table = $('.table').DataTable({
      "processing" : true,
      "ajax" : {
         "url" : "ajax/ajax_filesoal_admin.php?action=table_data",
         "type" : "POST"
      }
   });
});


function export_filesoal(){
    window.open("export/file_soal.php", "Download Pengumpulan File Soal");
 }
 
 //Ketika tombol hapus diklik
function delete_data(id){
   if(confirm("Apakah yakin data akan dihapus?")){
      $.ajax({
         url : "ajax/ajax_filesoal_admin.php?action=delete&id="+id,
         type : "GET",
         success : function(data){
            table.ajax.reload();
         },
         error : function(){
           alert("Tidak dapat menghapus data!");
         }
      });
   }
}

 