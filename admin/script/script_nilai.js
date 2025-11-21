var table, ujian, kelas;

$(function(){
   ujian = $('#id_ujian').val();
   kelas = $('#id_kelas').val();
   table = $('.table').DataTable({
      "processing" : true,
      "pageLength" : 50,
      "paging" : false,
      "ajax" : {
         "url" : "ajax/ajax_nilai.php?action=table_data&ujian=" + ujian + "&kelas=" + kelas,
         "type" : "POST"
      }
   });
});

function export_nilai(){
   ujian = $('#id_ujian').val();
   kelas = $('#id_kelas').val();
   window.open("export/excel_nilai.php?ujian=" + ujian + "&kelas=" + kelas, "Export Nilai");
}

//Ketika tombol koreksi diklik
function koreksi_jawaban(nis){
   $.ajax({
      url : "ajax/ajax_nilai.php?action=form_data&ujian="+ujian+"&nis="+nis,
      type : "GET",
      dataType : "JSON",
      success : function(data){
         $('#modal_nilai form')[0].reset();
         $('#modal_nilai').modal('show');
         $('.modal-title').text('Koreksi Jawaban');
			$('.form-koreksi').html(data.table);
         $('#nis').val(data.nis);
         $('#idujian').val(ujian);
      },
      error : function(data){
         //showError(JSON.stringify(data));
         showError('Tidak dapat menampilkan data');
      }
   });
	
   $('#kelas input').attr('checked', false);		
}

//Ketika tombol simpan diklik
function save_data(){
   url = "ajax/ajax_nilai.php?action=update";
   $.ajax({
      url : url,
      type : "POST",
      data : $('#modal_nilai form').serialize(),
      success : function(data){
         $('#modal_nilai').modal('hide');
         table.ajax.reload();
      },
      error : function(){
         showError("Tidak dapat menyimpan data!");
      }			
   });
   return false;
}

//Ketika tombol reset diklik
function reset_nilai(nis){
   if(confirm("Apakah yakin akan mereset nilai?")){
      $.ajax({
         url : "ajax/ajax_nilai.php?action=reset&ujian="+ujian+"&nis="+nis,
         type : "GET",
         success : function(data){
            table.ajax.reload();
         },
         error : function(){
           showError("Tidak dapat mereset data!");
         }
      });
   }
}

function reset_all(){
   if(confirm("Apakah yakin akan mereset semua nilai?")){
      $.ajax({
         url : "ajax/ajax_nilai.php?action=reset_all&ujian="+ujian+"&kls="+kelas,
         type : "GET",
         success : function(data){
            table.ajax.reload();
         },
         error : function(){
           showError("Tidak dapat mereset data!");
         }
      });
   }
}

function download_jawaban(nis){
   window.open("export/pdf_esay.php?ujian="+ujian+"&nis="+nis, "Download Jawaban Esay", "height=650, width=1024, left=150, scrollbars=yes");
   return false;
}

function download_esay(){
   window.open("export/pdf_esay_all.php?ujian="+ujian+"&kelas="+kelas, "Download Semua Jawaban Esay", "height=650, width=1024, left=150, scrollbars=yes");
   return false;
}

function detail_jawaban(nis){
   window.open("export/pdf_detail_jawaban.php?ujian="+ujian+"&nis="+nis, "Detail Jawaban", "height=650, width=1024, left=150, scrollbars=yes");
   return false;
}