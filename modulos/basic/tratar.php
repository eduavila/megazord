<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

if($_SESSION['importacao']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

define('PAG_TITLE','Importação');
define('PAG_CURRENT','?modulo=basic&pag=tratar');
define(MOD_CUR, './modulos/basic/');

$acao = getG('act','default');
$id = @$_GET['id'];
getHeadPage(PAG_TITLE,'primary');

switch ($acao) {
	default:
	geral();
	break;

	case('salvar'):
	salvar();
	break;	

}

######################################################
####################### GERAL #######################
######################################################

function geral(){
$database = new DB();
	$dados = $database->get_results( "SELECT id, titulo FROM legislacao2 where ano = 0 ");	
echo count($dados);
foreach($dados as $row){
	//print_r($row);
	$titulo = explode(',',$row['titulo']);
	$titulo2 = end(@explode(' ',$titulo[0]));
	$dataN = $titulo[1];
	
	
	echo $titulo2.'<br>';
	
	
	$dataAno = str_replace(".", "", $titulo2);
	
	//echo $dataN;
	//$dataAno = preg_replace(".", "", $dataAno);
	
	
	
	echo $dataAno;
	$dataAno = substr($dataAno, -4);
	
	
	//echo $dataAno.'<br>';
	echo $row['titulo'].' - '. $dataAno.'<br>';
	//echo $titulo2.'<br>';;
	//echo $titulo2.' - '.$dataAno.'<br>';
	
	
	$where = array( 'id' => $row['id'] );
	//$query = $database->update( 'legislacao2', array( 'numero' => $titulo2,'data_pub' => $dataN, ), $where, 1 );
	$query = $database->update( 'legislacao2', array( 'ano' => $dataAno), $where, 1 );
	if($query){
		echo 'Ok <br>';
	}
}	
	
}


	
getFootPage();
