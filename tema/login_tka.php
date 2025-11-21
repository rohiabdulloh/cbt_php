<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CBT SMAN 1 Kencong</title>
  
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-5/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/fontawesome/css/all.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/login_tka.css"/>

    
    <script type="text/javascript" src="assets/jquery/jquery-2.0.2.min.js"></script>
    <script type="text/javascript" src="assets/sweetalert/sweetalert.js"></script>
    <script type="text/javascript" src="js/sweetalert_helper.js"></script>

    <script type="text/javascript">
    $(function(){
      $('.login-form').submit(function(event){
        event.preventDefault();
        let username = $('input[name=username]').val().trim();
        let password = $('input[name=password]').val().trim();

        if(username === ""){
            showWarning('Kotak input Username masih kosong!');
        } else if(password === ""){
            showWarning('Kotak input Password masih kosong!');
        } else{
            $.ajax({
                type : "POST",
                url : "login_cek.php",
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
            });
        }
        return false;
      });
    });
    </script>

</head>
<body>

  <!-- Bagian atas biru -->
  <div class="top-bg bg-primary">
    <div>
      <img src="images/logo depan1.jpg" width="250" alt="Logo SMAN 1 Kencong" class="logo mb-3">
    </div>

    <!-- SVG bentuk gelombang -->
    
  </div>

  <!-- Card login -->
  <div class="card login-card">
    <div class="card-body">
      <h5 class="mb-3">Selamat Datang</h5>
      <p class="text-muted info mb-4">Silakan login dengan username dan password yang anda miliki</p>

      <form class="login-form">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <i class="fas fa-user-circle form-icon"></i>
          <div class="flex-grow-1 form-floating">
            <input type="text" class="form-control border-bottom-only" name="username" id="username" placeholder="Username">
            <label for="username"></i>Username</label>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="icon-lock"><i class="fas fa-lock"></i></div>
          <div class="flex-grow-1 form-floating position-relative">
            <input type="password" class="form-control border-bottom-only" name="password" id="password" placeholder="Password">
            <label for="password">Password</label>
            <i class="fas fa-eye-slash password-toggle" id="togglePassword"></i>
          </div>
        </div>

        <button type="submit" class="btn btn-login w-100 text-white rounded-pill">Login</button>
      </form>
    </div>
    <div class="card-footer text-center text-muted"></div>
  </div>

  <!-- JavaScript Toggle Password -->
  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', () => {
      // Toggle tipe input
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);

      // Ganti ikon mata
      togglePassword.classList.toggle('bi-eye');
      togglePassword.classList.toggle('bi-eye-slash');
    });
  </script>

</body>
</html>
