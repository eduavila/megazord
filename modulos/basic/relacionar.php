<?php
if($_SESSION['relacionar']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

define('PAG_TITLE','Relacionar');
define('PAG_CURRENT','?modulo=basic&pag=relacionar');
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
	
	case('vincular'):
	vincular();
	break;

	case('salvar_vinculo'):
	salvar_vinculo();
	break;	
	
	case('remover_vinculo'):
	remover_vinculo();
	break;
	
}

######################################################
##################### GRID GERAL #####################
######################################################

function geral(){
$database = new DB();

	$lstBtn = '
	<button class="btn btn-sm btn-success" name="edit" id="edit" onclick="actionPage(\'?modulo=basic&amp;pag=realacionar\',\'vincular\',true);">
		<span class="glyphicon glyphicon-plus-sign"></span> ADD Vinculos a Este
	</button>

	';
	
	$g = new TDataTables();
	$g->table(MOD_CUR.'scripts/grdLegislacao.php', 'tabela', $lstBtn,'html5menu',null);
	
	echo '
	<menu id="html5menu" style="display:none" type="context">
	  <command label="ADD Vinculos a Este" onclick="actionPage(\''.PAG_CURRENT.'\',\'vincular\',true);"> </command>
	</menu>
	';
	
	echo "<script>
			function getAnexo(id){
				sysModalBox('Modo Visualização','funcoes/visualizacao1.mdl.php?id='+id, false, 500);
			}
		</script>";

}





######################################################
################# GRID E FORM VINCULOS ###############
######################################################

function vincular(){
	$database = new DB();
	$id_pai = @$_GET['id'];

	$nome_pai= $database->get_results( "SELECT id, titulo FROM legislacao where id = $id_pai" );	
	
	echo '
	<div class="row" style="margin-right: auto; margin-left: auto; padding-left: 15px; padding-right: 15px;">
		<h3 style="margin-top: 7px;">Você esta atribuindo vínculos p/: <span class="label label-default">'; echo @$nome_pai[0]['titulo']; echo '</span></h3>
	';
	
					
					$form = new Form('form2',PAG_CURRENT.'&act=salvar_vinculo','post" enctype="multipart/form-data',null);
					$form->addHiddenField('referencia',@$id_pai);
					
					
					$listaleis = $database->get_results( "SELECT id, titulo as nome FROM legislacao where status = 1 and referencia = 0 and titulo <> '' and id <> $id_pai ORDER BY titulo ASC" );	
					$form->addSelectField('id','Leis sem Vinculo:',true,$listaleis,@$dados[0]['lei'],5,5,null);
					
					
					$listaatos = $database->get_results( "SELECT id, nome FROM atos where status = 1 ORDER BY nome ASC" );	
					$form->addSelectField('ato','Tipo de Ato: ',true,$listaatos,@$dados[0]['ato'],4,4,null,null, null,true);
					
					getFormFootDefault(PAG_CURRENT);
					$form->closeForm();
					
		
	echo '
	</div>
	<div class="row" style="margin-right: auto; margin-left: auto; padding-left: 15px; padding-right: 15px;margin-top: 25px;">
	
	<h3 style="margin-top: 7px;">Vínculos</h3>';        
	
				$dados = $database->get_results( "SELECT 
																 a.id
																,e.nome as tipo
																
																,a.referencia as ref
																,a.ato as ato
																,concat('Nº ',a.numero,'/',a.ano) as lei
																,SUBSTR(a.assunto, 1, 100) sumula
																,DATE_FORMAT( `data`, '%d/%m/%Y' ) AS `data`
																,i.nome	as status
																,concat('
																	<a href=\"?modulo=basic&pag=relacionar&act=remover_vinculo&id_pai=$id_pai&id_filho=',a.id,'\">
																		<button class=\"btn btn-danger btn-xs\" ><span class=\"glyphicon glyphicon-remove-sign\"></span> Remover Vínculo</button>
																	</a> | 
																	<a onclick=getAnexo(\"',a.id,'\") target=\"_blank\">
																		<button class=\"btn btn-info btn-xs\" ><span class=\"glyphicon glyphicon-search\"></span> Visualizar</button>
																	</a>
																')  acao  
																
														FROM legislacao a 
														
														inner join tipo e on e.id = a.tipo
														inner join status i on i.codigo = a.status
														
														WHERE a.referencia = $id_pai
					
													" );
					
					$g = new Grid('tab',$dados,false,300,true);
	
	

	
	echo "<script>
			function getAnexo(id){
				sysModalBox('Modo Visualização de Vinculo','funcoes/visualizacao1.mdl.php?id='+id, false, 500);
			}
		</script>";
	echo '
	</div>		
		';
}


######################################################
################### SALVA VINCULOS ###################
######################################################
function salvar_vinculo(){ 
$database = new DB();
	
	$id_pai = $_POST['referencia'];
	
		$where = array( 'id' => $_POST['id'] );
		$query = $database->update( 'legislacao', $_POST, $where, 1 );
		$idLast = $_POST['id'];
		
		#LOG EDIT#
		if($query){
			$_POST2['data'] = date('Y-m-d H:i:s');
			$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST2['acao'] = 'Adicionado Vinculo de Legislação Cod: '.$idLast;
			$_POST2['usuario'] = $_SESSION['userId'];
			
			$query = $database->insert( 'log_sistema', $_POST2 );
		}
		
		
	if( @$query ){
		echo '<div class="alert alert-success col-xs-12">
				<b>Sucesso:</b> Salvo com sucesso!</div>';
	}

	echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'&act=vincular&id='.$id_pai.'">';

}	


######################################################
################### SALVA VINCULOS ###################
######################################################
function remover_vinculo(){ 
$database = new DB();

	$_POST['id'] = $_GET['id_filho'];
	$id_pai = $_GET['id_pai'];
	
	
	$_POST['referencia'] = '0';
	$_POST['ato'] = 'NULL';
	
	
	
	$where = array( 'id' => $_POST['id'] );
		$query = $database->update( 'legislacao', $_POST, $where, 1 );
		$idLast = $_POST['id'];
		
		#LOG EDIT#
		if($query){
			$_POST2['data'] = date('Y-m-d H:i:s');
			$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST2['acao'] = 'Remove Vinculo de Legislação Cod: '.$idLast;
			$_POST2['usuario'] = $_SESSION['userId'];
			
			$query = $database->insert( 'log_sistema', $_POST2 );
		}
		
		
	if( @$query ){
		echo '<div class="alert alert-success col-xs-12">
				<b>Sucesso:</b> Salvo com sucesso!</div>';
	}

	echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'&act=vincular&id='.$id_pai.'">';



}

	
getFootPage();
