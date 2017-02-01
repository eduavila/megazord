<?php
if( file_exists('../../../base/classes/Funcao.class.php')){
	define('BASEDir','../../../base/');
	define('Dir','../../../');
}elseif( file_exists('../../base/classes/Funcao.class.php')){
	define('BASEDir','../../base/');
	define('Dir','../../');
}elseif( file_exists('../base/classes/Funcao.class.php')){
	define('BASEDir','../base/');
	define('Dir','../../');
}elseif( file_exists('base/classes/Funcao.class.php')){
	define('BASEDir','./base/');
	define('Dir','./');
}

require_once(Dir.'includes/config.php');
require_once(BASEDir.'classes/Funcao.class.php');

function __autoload($class_name){
	$directorys = array(
	BASEDir.'classes/',
	Dir.'funcoes/'
	);
	foreach($directorys as $directory){
		if(file_exists($directory.$class_name . '.class.php')){
			require_once($directory.$class_name . '.class.php');
			return;
		}            
	}
}	



?>