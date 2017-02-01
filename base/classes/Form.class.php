<?php
echo '<script src="./base/js/select2.full.js"></script>
			<link href="./base/css/select2.css" rel="stylesheet">';
class Form {
	
public function __construct($nome,$action,$method,$init=null){
	if( $init !=null ){
		$inicia = 'onload="'.$init.'"';
	}
	if( $method !=null ){
		$method = ' method="'.$method.'"';
	}
	if( $action !=null ){
		$action = ' action="'.$action.'"';
	}
	
	echo '<form id="'.$nome.'" name="'.$nome.'" '.$action.$method.@$inicia.'>';	
}

public function closeform(){
	echo '</form>
	<script>
		$(function () {
			$(\'.datetimepicker\').datetimepicker();
		});
	</script>
	<script>
	  $(function () {
		//Initialize Select2 Elements
		$(".select2").select2();
	  });
	
	  setTimeout(function(){
		 $(".select2").select2();
	  }, 2000);
	</script>
  ';
}

public function addTextField($name,$title,$value,$cols,$required=null,$desativa=null,$placeholder=null){
	if( $value != null ){
		$value = ' value="'.$value.'"';
		$labelAct = ' class="active"';
	}
	if( $desativa != null ){
		$desativa = " readonly";		
	}
	if( $placeholder != null ){
		$placeholder = ' placeholder="'.$placeholder.'"';		
	}
	if( $required != null ){
		$required = " required";
		$marca = '<span style="color: #E75428;" title="Campo Obrigatório!">*</span>';
	}
	if( $title != null ){
		$title = '<label '.@$labelAct.' for="'.@$name.'">'.$title.@$marca.'</label>';		
	}
	
	echo '<div class="form-group col-xs-'.$cols.'" id="area_'.$name.'">'.@$title.'
          <input '.@$desativa.' id="'.$name.'" name="'.$name.'" type="text" class="form-control" '.@$value.@$required.@$placeholder.' >
        </div>';
}

public function addPassField($name,$title,$value,$cols,$required=null,$desativa=null){
	if( $value != null ){
		$value = ' value="'.$value.'"';
		$labelAct = ' class="active"';
	}
	if( $desativa != null ){
		$desativa = " readonly";		
	}
	if( $title != null ){
		$title = ' <label '.@$labelAct.' for="'.$name.'">'.$title.'</label>';		
	}
	echo '<div class="form-group col-xs-'.$cols.'" id="area_'.$name.'">'.@$title.'
          <input '.@$desativa.' id="'.$name.'" name="'.$name.'" type="password" class="form-control" '.$value.'>
        </div>';
}



function addDateTimeField($nome,$titulo,$value,$required,$formato,$xs,$disabled=null){	
	if($required != null){
		$required = " required";
		$marca = '<span style="color: #E75428; line-height:0.3" title="Campo Obrigatório!">*</span>';
	}
	if($value != null){	$value = " value='$value'"; }
	if( $formato == false || $formato == 0 ){ 
		$format = 'DD/MM/YYYY HH:mm'; 
	}elseif($formato == 1){ 
		$format = 'DD/MM/YYYY'; 
	}elseif($formato == 2) {
		$format = 'HH:mm'; 
	}
	if($xs != null){ $xs = ' col-xs-'.$xs; }
	if($disabled != null){ $disabled = ' disabled'; }
	if($titulo != null){ $titulo = ' <label>'.$titulo.'</label>'.@$marca; }
	
	echo '<div class="form-group '.$xs.$md.' clsdatepicker" id="'.$nome.'_area" >'.$titulo.'
		<div class="input-group datetimepicker" id="'.$nome.'_grupo" >
				<input type="text" class="form-control date" name="'.$nome.'" id="'.$nome.'" '.$value.' data-date-format="'.$format.'" '.$required.$disabled.' />
				<span class="input-group-addon" style="font-size:12px"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
		</div>';
	/*echo "<script>
			$(function () {
				$('#".$nome."_grupo').datetimepicker({});
			});
			</script> ";*/
}


public function addHiddenField($name,$value){
	echo '<input type="hidden" id="'.$name.'"  name="'.$name.'" value="'.$value.'">';
}


public function addTextArea($name,$title,$value,$cols,$character,$required=null,$desativa=null,$ckeditor=null,$rows=null){
	if( $value != null ){
		//$labelAct = ' class="active"';
	}
	if( $rows != null ){ $rows = ' rows="'.$rows.'"'; } 
	if( $desativa != null ){ $desativa = " disabled"; } 
	if( $character != null ){ $character = ' length="'.$character.'"'; } 
	if( $required != null ){
		$required = " required";
		$marca = '<span style="color: #E75428;" title="Campo Obrigatório!">*</span>';
	}
	
	echo '<div class="form-group col-xs-'.$cols.'" id="area_'.$name.'">
        <label '.@$labelAct.' for="'.$name.'">'.$title.@$marca.'</label>
			<textarea '.@$desativa.' id="'.$name.'" name="'.$name.'" '.@$character.@$required.' class="form-control" '.$rows.'>'.$value.'</textarea>
        </div>';
	if( $ckeditor != null ){
	echo "<script src=\"base/classes/ckeditor/ckeditor.js\"></script>
		<script>
		CKEDITOR.replace('$name', {
			height : 300,
			language: 'br',
			 customConfig: 'config.js'
		});
		</script>";
	}
}


function addSelectField($nome,$titulo,$required,$mixOptions,$value,$xs,$md,$multiple=null,$size=null,$readonly=null,$desativSel2=null,$default=null,$jsAction=null,$tituloSel=null ){
	if($required != null){
		$required = " required";
		$marca = '<span style="color: red;" title="Campo Obrigatório"> <b>*</b></span>';
	}
	if($xs != null){
		$xs = ' col-xs-'.$xs;
	}
	if($md != null){
		$md = ' col-md-'.$md;
	}
	if($multiple == true){
		$multiple = ' multiple="multiple"';
	}
	if($size != null){
		$size = ' size="'.$size.'"';
	}
	if($readonly != null){
		$readonly = ' readonly';
	}else{
		if( !$desativSel2 ){
			$select2 = " select2";
		}
	}
	if($jsAction != null){
		$action = ' onChange="'.$jsAction.'"';
	}
	if($titulo != null){
		$titulo = '<label>'.$titulo.'</label>'.@$marca;
	}
	if($tituloSel != null){
		$tituloSel = $tituloSel;
	}else {
		$tituloSel = '-- Selecione --';
	}
	$padrao = $value;
	if($default != null){
		$padrao = $default;
	}

	// Verifica se está sendo passando Dados SQL ou array string;
		if( $mixOptions != null ){
		if( is_array($mixOptions) ){ // Trata o SQL
			foreach ( $mixOptions as $row ){
				$dados[$row['id']] = $row['nome'];
			}
		}else{ // Trata os Array String
			$seq = explode(',', $mixOptions);
			foreach($seq as $x=>$x_value){
				$seq = explode('=', $x_value);
					@$dados[$seq[0]] .= $seq[1];
			}
		}
	}
	echo '<div class="form-group '.$xs.' '.$md.' " id="'.$nome.'_area">'.$titulo.'
			<select class="form-control'.$select2.'" name="'.$nome.'" id="'.$nome.'" '.$required.$multiple.$size.$readonly.@$paddings.@$action.' >';
	if( $multiple != true ){echo '<option value> '.$tituloSel.' </option>'; }
	foreach(@$dados as $x=>$x_value){
		echo '<option value="'.$x.'" '.( trim($padrao) == trim($x) ? " selected":"").' >'.$x_value.'</option>';
	}
	echo '</select>
		</div>';
}


public function addFileField($name,$title,$value,$cols,$desativa=null,$multiple=null,$imag=null,$required=null){

	if( $value != null ){
		$value = ' value="'.$value.'"';
		$labelAct = ' class="active"';
	}
	if( $required != null ){
		$required = " required";
		$marca = '<span style="color: #E75428;" title="Campo Obrigatório!">*</span>';
	}
	if( $multiple != null ){
		$multiple = ' multiple';
	}
	if( $imag != null ){
		if( $imag == 2 ){
			$imag = ' accept="application/pdf"';				
		}else{
			$imag = ' accept="image/*"';
		}
	}
	echo '<div class="file-field form-group col-xs-'.$cols.'" id="area_'.$name.'">
        <span>'.$title.@$marca.'</span>
        <input type="file" id="'.$name.'" name="'.$name.'" class="form-control" '.@$multiple.@$imag.$required.' >
  </div>';
	
}


function addCheckboxField($nome, $titulo, $required, $mixOptions=null, $value=null, $xs=null, $md=null, $multiple=null, $size=null, $readonly=null){
	if($required != null){
		$required = " required";
	}
	if($xs != null){
		$xs = ' col-xs-'.$xs;
	}
	if($md != null){
		$md = ' col-md-'.$md;
	}
	if($multiple == true){
		$multiple = ' multiple="multiple"';
	}
	if($size != null){
		$size = ' size="'.$size.'"';
	}
	if($readonly != null){
		$readonly = ' readonly';
	}
	echo '<div class="form-group '.$xs.' '.$md.' ">';
	if( is_string($mixOptions) ){
		$seq = explode(',', $mixOptions);
		echo '<label class="checkbox">'.$titulo;
		foreach($seq as $x=>$x_value){
			$seq = explode('=', $x_value);
			echo '<label class="checkbox"><input type="checkbox" name="'.$nome.'" value="'.$seq[0].'">'.$seq[1].'</label>';
		}
	}
	echo '</label></div>';
}

	
}
