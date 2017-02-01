<?php
if($_SESSION['atos']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

define('PAG_TITLE','Tipos de Atos');
define('PAG_CURRENT','?modulo=basic&pag=atos');
define(MOD_CUR, './modulos/basic/');

$acao = getG('act','default');
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

######################################################
##################### GRID GERAL #####################
######################################################

function geral(){
$database = new DB();

	$lstBtn = '
	<button class="btn btn-sm btn-success" name="new" id="new" onclick="actionPage(\'?modulo=basic&amp;pag=atos\',\'form\',false);">
		<span class="glyphicon glyphicon-plus"></span> Novo
	</button> 
	<button class="btn btn-sm btn-warning" name="edit" id="edit" onclick="actionPage(\'?modulo=basic&amp;pag=atos\',\'form\',true);">
		<span class="glyphicon glyphicon-pencil"></span> Editar
	</button> 
	';
	
	$g = new TDataTables();
	$g->table(MOD_CUR.'scripts/grdAtos.php', 'tabela', $lstBtn,'html5menu',null);
	
	echo '
	<menu id="html5menu" style="display:none" type="context">
	  <command label="Novo" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',false);"> </command>
	  <command label="Editar" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',true);"> </command>
	</menu>
	';
	
}


######################################################
################ FORMULARIO PRINCIPAL ################
######################################################

function form($id){
	$database = new DB();
	if( $id ){
		$dados = $database->get_results( "SELECT * FROM atos where id = $id" );
		
	}else{
		@$dados[0]['status'] = 1;
	}

	$form = new Form('form2',PAG_CURRENT.'&act=salvar','post" enctype="multipart/form-data',null);

	$form->addTextField('id','ID: ',@$id,'1',null,true);
	
	
	
	$form->addTextField('nome','Nome: ',@$dados[0]['nome'],'6',true,null);
	
	$form->addSelectField('status','Status: ',true,'0=Inativo,1=Ativo',@$dados[0]['status'],2,2,null,null,null,true);

	getFormFootDefault(PAG_CURRENT);
	$form->closeForm();

}



######################################################
############### SALVA E EDITA O ATO ################
######################################################
function salvar(){
$database = new DB();

	if( $_POST['id'] == null ){
		
		$query = $database->insert( 'atos', $_POST );
		$idLast = $database->lastid();
		
		#LOG CAD#
		if($query){
			$_POST2['data'] = date('Y-m-d H:i:s');
			$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST2['acao'] = 'Cadastro de Tipos de Ato Cod: '.$idLast;
			$_POST2['usuario'] = $_SESSION['userId'];
			
			$query = $database->insert( 'log_sistema', $_POST2 );
		}
		
	}else{
		$where = array( 'id' => $_POST['id'] );
		$query = $database->update( 'atos', $_POST, $where, 1 );
		$idLast = $_POST['id'];
		
		#LOG EDIT#
		if($query){
			$_POST2['data'] = date('Y-m-d H:i:s');
			$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST2['acao'] = 'Edição de Tipos de Ato Cod: '.$idLast;
			$_POST2['usuario'] = $_SESSION['userId'];
			
			$query = $database->insert( 'log_sistema', $_POST2 );
		}
		
		
	}

	if( @$query ){
		echo '<div class="alert alert-success col-xs-12">
				<b>Sucesso:</b> Salvo com sucesso!</div>';
	}

	echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'">';

}


	
getFootPage();
