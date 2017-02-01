<?php
if($_SESSION['legislacao']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

define('PAG_TITLE','Legislação');
define('PAG_CURRENT','?modulo=basic&pag=legislacao');
define(MOD_CUR, './modulos/basic/');

$acao = getG('act','default');
$id = @$_GET['id'];
getHeadPage(PAG_TITLE,'primary');

switch ($acao) {
	default:
	geral();
	break;
	
	case('selecione'):
	selecione($id);
	break;
	
	case('selecione2'):
	selecione2($id);
	break;

	case('form'):
	form($id);
	break;
	
	case('form2'):
	form2($id);
	break;

	case('salvar'):
	salvar();
	break;	
	
	case('vincular'):
	vincular();
	break;
	
	case('vincular2'):
	vincular2();
	break;
	
	case('salvar_vinculo'):
	salvar_vinculo();
	break;
	

}

######################################################
##################### SELEÇÃO #####################
######################################################

function selecione($id){
	$database = new DB();
	
	if($id){
		$dados = $database->get_results( "SELECT * FROM legislacao where id = $id" );
		$arquivo = $dados[0]['arquivo'];
		
		if($arquivo){
			echo '<meta http-equiv="refresh" content=0;url="'.PAG_CURRENT.'&act=form2&id='.$id.'">';
		}else{
			echo '<meta http-equiv="refresh" content=0;url="'.PAG_CURRENT.'&act=form&id='.$id.'">';
			
		}
		
	}else{
		echo'
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
			
			
				<div class="well center-block" style="max-width:400px; background-color:#F5F5DC"> 
					<a href="'.PAG_CURRENT.'&act=form"><button type="button" class="btn btn-primary btn-lg btn-block"><i class="fa fa-file-text-o" aria-hidden="true"></i> Texto</button></a>
					<br />
					<a href="'.PAG_CURRENT.'&act=form2"><button type="button" class="btn btn-default btn-lg btn-block"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button></a>
				</div>
			
			
			</div>
			<div class="col-md-4"></div>
		</div>
		';
	}
	
}


######################################################
##################### SELEÇÃO 2 ######################
######################################################

function selecione2($id){
	$database = new DB();
		$id = $_GET['id'];
		$id_filho = $_GET['id_filho'];
		
		if($id_filho){
		$dados = $database->get_results( "SELECT * FROM legislacao where id = $id_filho" );
		$arquivo = $dados[0]['arquivo'];
		
		
		
			if($arquivo){
				echo '<meta http-equiv="refresh" content=0;url="'.PAG_CURRENT.'&act=vincular2&id='.$id.'&id_filho='.$id_filho.'">';
			}else{
				echo '<meta http-equiv="refresh" content=0;url="'.PAG_CURRENT.'&act=vincular&id='.$id.'&id_filho='.$id_filho.'">';
				
			}
		
		}else{
	
			echo'
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
				
				
					<div class="well center-block" style="max-width:400px; background-color:#F5F5DC"> 
						<a href="'.PAG_CURRENT.'&act=vincular&id='.$id.'"><button type="button" class="btn btn-primary btn-lg btn-block"><i class="fa fa-file-text-o" aria-hidden="true"></i> Texto</button></a>
						<br />
						<a href="'.PAG_CURRENT.'&act=vincular2&id='.$id.'"><button type="button" class="btn btn-default btn-lg btn-block"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button></a>
					</div>
				
				
				</div>
				<div class="col-md-4"></div>
			</div>
			';
	
		}
}




######################################################
##################### GRID GERAL #####################
######################################################

function geral(){
$database = new DB();

	$lstBtn = '
	<button class="btn btn-sm btn-success" name="new" id="new" onclick="actionPage(\'?modulo=basic&amp;pag=legislacao\',\'selecione\',false);">
		<span class="glyphicon glyphicon-plus"></span> Novo
	</button> 
	<button class="btn btn-sm btn-warning" name="edit" id="edit" onclick="actionPage(\'?modulo=basic&amp;pag=legislacao\',\'selecione\',true);">
		<span class="glyphicon glyphicon-pencil"></span> Editar
	</button>
	<button class="btn btn-sm btn-primary" name="vincular" id="vincular" onclick="actionPage(\'?modulo=basic&amp;pag=legislacao\',\'selecione2\',true);">
		<i class="fa fa-share-alt" aria-hidden="true"></i> Vincular
	</button> 	
 
	';
	
	$g = new TDataTables();
	$g->table(MOD_CUR.'scripts/grdLegislacao.php', 'tabela', $lstBtn,'html5menu',null);
	
	echo '
	<menu id="html5menu" style="display:none" type="context">
	  <command label="Novo" onclick="actionPage(\''.PAG_CURRENT.'\',\'selecione\',false);"> </command>
	  <command label="Editar" onclick="actionPage(\''.PAG_CURRENT.'\',\'selecione\',true);"> </command>
	  <command label="Vincular" onclick="actionPage(\''.PAG_CURRENT.'\',\'selecione2\',true);"> </command>
	</menu>
	';
	
	echo "<script>
			function getAnexo(id){
				sysModalBox('Modo Visualização','funcoes/visualizacao1.mdl.php?id='+id, false, 500);
			}
		</script>";

}


######################################################
################ FORMULARIO PRINCIPAL ################
######################################################

function form($id){
	$database = new DB();
	
	if( $id ){
		$dados = $database->get_results( "SELECT * FROM legislacao where id = $id" );
		$dados[0]['data'] = dataConvBR($dados[0]['data']);
	}else{
		@$dados[0]['status'] = 1;
	}

	$form = new Form('form2',PAG_CURRENT.'&act=salvar','post" enctype="multipart/form-data',null);

	
	$form->addTextField('id','ID: ',@$id,'1',null,true);
	
	$listatipos = $database->get_results( "SELECT id, nome FROM tipo where status = 1 ORDER BY nome ASC" );	
	$form->addSelectField('tipo','Tipo: ',true,$listatipos,@$dados[0]['tipo'],2,2,null,null,null,true);
	
	
	$form->addTextField('numero','Número: ',@$dados[0]['numero'],'1',true,null);
	$form->addTextField('ano','Ano: ',@$dados[0]['ano'],'1',true,null);
	
	
	$form->addDateTimeField('data','Data Publicar:',@$dados[0]['data'],true,0,2,null);
	//$form->addTextField('keys','Keys: ',@$dados[0]['keys'],'4',true,null);
	$form->addSelectField('status','Status: ',true,'0=Inativo,1=Ativo',@$dados[0]['status'],2,2,null,null,null,true);

	//$form->addTextField('titulo','Título: ',@$dados[0]['titulo'],'5',true,null);
	$form->addTextField('assunto','Súmula: ',@$dados[0]['assunto'],'12',true,null);
	
	$form->addTextArea('texto','Texto: ',@$dados[0]['texto'],'12',1024,true,null,true);
	
	
	getFormFootDefault(PAG_CURRENT);
	$form->closeForm();
	
}


######################################################
################ FORMULARIO SECUNDARIO ###############
######################################################

function form2($id){
	$database = new DB();
	if( $id ){
		$dados = $database->get_results( "SELECT * FROM legislacao where id = $id" );
		$dados[0]['data'] = dataConvBR($dados[0]['data']);
	}else{
		@$dados[0]['status'] = 1;
	}

	$form = new Form('form2',PAG_CURRENT.'&act=salvar','post" enctype="multipart/form-data',null);

	
	$form->addTextField('id','ID: ',@$id,'1',null,true);
	
	$listatipos = $database->get_results( "SELECT id, nome FROM tipo where status = 1 ORDER BY nome ASC" );	
	$form->addSelectField('tipo','Tipo: ',true,$listatipos,@$dados[0]['tipo'],2,2,null,null,null,true);
	
	$form->addTextField('numero','Número: ',@$dados[0]['numero'],'1',true,null);
	$form->addTextField('ano','Ano: ',@$dados[0]['ano'],'1',true,null);
	
	$form->addDateTimeField('data','Data Publicar:',@$dados[0]['data'],true,0,2,null);
	//$form->addTextField('keys','Keys: ',@$dados[0]['keys'],'4',true,null);
	$form->addSelectField('status','Status: ',true,'0=Inativo,1=Ativo',@$dados[0]['status'],2,2,null,null,null,true);
	//$form->addTextField('titulo','Título: ',@$dados[0]['titulo'],'5',true,null);
	$form->addTextField('assunto','Assunto: ',@$dados[0]['assunto'],'12',true,null);
	
	if( $id != null ){
		$form->addFileField('arquivo','Arquivo',@$dados[0]['arquivo'],'4',null,null,null,null);
	}else{
		$form->addFileField('arquivo','Arquivo',@$dados[0]['arquivo'],'4',null,null,null,true);
	}
	
	
	getFormFootDefault(PAG_CURRENT);
	$form->closeForm();
	
}



######################################################
############# SALVA E EDITA A LEGISLACAO #############
######################################################
function salvar(){
$database = new DB();

	$_POST['data'] = dataConvEN($_POST['data']);
	$_POST['texto'] = trim(html_entity_decode($_POST['texto'], ENT_COMPAT, "UTF-8"));
	
	$extensoes_permitidas = array('.PDF', '.pdf');
	$extensao1 = strrchr($_FILES['arquivo']['name'], '.');
	
	
	
		if($_POST['arquivo'] == ''){
			unset($_POST['arquivo'] );
		}
		
		
			
		if( $_FILES['arquivo']['name'] ){
			
			if(in_array($extensao1, $extensoes_permitidas) === true){

				$nomeOficial = limpa_str($_FILES["arquivo"]["name"]);
			
				$extensao = strtolower(end(explode('.', $nomeOficial)));
				$nome = rand().'_'.date('Ymd_Hi'). '.' . $extensao;
				$arquivo = $_FILES['arquivo']['tmp_name'];
				$caminho="../arquivos/";
				$caminho=$caminho.$nome;
				move_uploaded_file($arquivo,$caminho);
				$_POST['arquivo'] = $nome;
			}else{
				echo '<div class="alert alert-danger col-xs-12">
							<b>Erro:</b> Formato de arquivo invalido. Por favor repita o processo com arquivo PDF.</div>';
				echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'">';
				exit();
			}

		}
			
		
		
		if($_POST['id'] == null ){
			
			$query = $database->insert( 'legislacao', $_POST );
			$idLast = $database->lastid();
			
			#LOG CAD#
			if($query){
				$_POST2['data'] = date('Y-m-d H:i:s');
				$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
				$_POST2['acao'] = 'Cadastro de Legislação Cod: '.$idLast;
				$_POST2['usuario'] = $_SESSION['userId'];
				
				$query = $database->insert( 'log_sistema', $_POST2 );
			}
		
		
		}else{
		
			$where = array( 'id' => $_POST['id'] );
			$query = $database->update( 'legislacao', $_POST, $where, 1 );
			$idLast = $_POST['id'];
			
			#LOG EDIT#
			if($query){
				$_POST2['data'] = date('Y-m-d H:i:s');
				$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
				$_POST2['acao'] = 'Edição de Legislação Cod: '.$idLast;
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


######################################################
################# GRID E FORM VINCULOS ###############
######################################################

function vincular(){
	$database = new DB();
	$id_pai = @$_GET['id'];
	
	echo '
		<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Novo Vinculo</a></li>
              <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Vinculos a Esta </a></li>
            </ul>
            <div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<div class="row">
    ';
					$id_filho = @$_GET['id_filho'];
					
					if( $id_filho ){
						$dados = $database->get_results( "SELECT * FROM legislacao where id = $id_filho " );
						$dados[0]['data'] = dataConvBR($dados[0]['data']);
					}else{
						@$dados[0]['status'] = 1;
					}
	
					$form = new Form('form2',PAG_CURRENT.'&act=salvar_vinculo','post" enctype="multipart/form-data',null);
					$form->addHiddenField('referencia',@$id_pai);
					$form->addTextField('id','ID: ',@$id_filho,'1',null,true);
					
					$listatipos = $database->get_results( "SELECT id, nome FROM tipo where status = 1 ORDER BY nome ASC" );	
					$form->addSelectField('tipo','Tipo de Legislação: ',true,$listatipos,@$dados[0]['tipo'],2,2,null,null,null,true);
				
					$form->addTextField('numero','Número: ',@$dados[0]['numero'],'1',true,null);
					$form->addTextField('ano','Ano: ',@$dados[0]['ano'],'1',true,null);
					
					$form->addDateTimeField('data','Data Publicação:',@$dados[0]['data'],true,1,2,null);
					//$form->addTextField('keys','Keys: ',@$dados[0]['keys'],'4',true,null);
					$form->addSelectField('status','Status: ',true,'0=Inativo,1=Ativo',@$dados[0]['status'],2,2,null,null,null,true);
					
					//$form->addTextField('titulo','Título: ',@$dados[0]['titulo'],'5',true,null);
					$form->addTextField('assunto','Assunto: ',@$dados[0]['assunto'],'10',true,null);
					
					$listaatos = $database->get_results( "SELECT id, nome FROM atos where status = 1 ORDER BY nome ASC" );	
					$form->addSelectField('ato','Tipo de Ato: ',true,$listaatos,@$dados[0]['ato'],2,2,null,null,null,true);
					
					$form->addTextArea('texto','Texto: ',@$dados[0]['texto'],'12',1024,true,null,true);
					
					getFormFootDefault(PAG_CURRENT);
					$form->closeForm();
					
					
	echo'
					</div>
				</div>
				<div class="tab-pane" id="tab_2">
					<div class="row">
    ';           
	
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
																	<a href=\"?modulo=basic&pag=legislacao&act=selecione2&id=$id_pai&id_filho=',a.id,'\">
																		<button class=\"btn btn-warning btn-xs\" ><span class=\"glyphicon glyphicon-pencil\"></span> Editar</button>
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
					
					$g = new Grid('tab',$dados,false,250,true);
	
	
	echo'		   	</div>
				</div>
             
            </div>
        </div>
	';
	
	echo "<script>
			function getAnexo(id){
				sysModalBox('Modo Visualização de Vinculo','funcoes/visualizacao1.mdl.php?id='+id, false, 500);
			}
		</script>";
	
}



######################################################
################ GRID E FORM VINCULOS 2 ##############
######################################################

function vincular2(){
	$database = new DB();
	$id_pai = @$_GET['id'];
	
	echo '
		<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Novo Vinculo</a></li>
              <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Vinculos a Esta </a></li>
            </ul>
            <div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<div class="row">
    ';
					$id_filho = @$_GET['id_filho'];
					
					if( $id_filho ){
						$dados = $database->get_results( "SELECT * FROM legislacao where id = $id_filho " );
						$dados[0]['data'] = dataConvBR($dados[0]['data']);
					}else{
						@$dados[0]['status'] = 1;
					}
	
					$form = new Form('form2',PAG_CURRENT.'&act=salvar_vinculo','post" enctype="multipart/form-data',null);
					$form->addHiddenField('referencia',@$id_pai);
					$form->addTextField('id','ID: ',@$id_filho,'1',null,true);
					
					$listatipos = $database->get_results( "SELECT id, nome FROM tipo where status = 1 ORDER BY nome ASC" );	
					$form->addSelectField('tipo','Tipo de Legislação: ',true,$listatipos,@$dados[0]['tipo'],2,2,null,null,null,true);
				
					$form->addTextField('numero','Número: ',@$dados[0]['numero'],'1',true,null);
					$form->addTextField('ano','Ano: ',@$dados[0]['ano'],'1',true,null);
				
					$form->addDateTimeField('data','Data Publicação:',@$dados[0]['data'],true,1,2,null);
					//$form->addTextField('keys','Keys: ',@$dados[0]['keys'],'4',true,null);
					$form->addSelectField('status','Status: ',true,'0=Inativo,1=Ativo',@$dados[0]['status'],2,2,null,null,null,true);
					
					//$form->addTextField('titulo','Título: ',@$dados[0]['titulo'],'5',true,null);
					$form->addTextField('assunto','Assunto: ',@$dados[0]['assunto'],'10',true,null);
					
					$listaatos = $database->get_results( "SELECT id, nome FROM atos where status = 1 ORDER BY nome ASC" );	
					$form->addSelectField('ato','Tipo de Ato: ',true,$listaatos,@$dados[0]['ato'],2,2,null,null,null,true);
					
						if( $id_filho != null ){
							$form->addFileField('arquivo','Arquivo',@$dados[0]['arquivo'],'4',null,null,null,null);
						}else{
							$form->addFileField('arquivo','Arquivo',@$dados[0]['arquivo'],'4',null,null,null,true);
						}
					
					getFormFootDefault(PAG_CURRENT);
					$form->closeForm();
					
					
	echo'
					</div>
				</div>
				<div class="tab-pane" id="tab_2">
					<div class="row">
    ';           
	
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
																	<a href=\"?modulo=basic&pag=legislacao&act=selecione2&id=$id_pai&id_filho=',a.id,'\">
																		<button class=\"btn btn-warning btn-xs\" ><span class=\"glyphicon glyphicon-pencil\"></span> Editar</button>
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
					
					$g = new Grid('tab',$dados,false,250,true);
	
	
	echo'		   	</div>
				</div>
             
            </div>
        </div>
	';
	
	echo "<script>
			function getAnexo(id){
				sysModalBox('Modo Visualização de Vinculo','funcoes/visualizacao1.mdl.php?id='+id, false, 500);
			}
		</script>";
	
}



######################################################
############### SALVA E EDITA VINCULOS ###############
######################################################
function salvar_vinculo(){ 
$database = new DB();
	
	$id_pai = $_POST['referencia'];
	$_POST['data'] = dataConvEN($_POST['data']);
	
	$extensoes_permitidas = array('.PDF', '.pdf');
	$extensao1 = strrchr($_FILES['arquivo']['name'], '.');
	
	
	if($_POST['arquivo'] == ''){
		unset($_POST['arquivo'] );
	}
	
	
	if( $_FILES['arquivo']['name'] ){

		if(in_array($extensao1, $extensoes_permitidas) === true){
	
		$nomeOficial = limpa_str($_FILES["arquivo"]["name"]);
	
		$extensao = strtolower(end(explode('.', $nomeOficial)));
		$nome = rand().'_'.date('Ymd_Hi'). '.' . $extensao;
		
		$arquivo = $_FILES['arquivo']['tmp_name'];
		$caminho="../arquivos/";
		$caminho=$caminho.$nome;
		move_uploaded_file($arquivo,$caminho);
		$_POST['arquivo'] = $nome;
		
		}else{
			echo '<div class="alert alert-danger col-xs-12">
						<b>Erro:</b> Formato de arquivo invalido. Por favor repita o processo com arquivo PDF.</div>';
			echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'&act=">';
			exit();
		}

	}
	
	
	
	if( $_POST['id'] == null ){
		$query = $database->insert( 'legislacao', $_POST );
		$idLast = $database->lastid();
		
		#LOG CAD#
		if($query){
			$_POST2['data'] = date('Y-m-d H:i:s');
			$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST2['acao'] = 'Cadastro de Vinculo de Legislação Cod: '.$idLast;
			$_POST2['usuario'] = $_SESSION['userId'];
			
			$query = $database->insert( 'log_sistema', $_POST2 );
		}
		
	}else{
		$where = array( 'id' => $_POST['id'] );
		$query = $database->update( 'legislacao', $_POST, $where, 1 );
		$idLast = $_POST['id'];
		
		#LOG EDIT#
		if($query){
			$_POST2['data'] = date('Y-m-d H:i:s');
			$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST2['acao'] = 'Edição de Vinculo de Legislação Cod: '.$idLast;
			$_POST2['usuario'] = $_SESSION['userId'];
			
			$query = $database->insert( 'log_sistema', $_POST2 );
		}
		
		
	}

	if( @$query ){
		echo '<div class="alert alert-success col-xs-12">
				<b>Sucesso:</b> Salvo com sucesso!</div>';
	}
	echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.PAG_CURRENT.'&act=vincular&id='.$id_pai.'">';
		
}	

	
getFootPage();
