<?php 
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

class FNCGeral {
	
function visIMGDir($image){
	$url = urlencode($image);
	echo '<a href="principal.php?modulo=system&pag=images&act=remove&id='.$url.'" id="remove" class="btn btn-danger btn-sm">
			<span class="glyphicon glyphicon-remove" aria-hidden="true"> </span>Apagar</a>
			<input type="text" value="'.$image.'" size="100">';
	echo '<div class="col-xs-12 well "><img src="'.$image.'" width="100%"></div>';
}
	

}