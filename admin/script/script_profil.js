$(function(){	
   $('#form-profil').submit(function(){
      if($('#baru').val() != $('#ulang').val()){
         showWarning('Password Baru tidak sama dengan Ulang Password');
      }else{
         $.ajax({
            url : "ajax/ajax_profil.php",
            type : "POST",
            data : $('#form-profil').serialize(),
            success : function(data){
               if(data=="ok"){
                  showSuccess("Password berhasil diubah");
                  $('#form-profil')[0].reset();
               }else{
                  showWarning("Terjadi permasalahan pada server");
               }
            },
            error : function(){
               showError("Tidak dapat mengubah data!");
            }			
         });
      }
      return false;
   });
});
