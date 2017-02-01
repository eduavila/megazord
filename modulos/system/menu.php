<?php
if ($_SESSION ['perfilId'] != 1) {
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit ();
}
define ( 'PAG_TITLE', 'Menu do Sistema' );
define ( 'PAG_CURRENT', '?modulo=system&pag=menu' );
define ( MOD_CUR, './modulos/system/' );

$acao = getG ( 'act', 'default' );
$id = @$_GET ['id'];
getHeadPage ( PAG_TITLE, 'primary' );

switch ($acao) {
	default :
		geral ();
		break;
	
	case ('form') :
		form ( $id );
		break;
	
	case ('salvar') :
		salvar ();
		break;
}


function geral() {
	$database = new DB ();
	
	$lstBtn = '
	<button class="btn btn-sm btn-success" name="new" id="new" onclick="actionPage(\'?modulo=system&amp;pag=menu\',\'form\',false);">
		<span class="glyphicon glyphicon-plus"></span> Novo
	</button> 
	<button class="btn btn-sm btn-warning" name="edit" id="edit" onclick="actionPage(\'?modulo=system&amp;pag=menu\',\'form\',true);">
		<span class="glyphicon glyphicon-pencil"></span> Editar
	</button> 
	';
	
	$g = new TDataTables ();
	$g->table ( MOD_CUR . 'scripts/grdMenu.php', 'tabela', $lstBtn, 'html5menu', null );
	
	echo '
	<menu id="html5menu" style="display:none" type="context">
	  <command label="Novo" onclick="actionPage(\'' . PAG_CURRENT . '\',\'form\',false);"> </command>
	  <command label="Editar" onclick="actionPage(\'' . PAG_CURRENT . '\',\'form\',true);"> </command>
	</menu>
	';
}


function form($id) {
	$database = new DB ();
	if ($id) {
		$dados = $database->get_results ( "SELECT * FROM menu where id = $id" );
	} else {
		@$dados [0] ['status'] = 1;
	}
	
	$form = new Form ( 'form2', PAG_CURRENT . '&act=salvar', 'post', null );
	$form->addTextField ( 'id', 'ID: ', @$id, '1', null, true );
	$form->addTextField ( 'nome', 'Nome: ', @$dados [0] ['nome'], '3', true, null );
	$form->addTextField ( 'modulos', 'Módulo: ', @$dados [0] ['modulo'], '2', false, null );
	$form->addTextField ( 'pagina', 'Página: ', @$dados [0] ['pag'], '2', false, null );
	$form->addTextField ( 'link', 'Link: ', @$dados [0] ['link'], '4', false, null );
	$form->addTextField ( 'define', 'Define: ', @$dados [0] ['define'], '2', false, null );
	
	$target = '_blank=Blank,_top=Top,0=Null';
	$form->addSelectField ( 'target', 'Target: ', true, $target, @$dados [0] ['target'], 2, 2, null, null, null, true );
	
	$form->addTextField ( 'ordem', 'Ordem: ', @$dados [0] ['ordem'], 1, true, null );
	$form->addTextField ( 'icon', 'Icone: ', @$dados [0] ['icon'], 2, true, null );
	$form->addSelectField ( 'status', 'Status: ', true, '0=Inativo,1=Ativo', @$dados [0] ['status'], 2, 2, null, null, null, true );
	
	getFormFootDefault ( PAG_CURRENT );
	$form->closeForm ();
}


function salvar() {
	$database = new DB ();
	
	$_POST ['modulo'] = $_POST ['modulos'];
	unset ( $_POST ['modulos'] );
	
	$_POST ['pag'] = $_POST ['pagina'];
	unset ( $_POST ['pagina'] );
	
	if ($_POST ['id'] == null) {
		$query = $database->insert ( 'menu', $_POST );
		$idLast = $database->lastid ();
		
		// LOG CAD#
		if ($query) {
			$_POST2 ['data'] = date ( 'Y-m-d H:i:s' );
			$_POST2 ['ip'] = $_SERVER ['REMOTE_ADDR'];
			$_POST2 ['acao'] = 'Cadastro de Menu Painel Administardor Cod: ' . $idLast;
			$_POST2 ['usuario'] = $_SESSION ['userId'];
			
			$query = $database->insert ( 'log_sistema', $_POST2 );
		}
	} else {
		
		// LOG EDIT#
		if ($query) {
			$_POST2 ['data'] = date ( 'Y-m-d H:i:s' );
			$_POST2 ['ip'] = $_SERVER ['REMOTE_ADDR'];
			$_POST2 ['acao'] = 'Edição de Menu Painel Administardor Cod: ' . $idLast;
			$_POST2 ['usuario'] = $_SESSION ['userId'];
			
			$query = $database->insert ( 'log_sistema', $_POST2 );
		}
		
		$where = array (
				'id' => $_POST ['id'] 
		);
		$query = $database->update ( 'menu', $_POST, $where, 1 );
		$idLast = $_POST ['id'];
	}
	
	if ($query) {
		echo '<div class="alert alert-success col-xs-12"><b>Sucesso:</b> Salvo com sucesso!</div>';
	}
	echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL=' . PAG_CURRENT . '">';
}

getFootPage ();
