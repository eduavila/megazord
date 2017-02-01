<?php
if($_SESSION['perfilId']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

define('PAG_TITLE','Departamentos');
define('PAG_CURRENT','?modulo=system&pag=dpto');
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

$btn = new Button();
$lstBtn = $btn->btnFrmJS('btnNew', 'Novo', "onclick=\"actionPage('".PAG_CURRENT."','form',false);\"", 'fa-plus', 'success').
		$btn->btnFrmJS('btnEdit', 'Editar', "onclick=\"actionPage('".PAG_CURRENT."','form',true);\"", 'fa-pencil', 'warning');
	
	$g = new TDataTables();
	$g->table(MOD_CUR.'scripts/grdDpto.php', 'tabela', $lstBtn,'html5menu',null);
	
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
$dados = $database->get_results( "SELECT * FROM tab_dpto where id = $id" );
}	

$form = new Form('form1',PAG_CURRENT.'&act=salvar','post',null);
$form->addTextField('id','ID: ',$id,'1',null,true);
$form->addTextField('sigla','Sigla: ',@$dados[0]['sigla'],1,true,null);
$form->addTextField('nome','Nome: ',@$dados[0]['nome'],'5',true,null);

$listaResp = $database->get_results( "SELECT id, nome FROM tab_pessoa" );
$form->addSelectField('resp_id','Responsavel: ',null,$listaResp,@$dados[0]['resp_id'],4,4,null,null, null,false);
$form->addSelectField('status','Status: ',true,'1=Ativo,0=Inativo',@$dados[0]['status'],1,1,null,null, null,true);

getFormFootDefault(PAG_CURRENT);

$form->closeForm();
	
}



function salvar(){
$database = new DB();

if( $_POST['id'] == null ){
	$query = $database->insert( 'tab_dpto', $_POST );
	$idLast = $database->lastid();
}else{
	$where = array( 'id' => $_POST['id'] );
	$query = $database->update( 'tab_dpto', $_POST, $where, 1 );
	$idLast = $_POST['id'];
}

if( $query ){
	echo '<div class="alert alert-success">
				<b>Sucesso:</b> Salvo com sucesso!</div>';
}

echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'">';
}

getFootPage();
