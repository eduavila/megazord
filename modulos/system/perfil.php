<?php
if($_SESSION['perfilId']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

define('PAG_TITLE','Perfil');
define('PAG_CURRENT','?modulo=system&pag=perfil');
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

	case('formPermissoes'):
	formPermissoes($id);
	break;

	case('salvar'):
	salvar();
	break;
	
	case('salvarPermisssoes'):
	salvarPermisssoes();
	break;
	
	
}

function geral(){
$database = new DB();

	$lstBtn = '
	<button class="btn btn-sm btn-success" name="new" id="new" onclick="actionPage(\'?modulo=system&amp;pag=perfil\',\'form\',false);">
		<span class="glyphicon glyphicon-plus"></span> Novo
	</button> 
	<button class="btn btn-sm btn-warning" name="edit" id="edit" onclick="actionPage(\'?modulo=system&amp;pag=perfil\',\'form\',true);">
		<span class="glyphicon glyphicon-pencil"></span> Editar
	</button> 
	<button class="btn btn-sm btn-primary" name="edit" id="edit" onclick="actionPage(\'?modulo=system&amp;pag=perfil\',\'formPermissoes\',true);">
		<span class="glyphicon glyphicon-lock"></span> Permissões
	</button> 
	';
	
	$g = new TDataTables();
	$g->table(MOD_CUR.'scripts/grdPerfil.php', 'tabela', $lstBtn,'html5menu',null);
	
	echo '
	<menu id="html5menu" style="display:none" type="context">
	  <command label="Novo" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',false);"> </command>
	  <command label="Editar" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',true);"> </command>
	  <hr>
	  <command label="Permissões" onclick="actionPage(\''.PAG_CURRENT.'\',\'formPermissoes\',true);"> </command>
	</menu>
	';



}


function form($id){
$database = new DB();
if( $id ){
$dados = $database->get_results( "SELECT * FROM perfil where id = $id" );
}	


$form = new Form('form1',PAG_CURRENT.'&act=salvar','post',null);
$form->addTextField('id','ID: ',$id,'1',null,true);
$form->addTextField('nome','Nome: ',@$dados[0]['nome'],'5',true,null);




getFormFootDefault(PAG_CURRENT);

$form->closeForm();
	
}


function formPermissoes($id){
$database = new DB();
	$perfil = $database->get_results( "SELECT nome FROM perfil where id = $id"  );
	
	echo '<h3>Perfil: '.@$perfil[0]['nome'].'</h3>';
	
	$menu = $database->get_results( "SELECT * FROM menu where status = 1 ORDER BY ordem ASC"  );
	
	
		$form = new Form('form1',PAG_CURRENT.'&act=salvarPermisssoes','post',null);
		echo '<div class="col-xs-12 well2">';
		echo '<input type="hidden" name="perfil" value="'.$id.'">';
		
		for ($col = 0; $col<count($menu); $col++){
			$id_menu = @$menu[$col]['id'];
			$nome_menu = @$menu[$col]['nome'];
			$id_perfil = $id;			
			$liberado = $database->get_results( "SELECT * FROM perfil_menu where id_perfil = $id_perfil and id_menu = $id_menu" );
			
			echo '<div class="col-md-3">			
			<label>			
				<input type="checkbox" name="itemAcesso[]" value="'.$id_menu.'"';			
				if( $liberado[0] != null ){ echo ' checked'; }			
			echo '/> '.$nome_menu.'</label>			
			</div>';
		}
		echo '<br />
		</div>';
		getFormFootDefault(PAG_CURRENT);

		$form->closeForm();
	
}

function salvarPermisssoes(){
	$database = new DB();
	
	$id = $_POST['perfil'];
	$item = $_POST['itemAcesso'];
	$del = 0;
	
		foreach( $item as $itemKey => $itemAce){
			$consulta = $database->get_results( "SELECT * FROM perfil_menu where id_perfil = $id and id_menu = $itemAce" );
			$existe = count($consulta);
			if( @$existe == null ){
				$where = array('id_perfil' => $id, 'id_menu' => $itemAce);	
				$insert = $database->insert( 'perfil_menu', $where );
			}
			if ($del == 0) { 
				$del = "$itemAce";
			} else { 
				$del = $del.","."$itemAce";
			}
		}
		
	$where = array('id_perfil' => $id.' and id_menu NOT IN ('.$del.')' );
	$delete = $database->delete2( 'perfil_menu', $where );	
	
if( $insert or $delete){
	echo '<div class="alert alert-success">
				<b>Sucesso:</b> Salvo com sucesso!</div>';
}

echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'">';
	
}





function salvar(){
$database = new DB();

if( $_POST['id'] == null ){
	$query = $database->insert( 'perfil', $_POST );
	$idLast = $database->lastid();
}else{
	$where = array( 'id' => $_POST['id'] );
	$query = $database->update( 'perfil', $_POST, $where, 1 );
	$idLast = $_POST['id'];
}

if( $query ){
	echo '<div class="alert alert-success">
				<b>Sucesso:</b> Salvo com sucesso!</div>';
}

echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'">';
}

getFootPage();
