<div class="navbar-header">
   <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
   </button>
</div>

<div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav">

<?php
	
function menu_admin($link, $icon, $title){
   $item = '<li><a href="'.$link.'" class="navigation">'.$title.'</a></li>';
   return $item;
}

if($_SESSION['leveluser'] == "admin"){	
   echo menu_admin("home.php", "home", "Beranda");
   //echo menu_admin("view/view_jenis_ujian.php", "edit", "Jenis Ujian");
   echo menu_admin("view/view_ujian.php", "book", "Mapel Ujian");
   echo menu_admin("view/view_siswa.php", "user-graduate", "Siswa");
   echo menu_admin("view/view_user.php", "user-tie", "User");
   echo menu_admin("view/view_kelas.php", "signal", "Kelas");
   echo menu_admin("view/view_klsujian.php", "chalkboard-user", "Kelas Ujian");
   echo menu_admin("view/view_filesoal_admin.php", "file", "File Soal");
   echo menu_admin("view/view_bagiruang_admin.php", "th-large", "Bagi Ruang");
   echo menu_admin("view/view_pengaturan.php", "cog", "Pengaturan");
}

elseif($_SESSION['leveluser'] == "operator"){
   echo menu_admin("home.php", "home", "Beranda");
   echo menu_admin("view/view_ujian_operator.php", "edit", "Ujian");
   echo menu_admin("view/view_siswa_operator.php", "list-alt", "Siswa");
}
else{
   echo menu_admin("home.php", "home", "Beranda");
   echo menu_admin("view/view_ujian_teacher.php", "edit", "Ujian");
   echo menu_admin("view/view_filesoal_teacher.php", "file", "File Soal");
}
?>

   </ul>
   <ul class="nav navbar-nav navbar-right">

<?php
   echo menu_admin("view/view_profil.php", "user", $_SESSION['namalengkap']);
   echo menu_admin("logout.php", "sign-out", "Keluar");
?>

   </ul>
</div>
