<?php
//Fungsi untuk membuat judul konten
function create_title($icon, $title){
   echo '<div class="app-container-header mt-2">
         <div class="container-fluid">
            <div class="row"><h3 class="title">'.$title.'</h3></div>
         </div>
      </div>';
}

//Fungsi untuk membuat tombol pada bagian atas tabel
function create_button($color, $icon, $text, $class = "", $action=""){
   echo '<a class="btn btn-'.$color.' '.$class.' btn-top mr-2 me-2" onclick="'.$action.'"><i class="fa-solid fa-'.$icon.'"></i> '.$text.'</a>';
}

function open_content(){
   echo '<div class="app-content mt-2" style="margin-top: 20px">
            <div class="container-fluid">
               <div class="row">
                     <div class="card shadow-sm bg-body rounded">
                        <div class="card-body">';
}

function close_content(){
   echo '               </div>
                     </div>
               </div>
            </div>
         </div>';
}

//Fungsi untuk membuat tabel
function create_table($header){
   echo'<div class="table-responsive">
   <table class="table table-striped" width="100%">
   <thead><tr>
   <th style="width: 10px">No</th>';

foreach($header as $h){
   echo '<th>'.$h.'</th>';
}			
	
   echo '</tr></thead>
   <tbody></tbody>
   <tfooter><tr>
   <th style="width: 10px">No</th>';
	
foreach($header as $h){
  echo '<th>'.$h.'</th>';
}			
	
   echo'</tr></tfooter>
   </table>
   </div><br/>';
}


//Fungsi untuk membuat tombol aksi pada tabel
function create_action($id, $edit=true, $delete=true){
   $view = "<div style='display: flex'>";
   if($edit) $view .= ' <a class="btn btn-primary btn-edit rounded-circle p-2 btn-sm" onclick="form_edit('.$id.')"><i class="fa-solid fa-pencil"></i></a>';
   if($delete)	$view .= ' <a class="btn btn-danger btn-delete rounded-circle p-2 btn-sm" style="margin-left: 5px" onclick="delete_data('.$id.')"><i class="fa-solid fa-trash"></i></a>';
   $view .= "</div>";
   return $view;
}
?>
