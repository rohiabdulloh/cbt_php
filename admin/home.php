<?php
session_start();
include "../library/config.php";

if(empty($_SESSION['username']) or empty ($_SESSION['password'])){
   header('location: login.php');
}

// Ambil tema login admin dari tabel setting
$q = mysqli_query($mysqli, "SELECT nilai FROM setting WHERE parameter='tema_admin'");
$data = mysqli_fetch_array($q);

// Jika tidak ditemukan, default ke 'klasik'
$tema_admin = $data ? $data['nilai'] : 'klasik';
?>

<div class="container mt-4">
  <div class="card border-0 shadow-sm">
    <div class="card-body text-center p-5 bg-primary-subtle bg-gradient rounded">

      <div class="mb-3 text-info">
        <i class="fa fa-user-circle" style="font-size: 80px"></i>
      </div>

      <h2 class="fw-semibold text-info mb-2">
        Selamat Datang, <?= $_SESSION['namalengkap']; ?>!
      </h2>

      <p class="text-muted mb-0">
        Anda login sebagai
        <span class="badge bg-info text-light px-3 py-2">
          <?= $_SESSION['leveluser']; ?>
        </span>
      </p>

    </div>
  </div>

<?php
if($tema_admin == 'adminlte'){
?>
  <div class="row mt-4">

      <!-- Ujian -->
      <div class="col-lg-3 col-md-6 mb-4">
         <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
            
            <div class="me-3">
               <div class="icon-circle bg-info text-white">
                  <i class="fas fa-book"></i>
               </div>
            </div>

            <div>
               <h2 class="mb-0 font-weight-bold text-info">
                  <?php
                     $jmlujian = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM ujian"));
                     echo $jmlujian;
                  ?>
               </h2>
               <span class="text-muted">Data Ujian</span>
            </div>

            </div>
         </div>
      </div>

      <!-- Kelas -->
      <div class="col-lg-3 col-md-6 mb-4">
         <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">

            <div class="me-3">
               <div class="icon-circle bg-success text-white">
                  <i class="fas fa-chalkboard"></i>
               </div>
            </div>

            <div>
               <h2 class="mb-0 font-weight-bold text-success">
                  <?php
                     $jmlkelas = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM kelas"));
                     echo $jmlkelas;
                  ?>
               </h2>
               <span class="text-muted">Data Kelas</span>
            </div>

            </div>
         </div>
      </div>

      <!-- Siswa -->
      <div class="col-lg-3 col-md-6 mb-4">
         <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">

            <div class="me-3">
               <div class="icon-circle bg-warning text-white">
                  <i class="fas fa-user-graduate"></i>
               </div>
            </div>

            <div>
               <h2 class="mb-0 font-weight-bold text-warning">
                  <?php
                     $jmlsiswa = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM siswa"));
                     echo $jmlsiswa;
                  ?>
               </h2>
               <span class="text-muted">Data Siswa</span>
            </div>

            </div>
         </div>
      </div>

      <!-- User -->
      <div class="col-lg-3 col-md-6 mb-4">
         <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">

            <div class="me-3">
               <div class="icon-circle bg-danger text-white">
                  <i class="fas fa-user-tie"></i>
               </div>
            </div>

            <div>
               <h2 class="mb-0 font-weight-bold text-danger">
                  <?php
                     $jmluser = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM user"));
                     echo $jmluser;
                  ?>
               </h2>
               <span class="text-muted">Data User</span>
            </div>

            </div>
         </div>
      </div>

   </div>
<?php
} 
?>
</div>

<style>
   .icon-circle {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
   }
</style>
