<ul
   class="nav sidebar-menu flex-column"
   data-lte-toggle="treeview"
   role="navigation"
   aria-label="Main navigation"
   data-accordion="false"
   id="navigation"
>
	
<?php
function menu_admin($link, $icon, $title){
   $item = '<li class="nav-item">
      <a href="'.$link.'" class="navigation nav-link">
         <i class="nav-icon fa-solid fa-'.$icon.'"></i>
         <p>'.$title.'</p>
      </a>
   </li>';
   return $item;
}

if($_SESSION['leveluser'] == "admin"){	
   echo menu_admin("home.php", "home", "Beranda");
   //echo menu_admin("view/view_jenis_ujian.php", "edit", "Jenis Ujian");
   echo menu_admin("view/view_ujian.php", "book", "Mapel Ujian");
   echo menu_admin("view/view_siswa.php", "user-graduate", "Siswa");
   echo menu_admin("view/view_user.php", "user-tie", "User");
   echo menu_admin("view/view_kelas.php", "chalkboard", "Kelas");
   echo menu_admin("view/view_klsujian.php", "chalkboard-user", "Kelas Ujian");
   echo menu_admin("view/view_filesoal_admin.php", "file", "File Soal");
   echo menu_admin("view/view_bagiruang_admin.php", "th-large", "Bagi Ruang");
   echo menu_admin("view/view_pengaturan.php", "cog", "Pengaturan");
   echo menu_admin("view/view_log.php", "history", "Log Login");
   echo menu_admin("view/view_backup_restore.php", "database", "Backup & Restore");
}

elseif($_SESSION['leveluser'] == "operator"){
   echo menu_admin("home.php", "home", "Beranda");
   echo menu_admin("view/view_ujian_operator.php", "edit", "Ujian");
   echo menu_admin("view/view_siswa_operator.php", "list-alt", "Siswa");   
   echo menu_admin("view/view_log_siswa.php", "history", "Log Login Siswa");
}
else{
   echo menu_admin("home.php", "home", "Beranda");
   echo menu_admin("view/view_ujian_teacher.php", "edit", "Ujian");
   echo menu_admin("view/view_filesoal_teacher.php", "file", "File Soal");
}
?>
</ul>
