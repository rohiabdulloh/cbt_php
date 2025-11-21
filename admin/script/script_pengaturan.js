$(function(){	
   $('#form-pengaturan').submit(function(){
      $.ajax({
         url : "ajax/ajax_pengaturan.php",
         type : "POST",
         data : $('#form-pengaturan').serialize(),
         success : function(data){
            if(data == "ok"){
               showSuccess("Pengaturan tema berhasil disimpan");
               setTimeout(() => {
                  window.location.reload();
               }, 2000);
            }else{
               showWarning("Terjadi kesalahan saat menyimpan pengaturan!");
            }
         },
         error : function(){
            showError("Tidak dapat menyimpan pengaturan!");
         }			
      });
      return false;
   });
});
