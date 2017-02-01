<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);
if($_SESSION['perfilId']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}
require_once "./modulos/eventos/function/GPessoas.class.php";

define('PAG_TITLE','Cadastro de Usuarios');
define('PAG_CURRENT','?modulo=system&pag=usuario');
define(MOD_CUR, './modulos/system/');

$acao = (@$_GET['act'] == null ? "default": $_GET['act']);
$id = @$_GET['id'];
getHeadPage(PAG_TITLE,'primary');

switch ($acao) {
	default:
	geral();
	break;

	case('form'):
	form($id);
	break;

	case('formSenha'):
	formSenha($id);
	break;

	case('salvar'):
	salvar();
	break;
	
	case('salvarSenhaAdmin'):
	salvarSenhaAdmin();
	break;
	
}

function geral(){
$database = new DB();

	$lstBtn = '
	<button class="btn btn-sm btn-success" name="new" id="new" onclick="actionPage(\'?modulo=system&amp;pag=usuario\',\'form\',false);">
		<span class="glyphicon glyphicon-plus"></span> Novo
	</button> 
	<button class="btn btn-sm btn-warning" name="edit" id="edit" onclick="actionPage(\'?modulo=system&amp;pag=usuario\',\'form\',true);">
		<span class="glyphicon glyphicon-pencil"></span> Editar
	</button> 
	<button class="btn btn-sm btn-danger" name="edit" id="edit" onclick="actionPage(\'?modulo=system&amp;pag=usuario\',\'formSenha\',true);">
		<span class="glyphicon glyphicon-lock"></span> Alterar Senha
	</button> 
	';
	
	$g = new TDataTables();
	$g->table(MOD_CUR.'scripts/grdUsuarios.php', 'tabela', $lstBtn,'html5menu',null);
	
	echo '
	<menu id="html5menu" style="display:none" type="context">
	  <command label="Novo" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',false);"> </command>
	  <command label="Editar" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',true);"> </command>
	  <hr>
	  <command label="Alterar Senha" onclick="actionPage(\''.PAG_CURRENT.'\',\'formSenha\',true);"> </command>
	</menu>
	';



}


function form($id){
$database = new DB();

	if( $id ){
	$dados = $database->get_results( "SELECT * FROM usuario where id = $id" );
	}

	$form = new Form('form1',PAG_CURRENT.'&act=salvar','post',null);
	$form->addTextField('id','ID: ',$id,'1',null,true);
	$form->addTextField('nome','Nome: ',@$dados[0]['nome'],'5',true,null);
	$form->addTextField('login','Login: ',@$dados[0]['login'],'5',true,null);
	
	if( $id == null ){
		$form->addPassField('senha','Senha: ',null,'4',true,null);
	}
	
	$listaPerfil = $database->get_results( "SELECT id, nome FROM perfil" );
		$form->addSelectField('perfil','Perfil: ',true,$listaPerfil,@$dados[0]['perfil'],2,2,null,null, null,true);
		
	$orgaoLista = GPessoas::selectListDpto();
		$form->addSelectField('dpto','Secretaria: ',true,$orgaoLista,@$dados[0]['dpto'],4,4,null,null,null,true,null,null,false);
		
	$form->addSelectField('status','Status: ',true,'1=Ativo, 0=Inativo',@$dados[0]['status'],2,2,null,null, null,true);
	
	$form->addSelectField('alt_senha','Alterar Senha: ',true,'1=Sim, 0=Não',@$dados[0]['alt_senha'],2,2,null,null, null,true);

getFormFootDefault(PAG_CURRENT);
$form->closeForm();
	
}


function formSenha($id){
$database = new DB();

	$dados = $database->get_results( "SELECT * FROM usuario where id = $id" );
	
	echo '<h4>Alteração de Senha do Usuário: <b>'.$dados[0]['nome'].'</b></h4>';
		
	$form = new Form('form1',PAG_CURRENT.'&act=salvarSenhaAdmin','post',null);
	$form->addTextField('id','ID: ',$id,'1',null,true);
	$form->addPassField('senha','Senha: ',null,'4',true,null);

	getFormFootDefault(PAG_CURRENT);
	$form->closeForm();
	
}


function salvar(){
$database = new DB();

	if( $_POST['senha'] ){
		$_POST['senha'] = md5($_POST['senha']);
	}
	
	if( $_POST['id'] == null ){
		$query = $database->insert( 'usuario', $_POST );
		$idLast = $database->lastid();
		
		#LOG CAD#
		if($query){
			$_POST2['data'] = date('Y-m-d H:i:s');
			$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST2['acao'] = 'Cadastro de Usuario Cod: '.$idLast;
			$_POST2['usuario'] = $_SESSION['userId'];
			
			$query = $database->insert( 'log_sistema', $_POST2 );
		}
		
	}else{
		$where = array( 'id' => $_POST['id'] );
		$query = $database->update( 'usuario', $_POST, $where, 1 );
		$idLast = $_POST['id'];
		
		#LOG EDIT#
		if($query){
			$_POST2['data'] = date('Y-m-d H:i:s');
			$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST2['acao'] = 'Edição de Usuario Cod: '.$idLast;
			$_POST2['usuario'] = $_SESSION['userId'];
			
			$query = $database->insert( 'log_sistema', $_POST2 );
		}
		
	}

	if( $query ){
		echo '<div class="alert alert-success">
					<b>Sucesso:</b> Salvo com sucesso!</div>';
	}

	echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'">';
	
}


function salvarSenhaAdmin(){
$database = new DB();

	if( $_POST['senha'] ){
		$_POST['senha'] = md5($_POST['senha']);
	}
	
		$where = array( 'id' => $_POST['id'] );
		$query = $database->update( 'usuario', $_POST, $where, 1 );
		$idLast = $_POST['id'];
		
		#LOG EDIT#
		if($query){
			$_POST2['data'] = date('Y-m-d H:i:s');
			$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST2['acao'] = 'Altera Senha de Usuario Cod: '.$idLast;
			$_POST2['usuario'] = $_SESSION['userId'];
			
			$query = $database->insert( 'log_sistema', $_POST2 );
		}
		
	
	if( $query ){
		echo '<div class="alert alert-success">
					<b>Sucesso:</b> Salvo com sucesso!</div>';
	}

	echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'">';
	
}

getFootPage();
