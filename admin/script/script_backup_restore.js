function backup_database(){
    if (confirm("Backup database sekarang?")) {
        // 1. Ambil elemen tombol
        var btn = document.querySelector('button[onclick="backup_database()"]');
        
        // 2. Tampilkan status loading
        btn.disabled = true; 
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...'; 
        // 3. Lakukan pengalihan
        window.location = "ajax/ajax_backup_restore.php?action=backup";

        setTimeout(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-download"></i> Backup Sekarang';
        }, 3000);
        
    }
}

function restore_database(){
    var file = $('#restore_file').prop('files')[0];

    // 1. Ambil elemen tombol
    var btn = $('button[onclick="restore_database()"]');

    if(!file){
        alert("Pilih file .sql terlebih dahulu!");
        return;
    }

    // 2. Tampilkan status loading
    btn.prop('disabled', true);
    btn.html('<i class="fa fa-spinner fa-spin"></i> Mengunggah & Restore...');

    var formData = new FormData();
    formData.append('file', file);

    $.ajax({
        url: "ajax/ajax_backup_restore.php?action=restore",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data){
            alert(data);
        },
        error: function(){
            alert("Gagal restore database!");
        },

        // 3. Mengembalikan tombol ke keadaan normal setelah AJAX selesai (baik sukses/gagal)
        complete: function() {
            btn.prop('disabled', false); 
            btn.html('<i class="fa fa-upload"></i> Restore'); 
        }
    });
}
