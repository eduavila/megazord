<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

include_once('../base/autoload_sub.php');

$classe = getG('cl');
$fct = getG('fct');
$id = getG('id');
$mod = getG('mod');

if( $mod ){
	require_once '../modulos/'.$mod.'/function/'.$classe.'.class.php';	
}

$mostra = new $classe();
$mostra->$fct($id);


