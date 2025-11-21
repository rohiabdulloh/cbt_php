var table;

//Menampilkan data dengan plugin dataTable
$(function(){
   table = $('.table').DataTable({
     "processing" : true,
     "ajax" : {
       "url" : "ajax/ajax_siswa_operator.php?action=table_data",
       "type" : "POST"
     }
   });
});

//Ketika tombol Refresh diklik
function refresh_data(){
   table.ajax.reload();
}

//Ketika tombol Reset Login diklik
function reset_login(id){
   if(confirm("Apakah yakin akan mereset login siswa dengan nis "+id+" ?")){
      $.ajax({
         url : "ajax/ajax_siswa_operator.php?action=reset_login&nis="+id,
         type : "GET",
         success : function(data){
            table.ajax.reload();
         },
         error : function(){
            showError("Tidak dapat mereset login!");
         }
      });
   }
}


function Lock_login(id){
   if(confirm("Apakah yakin akan Lock login siswa dengan nis "+id+" ?")){
      $.ajax({
         url : "ajax/ajax_siswa_operator.php?action=Lock_login&nis="+id,
         type : "GET",
         success : function(data){
            table.ajax.reload();
         },
         error : function(){
            showError("Tidak dapat Lock login!");
         }
      });
   }
}

function UNLock_login(){
   if(confirm("Apakah yakin akan unlock atau reset Semua login siswa Login ?")){
      $.ajax({
         url : "ajax/ajax_siswa_operator.php?action=UNLock_login",
         type : "GET",
         success : function(data){
            table.ajax.reload();
         },
         error : function(){
            showError("Tidak dapat unLock login!");
         }
      });
   }
}