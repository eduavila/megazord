<?php
if($_SESSION['perfilId']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

define('PAG_TITLE','Parâmetros Gerais do Sistema');
define('PAG_CURRENT','?modulo=system&pag=parametro');
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
	
	case('salvar'):
	salvar();
	break;	
}

function geral(){
$database = new DB();

	$lstBtn = '
	<button class="btn btn-sm btn-success" name="new" id="new" onclick="actionPage(\'?modulo=system&amp;pag=parametro\',\'form\',false);">
		<span class="glyphicon glyphicon-plus"></span> Novo
	</button> 
	<button class="btn btn-sm btn-warning" name="edit" id="edit" onclick="actionPage(\'?modulo=system&amp;pag=parametro\',\'form\',true);">
		<span class="glyphicon glyphicon-pencil"></span> Editar
	</button> 
	';
	
	$g = new TDataTables();
	$g->table(MOD_CUR.'scripts/grdParametro.php', 'tabela', $lstBtn,'html5menu',null);
	
	echo '
	<menu id="html5menu" style="display:none" type="context">
	  <command label="Novo" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',false);"> </command>
	  <command label="Editar" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',true);"> </command>
	</menu>
	';

}


function form($id){
$database = new DB();

	if( $id ){
	$dados = $database->get_results( "SELECT * FROM parametro where id = $id" );
	}else{
		@$dados[0]['status'] = 1;
	}
		
	$form = new Form('form1',PAG_CURRENT.'&act=salvar','post',null);
	
	$form->addTextField('id','ID: ',$id,'1',null,true);
	$form->addTextField('campo','Campo: ',@$dados[0]['campo'],'7',true,null);
	
	$form->addSelectField('status','Status: ',true,'0=Inativo,1=Ativo',@$dados[0]['status'],2,2,null,null,null,true);

	$form->addTextArea('valor','Valor: ',@$dados[0]['valor'],'12',1024,true,null);


getFormFootDefault(PAG_CURRENT);
$form->closeForm();

}


function salvar(){
$database = new DB();

if( $_POST['id'] == null ){
	$query = $database->insert( 'parametro', $_POST );
	$idLast = $database->lastid();
}else{
	$where = array( 'id' => $_POST['id'] );
	$query = $database->update( 'parametro', $_POST, $where, 1 );
	$idLast = $_POST['id'];
}

if( $query ){
	echo '<div class="alert alert-success"><span class="white-text">
				<b>Sucesso:</b> Salvo com sucesso!</span></div>';
}

echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'">';
}

getFootPage();
