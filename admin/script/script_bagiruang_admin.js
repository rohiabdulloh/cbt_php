var save_method, table;

//Menampilkan data dengan plugin datatables dan konfigurasi datepicker
$(function(){
   table = $('.table').DataTable({
      "processing" : true,
      "ajax" : {
         "url" : "ajax/ajax_bagiruang_admin.php?action=table_data",
         "type" : "POST"
      }
   });
});


function export_bagiruang(){
    window.open("export/bagi_ruang.php", "Download Pembagian Ruang");
 }
 

//Ketika tombol reset diklik
function reset_bagiruang(){
    $('#modal_bagiruang').modal('show');
    $('#modal_bagiruang form')[0].reset();
    $('.modal-title').text('Tambah Bagi Ruang');
}
     
 
 //Ketika tombol simpan diklik
 function save_data(){
    url = "ajax/ajax_bagiruang_admin.php?action=update";
    $.ajax({
       url : url,
       type : "POST",
       data : $('#modal_bagiruang form').serialize(),
       success : function(data){
        
          $('#modal_bagiruang').modal('hide');
          table.ajax.reload();
       
       },
       error : function(){
            alert("Tidak dapat menyimpan data!");
       }			
    });
    return false;
 }

 