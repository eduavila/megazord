<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

if($_SESSION['perfilId']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

require_once 'function/GPessoas.class.php';

define(PAG_TITLE,'Tipo de Eventos');
define(PAG_CURRENT, '?modulo=eventos&pag=horasExtraTipo');
define(MOD_CUR, './modulos/eventos/');

$acao = getG('act','geral');
$id = getG('id');
getHeadPage(PAG_TITLE,'warning');

switch ($acao){

	case "geral";
	geral();
	default;
	break;
	
	case "salvar";
	salvar();
	break;

	case "form";
	form( @$id );
	break;
}

function geral(){
$btn = new Button();
$lstBtn = $btn->btnFrmJS('btnNew', 'Novo', "onclick=\"actionPage('".PAG_CURRENT."','form',false);\"", 'fa-plus', 'success').
	$btn->btnFrmJS('btnEdit', 'Editar', "onclick=\"actionPage('".PAG_CURRENT."','form',true);\"", 'fa-pencil', 'warning');
	
$g = new TDataTables();
$g->table(MOD_CUR.'function/grdHoraExtraTipo.php', 'tab', $lstBtn, 'html5menu',null);

echo '<menu id="html5menu" style="display:none" type="context">
  <command label="Novo" icon="new" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',false);"></command>
  <command label="Editar" icon="edit" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',true);"></command>
</menu>';

}


// Função Único formulario na página
function form( $id=null ){
$db = new DB();
if( $id != null ){
	$dados = $db->get_results('select * from gp_horas_tipo where id = '.$id);
}

aDiv(null,'12','well2', null);
$form = new Form('form1',PAG_CURRENT.'&act=salvar','post',null);
	$form->addHiddenField('id',$id);
	
	$form->addTextField( 'codigo', 'Código:', @$dados[0]['codigo'],1, true, null, 'Cód. Fiorili');
	$form->addTextField( 'nome', 'Nome:', @$dados[0]['nome'], 6, true , null, 'Descrição do historico do evento');

	$form->addSelectField('tipo','Tipo: ',true,'H=Hora,V=Valor',$dados[0]['tipo'],1,1,null,null,null,true,null,null);
	$form->addSelectField('status','Status:',true,"1=Ativo,0=Inativo",$dados[0]['status'],1,1,null,null,null,true,null,null);
	
getFormFootDefault(PAG_CURRENT);
$form->closeform();
cDiv();
}


// Função para Salvar dos Dados
function salvar(){
$db = new DB();	
Logger($_POST);	
	if( $db->insert( 'gp_horas_tipo', $_POST, 'id' ) ){
		msgTelaPost(1, null, PAG_CURRENT);
	}else{
		msgTelaPost(2, null, PAG_CURRENT, 5);
	}	
}


