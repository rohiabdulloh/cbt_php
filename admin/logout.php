<?php
  session_start();
  session_destroy();
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="script/sweetalert_helper.js"></script>
<script>
showSuccess('Anda telah logout dari halaman admin');
setTimeout(function(){
   window.location = 'login.php';
}, 2000);
</script>
