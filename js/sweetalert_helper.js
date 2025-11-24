
// ======== FUNGSI SWEETALERT REUSABLE ========

// Alert sukses
function showSuccess(message = "Berhasil disimpan!") {
   Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: message,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'OK'
   });
}

// Alert peringatan
function showWarning(message = "Ada yang perlu diperhatikan!") {
   Swal.fire({
      icon: 'warning',
      title: 'Peringatan',
      text: message,
      confirmButtonColor: '#f0ad4e',
      confirmButtonText: 'Tutup'
   });
}

// Alert kesalahan
function showError(message = "Terjadi kesalahan!") {
   Swal.fire({
      icon: 'error',
      title: 'Kesalahan!',
      text: message,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Tutup'
   });
}
