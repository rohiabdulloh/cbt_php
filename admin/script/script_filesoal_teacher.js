var save_method, table;

//Menampilkan data dengan plugin datatables dan konfigurasi datepicker
$(function(){
   table = $('.table').DataTable({
      "processing" : true,
      "ajax" : {
         "url" : "ajax/ajax_filesoal_teacher.php?action=table_data",
         "type" : "POST"
      }
   });
});

//Ketika tombol tambah diklik
function form_add(){
   save_method = "add";
   $('#modal_filesoal').modal('show');
   $('#modal_filesoal form')[0].reset();
   $('.modal-title').text('Tambah File');
   $('#filesoal').attr('disabled',false);
   $('#filekisi').attr('disabled',false);
}
	
//Ketika tombol edit diklik
function form_edit(id){
   save_method = "edit";
   $('#modal_filesoal form')[0].reset();
   $.ajax({
      url : "ajax/ajax_filesoal_teacher.php?action=form_data&id="+id,
      type : "GET",
      dataType : "JSON",
      success : function(data){
         $('#modal_filesoal').modal('show');
         $('.modal-title').text('Edit File');
			
         $('#id').val(data.id_filesoal);
         $('#nama_guru').val(data.nama_guru);
         $('#nip').val(data.nip);
         $('#ujian').val(data.id_ujian);
         $('#filesoal').attr('disabled',true);
         $('#filekisi').attr('disabled',true);
      },
      error : function(){
         alert("Tidak dapat menampilkan data!");
      }
   });
}

//Ketika tombol simpan diklik
function save_data(){
    var formData = new FormData();
    formData.append('id_ujian', $('#ujian').val());
    formData.append('nama_guru', $('#nama_guru').val());
    formData.append('nip', $('#nip').val());
    formData.append('filesoal', $('#filesoal')[0].files[0]);
    formData.append('filekisi', $('#filekisi')[0].files[0]);

   if(save_method == "add") url = "ajax/ajax_filesoal_teacher.php?action=insert";
   else url = "ajax/ajax_filesoal_teacher.php?action=update";
   $.ajax({
      url : url,
      type : "POST",
      data : formData,
      processData: false,
      contentType: false,
      success : function(data){
        if(data=="ok"){
            $('#modal_filesoal').modal('hide');
            table.ajax.reload();
        }else{
            alert(data);
        }
      },
      error : function(){
         alert("Tidak dapat menyimpan data!");
      }			
   });
   return false;
}
	
//Ketika tombol hapus diklik
function delete_data(id){
   if(confirm("Apakah yakin data akan dihapus?")){
      $.ajax({
        url : "ajax/ajax_filesoal_teacher.php?action=delete&id="+id,
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
