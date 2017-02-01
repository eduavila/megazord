<?php
if($_SESSION['perfilId']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

require_once "./modulos/eventos/function/GPessoas.class.php";
require_once "function/FunctionsAtestado.class.php";

define('PAG_TITLE','Cadastro de Profissionais');
define('PAG_CURRENT','?modulo=atestados&pag=profissional');
define(MOD_CUR, './modulos/atestados/');

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

	case('salvar'):
	salvar();
	break;
}

function geral(){

	$database = new DB();

	$lstBtn = '
		<button class="btn btn-sm btn-info" name="voltar" id="voltar" onclick="actionPage(\'?modulo=atestados&pag=menuAtestado&named=Atestados\',\'\',false);">
			<span class="fa fa-arrow-left"></span> Voltar
		</button>
		<button class="btn btn-sm btn-success" name="new" id="new" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',false);">
			<span class="fa fa-plus"></span> Novo
		</button> 
		<button class="btn btn-sm btn-warning" name="edit" id="edit" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',true);">
			<span class="fa fa-pencil"></span> Editar
		</button> 
	';
	
	$g = new TDataTables();
	$g->table(MOD_CUR.'scripts/grdProfissionais.php', 'tabela', $lstBtn,'html5menu',null);
	
	echo '
	<menu id="html5menu" style="display:none" type="context">
	  <command label="Novo" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',false);"> </command>
	  <command label="Editar" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',true);"> </command>
	</menu>
	';
}

function form($id){

	$database = new DB();
	$form = new Form('form1',PAG_CURRENT.'&act=salvar','post',null);
	$form->addHiddenField('id', $id);
	
	if($id){
		$dados = $database->get_results("SELECT * FROM profissional where id = $id");
	}
	
	$form->addTextField('nome','Nome: ',@$dados[0]['nome'],8,true,null);
	$form->addTextField('registro','Registro: ',@$dados[0]['registro'],4,true,null);
	
	getFormFootDefault(PAG_CURRENT);
	$form->closeForm();
	
}

function salvar(){

	$database = new DB();

	if($_POST['id'] == null){
		$query = $database->insert('profissional', $_POST);
		$idLast = $database->lastid();
	}
	else {
		$where = array('id' => $_POST['id']);
		$query = $database->update('profissional', $_POST, $where, 1);
		$idLast = $_POST['id'];
	}

	// Log da ação
	Logger($_POST);
	
	if( $query ){
		echo '<div class="alert alert-success" style="position:relative">
				<i class="fa fa-2x fa-spinner fa-pulse"></i></i>
				<span style="position:absolute; top:100%; margin-top:-40px"><b>&nbsp&nbspSucesso:</b> Salvo com sucesso!</span>
			  </div>';
	}

	echo '<META HTTP-EQUIV="Refresh" CONTENT="2;URL='.PAG_CURRENT.'">';
}

getFootPage();
