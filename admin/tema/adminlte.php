
<html>
<head>   
   <title>Halaman Administrator</title>
   
   <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

   <link rel="stylesheet" href="../assets/adminlte/css/adminlte.min.css">

   <!-- jQuery -->
   <script src="../assets/jquery/jquery-2.0.2.min.js"></script>
   <link rel="stylesheet" type="text/css" href="../assets/bootstrap-5/css/bootstrap.min.css"/>
   <link rel="stylesheet" type="text/css" href="../assets/dataTables/css/dataTables.bootstrap5.css">
   <link rel="stylesheet" type="text/css" href="../assets/fontawesome/css/all.min.css"/>
   <!-- AdminLTE App -->
   <script src="../assets/adminlte/js/adminlte.min.js"></script>
   <script type="text/javascript" src="../assets/sweetalert/sweetalert.js"></script>
   <script type="text/javascript" src="../js/sweetalert_helper.js"></script>

</head>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">

<div class="app-wrapper">
  <!-- Navbar -->
  <nav class="app-header navbar navbar-expand bg-body shadow-sm">
    <div class="container-fluid">
      <ul class="navbar-nav">
         <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
               <i class="fa-solid fa-bars"></i>
            </a>
         </li>
      </ul>

      <ul class="navbar-nav ms-auto">
         <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img
                  src="../images/logo.jpg"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline"><?= $_SESSION['namalengkap'] ?></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src="../images/logo.jpg"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    <?= $_SESSION['namalengkap'] ?>
                  </p>
                </li>
                <!--end::User Image-->
                <!--begin::Menu Body-->
                <li class="user-footer">
                  <a href="view/view_profil.php" class="navigation btn btn-default btn-flat">Profile</a>
                  <a href="logout.php" class="navigation btn btn-default btn-flat float-end">Sign out</a>
                </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
         </ul>
    </div>
  </nav>

  <!-- Sidebar -->
  <aside class="app-sidebar bg-body-secondary shadow" id="sidebar" data-bs-theme="dark">
    <div class="sidebar-brand p-3">
      <span class="brand-text fw-light">Menu Admin</span>
    </div>
    <div class="sidebar-wrapper">
      <nav class="mt-2">
         <?php include "menu_adminlte.php"; ?>
      </nav>
    </div>
  </aside>

  <!-- Konten -->
  <main class="app-main" id="main" tabindex="-1">
      <div id="content"></div>
  </main>

  <!-- Footer -->
  <footer class="app-footer text-center py-3">
    <strong>Copyright &copy; sansoftware komputer ver 04.</strong>
    All rights reserved.
  </footer>
</div>
	
<script type="text/javascript" src="../assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="../assets/dataTables/js/datatables.min.js"></script>
<script type="text/javascript" src="../assets/dataTables/js/dataTables.bootstrap5.js"></script>
<script type="text/javascript" src="../js/admin.js"></script>

</body>
</html>
