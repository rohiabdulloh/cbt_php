var table;

// =================================
// INIT DATATABLE
// =================================
$(function () {
    initTable();
    autoRefresh(); // aktifkan jika mau realtime
    load_summary(); // saat halaman dibuka
});



// =================================
// INISIALISASI DATATABLE
// =================================
function initTable() {
    table = $('.table').DataTable({
        processing: true,
        ajax: {
            url: "ajax/ajax_siswa_operator.php?action=table_data",
            type: "POST"
        }
    });
}

// =================================
// FUNGSI REFRESH TERPUSAT
// =================================
function refreshData() {
    if (table) {
        table.ajax.reload(null, false); // false = tetap di halaman yg sama
    }
}

function load_summary() {
    $.getJSON('ajax/summary.php')
    .done(function(res){
        console.log("SUMMARY OK:", res);
        $('#jml_login').text(res.login);
        $('#jml_kerja').text(res.mengerjakan);
        $('#jml_selesai').text(res.selesai);
        $('#jml_lock').text(res.lock);
    })
    .fail(function(xhr){
        console.error("SUMMARY ERROR");
        console.log(xhr.responseText);
    });
}

// =================================
// AUTO REFRESH REAL-TIME (OPSIONAL)
// =================================
function autoRefresh() {
    setInterval(function () {
        refreshData();
        load_summary(); // aktifkan jika punya summary
    }, 3000); // 3 detik
}

// =================================
// RESET LOGIN SISWA
// =================================
function reset_login(id) {
    if (confirm("Apakah yakin akan mereset login siswa dengan NIS " + id + " ?")) {
        $.get("ajax/ajax_siswa_operator.php", {
            action: "reset_login",
            nis: id
        })
        .done(function () {
            refreshData();
        })
        .fail(function () {
            showError("Tidak dapat mereset login!");
        });
    }
}

// =================================
// LOCK LOGIN SISWA
// =================================
function Lock_login(id) {
    if (confirm("Apakah yakin akan lock IP siswa dengan NIS " + id + " ?")) {
        $.get("ajax/ajax_siswa_operator.php", {
            action: "Lock_login",
            nis: id
        })
        .done(function () {
            refreshData();
        })
        .fail(function () {
            showError("Tidak dapat lock login!");
        });
    }
}

// =================================
// UNLOCK / RESET SEMUA LOGIN
// =================================
function UNLock_login() {
    if (confirm("Apakah yakin akan unlock / reset semua login siswa?")) {
        $.get("ajax/ajax_siswa_operator.php", {
            action: "UNLock_login"
        })
        .done(function () {
            refreshData();
        })
        .fail(function () {
            showError("Tidak dapat unlock login!");
        });
    }
}
