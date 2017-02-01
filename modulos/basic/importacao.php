<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

if($_SESSION['importacao']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

define('PAG_TITLE','Importação');
define('PAG_CURRENT','?modulo=basic&pag=importacao');
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
	
	
	$form = new Form('form2',PAG_CURRENT.'&act=salvar','post" enctype="multipart/form-data',null);
		
		
		$form->addTextField('pasta','Pasta p/ importacao: ',@$dados[0]['pasta'],'4',true,null,'importar/');
		
		$listatipos = $database->get_results( "SELECT id, nome FROM tipo where status = 1 ORDER BY nome ASC" );	
		$form->addSelectField('tipo','Tipo: ',true,$listatipos,@$dados[0]['tipo'],2,5,null,null, null,true);
		
		
		
		getFormFootDefault(PAG_CURRENT);
	$form->closeForm();
	
}



######################################################
################# SALVA IMPORTACAO ###################
######################################################
function salvar(){
	
$database = new DB();
		
	$tipo = $_POST['tipo'];
	$pasta = $_POST['pasta'];
	$pasta = 'importar/'.$pasta.'/';
	
	$arquivos = glob("$pasta{*.html,*.HTML}", GLOB_BRACE);
	
	foreach($arquivos as $k => $files){
		$arq[$k] = $files;
		
		$db = new DB();
		$html = file_get_contents($files);
		$html = preg_replace("/<img[^>]+\>/i", "", $html);
		$dados = explode('<br>', $html);
		$title = explode('<h2>', $dados[0])[1];
		$assunto = strip_tags($dados[1]);
		
		// Remove tags html inicial
		$pos = strpos($html, '<h2>');
		$text = substr($html, $pos);
		$text = preg_replace("/(?<=href=(\"|'))[^\"']+(?=(\"|'))/",'#',$text);
		 
		$insert = array(
			"tipo" => $tipo,
			"data" => date('Y-m-d'),
			"referencia" => 0,
			"titulo" => utf8_encode(strip_tags($title)),
			"assunto" => utf8_encode(strip_tags($assunto)),
			"texto" => utf8_encode($text)
		);
		 
		$query = $db->insert( 'legislacao2', $insert );
		 
		 
		if( @$query ){
			echo '<small class="label label-success"><i class="fa fa-check-square"></i> Sucesso</small> '; echo $title;  echo '<br />';
		}
		
			
	}
	
	/*
	 $db = new DB();
	 $html = file_get_contents('importar/HTML_Lei-Complementar-lucas-do-rio-verde/Lei-Complementar-153-2015.html');
	 $html = preg_replace("/<img[^>]+\>/i", "", $html);
	 $dados = explode('<br>', $html);
	 $title = explode('<h2>', $dados[0])[1];
	 $assunto = strip_tags($dados[1]);
	 // Remove tags html inicial
	 $pos = strpos($html, '<h2>');
	 $text = substr($html, $pos);
	 
	 $insert = array(
			"tipo" => 0,
			"data" => date('Y-m-d'),
			"referencia" => 0,
			"titulo" => utf8_encode($title),
			"assunto" => utf8_encode($assunto),
			"texto" => utf8_encode($text)
		);
	$query = $db->insert( 'legislacao', $insert );
	if( @$query ){
			echo '<small class="label label-success"><i class="fa fa-check-square"></i> Sucesso</small> '; echo $title;  echo '<br />';
	}
	*/
	
}


	
getFootPage();
