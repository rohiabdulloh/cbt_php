var save_method, table, editor_id, ujian, bobot;

//Menampilkan data dengan plugin dataTable
$(function(){
   ujian = $('#id_ujian').val();
   table = $('.table').DataTable({
      "processing" : true,
      "ajax" : {
         "url" : "ajax/ajax_soal.php?action=table_data&ujian="+ujian,
         "type" : "POST"
      }
   });
   
   $('.mediapicker .close').click(function(){
		$('.mediapicker').modal('hide');
   });

   ubah_jenis();
   $('#jenis').change(function(){
      ubah_jenis();
   });
   
   $('#bobot').change(function(){
      bobot = $('#bobot').val();
   });

   tinymce_config();
   tinymce_config_simple();

   $('#parameter').on('input', function() {
      for (let i = 1; i <= 5; i++) {
         generateRadioButtons('#parameter', `kunci_pernyataan_${i}`, `kunci_pernyataan_${i}`);
      }
   });

});

function generateRadioButtons(parameterSelector, containerId, radioNamePrefix) {
   const parameters = $(parameterSelector).val().split(','); // Pisahkan berdasarkan koma
   const container = $('#' + containerId);
   container.empty(); // Hapus radio sebelumnya

   // Buat radio sesuai jumlah parameter
   parameters.forEach((param, index) => {
   const parameter = param.trim();
   if (parameter) {
      const radioHtml = `<span style="margin-right: 30px"> <input type="radio" id="${radioNamePrefix}_${index}" 
                   name="${radioNamePrefix}" 
                   value="${index + 1}"> ${parameter} </span>`;
      container.append(radioHtml);
   }
   });
}

function ubah_jenis(){
   if($('#jenis').val() == "") $('#jenis').val(0);
   if($('#jenis').val() == 0){
      $('.pg_area').show();
      $('.pg_kunci_area').show();
      $('.kompleks_kunci_area').hide();
      $('.matching_area').hide();
      $('#pil_1, #pil_2, #pil_3, #kunci').attr('required', true);

   }else if($('#jenis').val() == 2){
      $('.pg_area').show();
      $('.pg_kunci_area').hide();
      $('.kompleks_kunci_area').show();
      $('.matching_area').hide();
      $('#pil_1, #pil_2, #pil_3').attr('required', true);
      $('#kunci').attr('required', false);

   }else if($('#jenis').val() == 3){
      $('.pg_area').hide();
      $('.pg_kunci_area').hide();
      $('.kompleks_kunci_area').hide();
      $('.matching_area').show();
      $('#pil_1, #pil_2, #pil_3, #pil_4, #pil_5').attr('required', false);
      $('#kunci').attr('required', false);

   }else{
      $('.pg_area').hide();
      $('.pg_kunci_area').hide();
      $('.kompleks_kunci_area').hide();
      $('.matching_area').hide();
      $('#pil_1, #pil_2, #pil_3, #pil_4, #pil_5, #kunci').attr('required', false);
   }
};

function update_status(data){
   // Jika data masih string, ubah ke object
   if (typeof data === "string") {
      data = JSON.parse(data);
   }

   // baca nilai bobot & soal
   let bobot = parseInt(data.bobot);
   let soal  = parseInt(data.soal);

   // update ke HTML
   $('.totalbobot').text(bobot);
   $('.totalsoal').text(soal);

   // ambil nilai maksimal soal dari HTML
   let maxSoal = parseInt($('.totalsoal').data('max'));

   // update status
   if (data.soal >= maxSoal || data.bobot >= 100) {
       $('.status').html('<span class="text-success"> Lengkap</span>');
   } else {
       $('.status').html('<span class="text-danger"> Belum Lengkap</span>');
   }
}

