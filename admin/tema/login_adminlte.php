<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login Administrator</title>

<link rel="stylesheet" href="../assets/adminlte/css/adminlte.min.css">

<!-- jQuery -->
<script src="../assets/jquery/jquery-2.0.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="../assets/bootstrap-5/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="../assets/fontawesome/css/all.min.css"/>
<!-- AdminLTE App -->
<script src="../assets/adminlte/js/adminlte.min.js"></script>
<script type="text/javascript" src="../assets/sweetalert/sweetalert.js"></script>
<script type="text/javascript" src="../js/sweetalert_helper.js"></script>

<script type="text/javascript">
$(function(){
   $('.login-form').submit(function(){
      let username = $('input[name=username]').val().trim();
      let password = $('input[name=password]').val().trim();

      if(username === ""){
         showWarning('Kotak input Username masih kosong!');
      } else if(password === ""){
         showWarning('Kotak input Password masih kosong!');
      } else {
         $.ajax({
            type : "POST",
            url  : "login_cek.php",
            data : $(this).serialize(),
            success : function(data){
               if(data.trim() === "ok"){
                  Swal.fire({
                     icon: 'success',
                     title: 'Login Berhasil!',
                     text: 'Anda akan diarahkan ke halaman admin...',
                     showConfirmButton: false,
                     timer: 1500
                  });
                  setTimeout(() => window.location = "index.php", 1500);
               } else {
                  showError('Login Gagal!');
               }
            },
            error: function(){
               showError('Tidak dapat memproses permintaan login.');
            }
         });
      }
      return false;
   });
});
</script>
</head>

<body class="login-page bg-body-secondary">
  <div class="login-box">

    <div class="card shadow">
      <div class="card-header p-4 text-center ">
         <h2>Login Admin</h2>
      </div>
      <div class="card-body login-card-body">
        <p class="login-box-msg">Masukkan Username dan Password</p>

        <form class="login-form">
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" autofocus>
            <div class="input-group-text"><span class="fa-solid fa-user"></span></div>
          </div>

          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <div class="input-group-text"><span class="fa-solid fa-lock"></span></div>
          </div>

          <div class="mb-3">
            <button type="submit" class="btn btn-primary w-100">
            <i class="fa-solid fa-sign-in"></i> Login Administrator
            </button>
           </div>
        </form>

      </div>
      <div class="card-footer text-center">       
        <p class="mb-0 text-center text-muted" style="font-size: 0.9em;">
          &copy; <?= date('Y') ?> SMAN 1 Kencong | Developed by IT Team
        </p>
    </div>
  </div>
</body>
</html>
