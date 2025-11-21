<?php
//Fungsi untuk membuka modal dan form
function open_form($modal_id, $action){
   echo '<div class="modal fade" id="'.$modal_id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
				  
<form class="form-horizontal" enctype="multipart/form-data"  onsubmit="'.$action.'">
   <div class="modal-header">
      <h3 class="modal-title"></h3>
      <button type="button" class="close btn-close" data-dismiss="modal"  
         data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"> </span> </button>
   </div>
				
   <div class="modal-body">
      <input type="hidden" name="id" id="id">';
}

//Fungsi untuk membuat kotak input
function create_textbox($label, $name, $type="text", $width='5', $class="", $attr=""){
   echo'<div class="form-group row mb-2">
   <label for="'.$name.'" class="col-sm-2 control-label"> '.$label.'</label>
   <div class="col-sm-'.$width.'">
      <input type="'.$type.'" class="form-control '.$class.'" id="'.$name.'" name="'.$name.'" '.$attr.'>
   </div> </div>';
}

//Fungsi untuk membuat textarea
function create_textarea($label, $name, $class='', $attr=''){
   echo'<div class="form-group row mb-2" id="'.$name.'_wrapper">
   <label for="'.$name.'" class="col-sm-2 control-label"> '.$label.'</label>
   <div class="col-sm-10">
     <textarea class="form-control '.$class.'" id="'.$name.'" rows="3" name="'.$name.'" '.$attr.'></textarea>
   </div> </div>';
}


//Fungsi untuk membuat combobox / select box
function create_combobox($label, $name, $list, $width='5', $class="", $attr="", $value="",){
   echo'<div class="form-group row mb-2">
   <label for="'.$name.'" class="col-sm-2 control-label"> '.$label.'</label>
   <div class="col-sm-'.$width.'">
      <select class="form-select form-control '.$class.'" name="'.$name.'" id="'.$name.'" '.$attr.'>
         <option value="">- Pilih -</option>';

   foreach($list as $ls){
      // jika nilai sekarang sama dengan nilai dari database, beri selected
      $selected = ($ls[0] == $value) ? 'selected' : '';
      echo '<option value="'.$ls[0].'" '.$selected.'>'.$ls[1].'</option>';
   }
	
   echo '</select>
   </div> </div>';
}



//tambahan

function create_combobox1($label, $name, $blist, $width='5', $class="", $attr=""){
   echo'<div class="form-group row mb-2">
   <label for="'.$name.'" class="col-sm-2 control-label"> '.$label.'</label>
   <div class="col-sm-'.$width.'">
      <select class="form-select form-control '.$class.'" name="'.$name.'" id="'.$name.'" '.$attr.'>
         <option value="">- Pilih -</option>';

foreach($blist as $ls){
   echo '<option value='.$ls[0].'>'.$ls[1].'</option>';
}
	
   echo '</select>
   </div> </div>';
}

//tambahan




//Fungsi untuk membuat checkbox
function create_checkbox($label, $name, $list){
   echo '<div class="form-group row mb-2" id="'.$name.'">
   <label class="col-sm-2 control-label">'.$label.'</label>
   <div class="col-sm-10">';

foreach($list as $ls){
   echo' <input type="checkbox" name="'.$name.'[]" value="'.$ls[0].'" style="margin-left: 30px"> '.$ls[1];
}
	
   echo '</div></div>';
}



//Fungsi untuk membuat radio button
function create_radio($label, $name, $list){
   echo '<div class="form-group row mb-2" id="'.$name.'">
   <label class="col-sm-2 control-label">'.$label.'</label>
   <div class="col-sm-10">';

foreach($list as $ls){
   echo' <input type="radio" name="'.$name.'" value="'.$ls[0].'" style="margin-left: 30px"> '.$ls[1];
}
	
   echo '</div></div>';
}


//Fungsi untuk menutup form dan modal
function close_form($icon="floppy-disk", $button="Simpan"){
   echo'</div>
   <div class="modal-footer">
   <button type="submit" class="btn btn-primary btn-save">
   <i class="glyphicon glyphicon-'.$icon.'"></i> '.$button.' 
   </button>
   <button type="button" class="btn btn-warning" data-dismiss="modal" data-bs-dismiss="modal">
   <i class="glyphicon glyphicon-remove-sign"></i> Close
   </button>
   </div>
		
   </form></div></div></div>';
}
?>