//Ketika tombol tambah diklik
function form_add(){
   save_method = "add";
   $('#modal_soal').modal('show');
  
   $('#modal_soal form')[0].reset();
   $('#jenis').val(0);
   if(bobot!==undefined && bobot!==null) $('#bobot').val(bobot);
   $('.pg_area').show();   
   $('.kompleks_kunci_area').hide();
   $('.matching_area').hide();
   $('.modal-title').text('Tambah Soal');
}
	
//Ketika tombol edit diklik
function form_edit(id){
   save_method = "edit";   
   $('#modal_soal form')[0].reset();
   
   $.ajax({
      url : "ajax/ajax_soal.php?action=form_data&id="+id,
      type : "GET",
      dataType : "JSON",
      success : function(data){
         $('#modal_soal').modal('show');
         tinymce_config();
         tinymce_config_simple();
         $('.modal-title').text('Edit Soal');
			
         $('#id').val(data.id_soal);
         $('#jenis').val(data.jenis);
         $('#soal').val(data.soal);
            $('#audio').val(data.audio);
            $('#pil_1, #pernyataan_1').val(data.pilihan_1);
            $('#pil_2, #pernyataan_2').val(data.pilihan_2);
            $('#pil_3, #pernyataan_3').val(data.pilihan_3);
            $('#pil_4, #pernyataan_4').val(data.pilihan_4);
            $('#pil_5, #pernyataan_5').val(data.pilihan_5);
            $('#parameter').val(data.parameter);

            tinymce.get('soal').setContent(data.soal);
            tinymce.get('pil_1').setContent(data.pilihan_1);
            tinymce.get('pil_2').setContent(data.pilihan_2);
            tinymce.get('pil_3').setContent(data.pilihan_3);
            tinymce.get('pil_4').setContent(data.pilihan_4);
            tinymce.get('pil_5').setContent(data.pilihan_5);
            tinymce.get('pernyataan_1').setContent(data.pilihan_1);
            tinymce.get('pernyataan_2').setContent(data.pilihan_2);
            tinymce.get('pernyataan_3').setContent(data.pilihan_3);
            tinymce.get('pernyataan_4').setContent(data.pilihan_4);
            tinymce.get('pernyataan_5').setContent(data.pilihan_5);
            
            $('#kunci').val(data.kunci);

            if (parseInt(data.jenis) === 2) {
               // Pastikan kunci dikonversi ke array
               let kunciArray = [];
       
               // kalau data.kunci sudah berupa string dipisah koma, misal "A,B"
               if (typeof data.kunci === 'string') {
                 kunciArray = data.kunci.split(',').map(k => k.trim());
               }
               // kalau backend kirim array, tinggal pakai
               else if (Array.isArray(data.kunci)) {
                 kunciArray = data.kunci;
               }
       
               // isi ke elemen form (misal select multiple atau checkbox)
               $('#kunci_kompleks').val(kunciArray);
               
               $('input[name="kunci_kompleks[]"]').each(function () {
                  if (kunciArray.includes($(this).val())) {
                  $(this).prop('checked', true);
                  } else {
                  $(this).prop('checked', false);
                  }
               });
             } 

            if (parseInt(data.jenis) === 3) {
               let kunciArray = [];
       
               // kalau data.kunci sudah berupa string dipisah koma, misal "A,B"
               if (typeof data.kunci === 'string') {
                 kunciArray = data.kunci.split(',').map(k => k.trim());
               }
               // kalau backend kirim array, tinggal pakai
               else if (Array.isArray(data.kunci)) {
                 kunciArray = data.kunci;
               }
               
               for (let i = 1; i <= 5; i++) {
                  generateRadioButtons('#parameter', `kunci_pernyataan_${i}`, `kunci_pernyataan_${i}`);
                  const nilaiKunci = kunciArray[i - 1]; // ambil nilai kunci untuk pernyataan ke-i
                  if (nilaiKunci) {
                     $(`input[name="kunci_pernyataan_${i}"][value="${nilaiKunci}"]`).prop('checked', true);
                  }
               }
            }
         $('#bobot').val(data.bobot);
         ubah_jenis();
      },
      error : function(){
         showError("Tidak dapat menampilkan data!");
      }
   });
}

