<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

class Button {
	
public function btnForm($name,$title,$type,$icon,$efect){
	if( $name != null ){
		$name = 'name="'.$name.'" id="'.$name.'"';
	}
	echo  '<button class="btn btn-sm btn-'.$efect.'" '.@$name.' '.$type .' >
		<span class="fa fa-'.$icon.'"></span> '.$title.'
	</button>';
	
}	
	
public function aBtn($name,$title,$type,$icon,$efect){
	if( $name != null ){
		$name = 'name="'.$name.'" id="'.$name.'"';
	}
	echo  '<a class="btn btn-sm btn-'.$efect.'" '.@$name.' '.$type .' >
		<span class="fa fa-'.$icon.'"></span> '.$title.'
	</a>';
	
}	

public function btnFrmJS($name,$title,$type,$icon,$efect){
	if( $name != null ){
		$name = 'name="'.$name.'" id="'.$name.'"';
	}
	return '<button class="btn btn-sm btn-'.$efect.'" '.@$name.' '.$type .' >
		<i class="fa '.$icon.'" aria-hidden="true"></i> '.$title.'
	</button>';	
}	

public function btnHref($name,$title,$action,$icon,$efect){
	if( $name != null ){
		$name = 'name="'.$name.'" id="'.$name.'"';
	}
	echo  '<a class="btn btn-sm btn-'.$efect.'" href="'.$action.'" '.@$name.'>
			<span class="fa fa-'.$icon.'"></span> '.$title.'
	</a>';
	
}

function campoPesquisaTabela($nome,$tamanho=null){
	$tamanho = ($tamanho != null  ? $tamanho : '90' );
	$xs = $tamanho/10;
	echo '<div class="col-xs-'.$xs.' col-md-'.$xs.' navbar-right">
	<form method="post" id="frm-filtro" class="form navbar-right xs" role="search" onsubmit="return false;">
	<input type="text" id="pesquisar" name="pesquisar" size="'.$tamanho.'" class="form-control" placeholder="Busca Rápida" onkeyup="convMaiusc(this)" />
	</form>';
	echo '<script src="base/js/outros.js"></script>';
	echo '</div>';
}

	
	
}




