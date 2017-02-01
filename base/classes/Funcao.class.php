<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

date_default_timezone_set('America/Cuiaba');
setlocale(LC_MONETARY, 'en_US');

function getS($campo, $padrao = '') {
	return isset ( $_SESSION ["$campo"] ) ? $_SESSION ["$campo"] : $padrao;
}

function getG($campo, $padrao = '') {
	return isset ( $_GET ["$campo"] ) ? $_GET ["$campo"] : $padrao;
}

function getR($campo, $padrao = '') {
	return isset ( $_REQUEST ["$campo"] ) ? $_REQUEST ["$campo"] : $padrao;
}

function postP($campo, $padrao = '') {
	return isset ( $_POST ["$campo"] ) ? $_POST ["$campo"] : $padrao;
}

function getP($campo, $padrao = '') {
	return isset ( $_POST ["$campo"] ) ? $_POST ["$campo"] : $padrao;
}

function getParametro($name){
	$db = new DB();
	$dados = $db->get_results('select valor from parametro where campo = "'.$name.'" and status = 1');
	return $dados[0]['valor'];
}

function getInfoSessaoSSID($ssid){
	$db = new DB();
	$dados = $db->get_results('select * from log_sessao where session = "'.$ssid.'" and status = 1');
	foreach($dados as $key => $arr) {
		$data = $arr;
	}
	return $data;
}

function printR($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function getHeadPage( $title, $tipo ){
	/* title="'.$title.'"*/
	echo '
		<div class="box box-'.$tipo.'">
			<div class="box-body">
		';
}

function aDiv($nome,$xs,$class,$role,$content=null){
	if( $nome != null ){ $nome = 'id="'.$nome.'"'; }
	if( $xs != null ){ $xs = "col-xs-".$xs." " ; }
	if( $role != null ){ $role = " role=\"$role\""; }
	echo '<div '.@$nome.' '.@$role.' class="'.$xs.@$class.'">';
	if( $content != null){
		echo $content.'</div>';
	}
}


function cDiv(){
	echo '</div>';
}

function getFootPage( $footer=null ){
	echo '</div>';
	if( $footer ){
		echo '<div class="panel-footer">'.$footer.'</div>';
	}
	echo '</div>';
}


function getFormFootDefault( $page ){
	echo '<div class="col-xs-12">';
		$btn = new Button();
		$btn->btnHref(null,'Voltar',$page.'&act=geral','arrow-left','info');
		echo'&nbsp;';		 
		$btn->btnForm(null,'Salvar','type="submit"','save','success');
	echo '</div>';
}

function dataConvBR($data){
	$datah = explode(" ",$data);
	$data = implode("/",array_reverse(explode("-",$datah[0])));
	if( count($datah) == 2 ) {
		$data = $data.' '.$datah[1];
	}
	return $data;
}


function dataConvEN($data){
	$datah = explode(" ",$data);
	$data = implode("-",array_reverse(explode("/",$datah[0])));
	if( count($datah) == 2 ) {
		$data = $data.' '.$datah[1];
	}
	return $data;
}

function limpa_str($string) {
	$string = preg_replace("/[Ã�Ã€Ã‚ÃƒÃ„Ã¡Ã Ã¢Ã£Ã¤]/", "a", $string);
    $string = preg_replace("/[Ã‰ÃˆÃŠÃ©Ã¨Ãª]/", "e", $string);
    $string = preg_replace("/[Ã�ÃŒÃ­Ã¬]/", "i", $string);
    $string = preg_replace("/[Ã“Ã’Ã”Ã•Ã–Ã³Ã²Ã´ÃµÃ¶]/", "o", $string);
    $string = preg_replace("/[ÃšÃ™ÃœÃºÃ¹Ã¼]/", "u", $string);
    $string = preg_replace("/[Ã‡Ã§]/", "c", $string);
//echo $string;
    $string = preg_replace("/[][><}{)(:;,ÂºÂª!?*%~^`&#@]/", "", $string);
    $string = preg_replace("/ /", "_", $string);
    $string = strtolower($string);
    return $string;
}


function createDir( $dirname ){
	if ( !file_exists( $dirname ) ){
		mkdir( $dirname, 0777 );
		echo "O diretorio $dirname foi criado com sucesso.";
		//exit;
	}else{
		echo "Diretorio $dirname ja existe.";
	}

}

function msgTelaPost( $tipo, $msg, $pag, $time=2 ){
	if( $tipo == 1 ){
	echo '<div class="alert alert-success" style="position:relative">
		<i class="fa fa-2x fa-spinner fa-pulse"></i></i>
		<span style="position:absolute; top:100%; margin-top:-40px"><b>&nbsp&nbspSucesso:</b> Salvo com sucesso!</span>
	  </div>';
	}else{
	echo '<div class="alert alert-danger" style="position:relative">
		<i class="fa fa-2x fa-spinner fa-pulse"></i></i>
		<span style="position:absolute; top:100%; margin-top:-40px"><b>&nbsp;&nbsp;Falha:</b> Registro não salvo!</span>
	  </div>';			
	}
	echo @$msg;
	echo '<META HTTP-EQUIV="Refresh" CONTENT="'.$time.';URL='.$pag.'">';
}


function Logger($msg,$tela=null){
$db = new DB();

	// Log de Alterações do sistema.
	$data = date("m-Y");
	$hora = date("Y-m-d H:i:s");
	if( $_SESSION['ip'] == null ){
		if(isset ($_SERVER ['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
		}elseif(isset ($_SERVER ['HTTP_X_REAL_IP'])){
			$ip = $_SERVER ['HTTP_X_REAL_IP'];
		}else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	}else {
		$ip = $_SESSION['ip'];
	}
	
	if( $tela != null ){
		$tela = 'origen: '.$tela.'; ';
	}else{
		$tela = $_SERVER['REQUEST_URI'];
	}
	if( $_SESSION['userId'] != null ){
		$user = $_SESSION['userId'];
	}elseif(@$msg['user_id'] != null ){
		$user = @$msg['user_id'];
	}else{
		$user = '0';
	}
	//Nome do arquivo:
	$arquivo = "tmp/log_$data.txt";
	if( is_array($msg) ){
		$msg = json_encode($msg);
		$msg = str_replace('","','", "',$msg);
	}

	//Texto a ser impresso no log:
	$texto = "Date: [$hora] | Ip: [$ip] | User: [$user - $_SESSION[userNome]] | Source: [ $tela ] | Log: [ $msg ] \n";

	if( file_exists('../base')){
		if( !file_exists('../base/tmp/')){
			umask(0);
			mkdir("../base/tmp/", 0777, true);
		}
		$arquivo = '../base/'.$arquivo;
	}else{
		if( !file_exists('./base/tmp/')){
			umask(0);
			mkdir("./base/tmp/", 0777, true);
		}
		$arquivo = './base/'.$arquivo;
	}
	// Gravando log no arquivo
	$manipular = fopen($arquivo, "a+b");
	fwrite($manipular, $texto);
	fclose($manipular);

	// Gravar no Banco de Dados
	$save['data'] = $hora; 
	$save['sessao'] = getS('sessionId');
	$save['ip'] = $ip;
	$save['tela'] = $tela;
	$save['usuario'] = $user;
	$save['acao'] = $texto;
	
	$query = $db->insert( 'log_sistema', $save );
}