//Ketika tombol simpan pada modal diklik
function save_data(){
   ujian = $('#id_ujian').val();
   if(save_method == "add") 
      url = "ajax/ajax_soal.php?action=insert&ujian="+ujian;
   else url = "ajax/ajax_soal.php?action=update&ujian="+ujian;

   $.ajax({
      url : url,
      type : "POST",
      data : $('#modal_soal form').serialize(),
      success : function(data){
         if(data!==null){
            $('#modal_soal').modal('hide');
            table.ajax.reload();     
            update_status(data);
         }else{
            showError(data);
         }
      },
      error : function(){
         showError("Tidak dapat menyimpan data!");
      }			
   });
   return false;
}
	
//Ketika tombol hapus diklik
function delete_data(id){
   if(confirm("Apakah yakin data akan dihapus?")){
      $.ajax({
         url : "ajax/ajax_soal.php?action=delete&id="+id+"&ujian="+ujian,
         type : "GET",
         success : function(data){
            table.ajax.reload();
            update_status(data);
         },
         error : function(){
            showError("Tidak dapat menghapus data!");
         }
      });
   }
}

//Konfigurasi tinyMCE dengan fitur full
function tinymce_config(){
   tinyMCE.init({
      selector: ".richtext",
      height: 150,
      setup: function (editor) {
         editor.on('change', function () {
            tinymce.triggerSave();
         });
      },
      plugins: [
         "advlist autolink lists link image charmap print preview anchor",
         "searchreplace visualblocks code fullscreen",
         "insertdatetime media table contextmenu paste imagetools responsivefilemanager tiny_mce_wiris"
      ],
      toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | responsivefilemanager tiny_mce_wiris_formulaEditor",
	      
      external_filemanager_path:"../assets/filemanager/",
      filemanager_title:"File Manager" ,
      external_plugins: { "filemanager" : "../filemanager/plugin.min.js"}
   });
}

//Konfigurasi tinyMCE tanpa menu bar
function tinymce_config_simple(){
   tinyMCE.init({
      selector: ".richtextsimple",
      height: 30,
      setup: function (editor) {
         editor.on('change', function () {
            tinymce.triggerSave();
         });
      },
      plugins: [
         "advlist autolink lists link image charmap print preview anchor",
         "searchreplace visualblocks code fullscreen",
         "insertdatetime media table contextmenu paste imagetools responsivefilemanager tiny_mce_wiris"
      ],
      toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | responsivefilemanager tiny_mce_wiris_formulaEditor",
	      
      external_filemanager_path:"../assets/filemanager/",
      filemanager_title:"File Manager" ,
      external_plugins: { "filemanager" : "../filemanager/plugin.min.js"},
      menubar: false
   });
}


//Ketika tombol export diklik
function form_export() {
  window.location.href = "export/format_soal.xls";
}

//Ketika tombol import diklik
function form_import(){
   $('#modal_import').modal('show');
   $('.modal-title').text('Import Excel');
   $('#modal_import form')[0].reset();
}

//Ketika tombol import pada modal diklik
function import_data(){
   var formdata = new FormData();      
   var file = $('#file')[0].files[0];
   formdata.append('file', file);
   $.each($('#modal_import form').serializeArray(), function(a, b){
      formdata.append(b.name, b.value);
   });
	
   ujian = $('#id_ujian').val();
   $.ajax({
      url: 'ajax/ajax_soal.php?action=import&ujian='+ujian,
      data: formdata,
      processData: false,
      contentType: false,
      type: 'POST',
      success: function(data) {
         if(data!==null){
            $('#modal_import').modal('hide');
            table.ajax.reload();
            update_status(data);
         }else{
            showError(data);
         }
      },
      error: function(data){
         showError('Tidak dapat mengimport data!');
      }
   });
   return false;
}