<?php
if($_SESSION['perfilId']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

require_once "./modulos/eventos/function/GPessoas.class.php";
require_once "function/FunctionsAtestado.class.php";

echo'<script src="./base/js/date.format.js" type="text/javascript"></script>';

define('PAG_TITLE','Lançamento de Atestados');
define('PAG_CURRENT','?modulo=atestados&pag=atestado');
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
	$btn = new Button();
	
	$lstBtn = $btn->btnFrmJS('btnVoltar', 'Voltar', "onclick=\"actionPage('?modulo=atestados&amp;pag=menuAtestado','form',false);\"", 'fa-arrow-left', 'info').' '.
	$btn->btnFrmJS('btnNovo', 'Novo', "onclick=\"actionPage('".PAG_CURRENT."','form',false);\"", 'fa-plus', 'success').' '.
	$btn->btnFrmJS('btnEditar', 'Editar', "onclick=\"actionPage('".PAG_CURRENT."','form',true);\"", 'fa-pencil', 'warning').' '.
	$btn->btnFrmJS('btnRelatorio', 'Relatório', "onclick=\"mdlRelatorio();\"", 'fa-file-o', 'primary');
	
	$g = new TDataTables();
	$g->table(MOD_CUR.'scripts/grdAtestados.php', 'tabela', $lstBtn,'html5menu',null);
	
	echo '
	<menu id="html5menu" style="display:none" type="context">
	  <command label="Novo" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',false);"> </command>
	  <command label="Editar" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',true);"> </command>
	</menu>
	';
	
	echo "<script>
			function mdlRelatorio(){
				sysModalBoxJs('Relatório Atestados','./base/xxxPagDin.php?cl=FNGAtestado&fct=relResumo&mod=atestados',990,600,false,'success','relRes');
			}
		</script>";
}

function form($id){

	$database = new DB();
	$form = new Form('form1',PAG_CURRENT.'&act=salvar','post',null);
	$form->addHiddenField('id', $id);
	
	if($id){
		$dados = $database->get_results("SELECT * FROM atestado where id = $id");
		
		$dados[0]['data'] = dataConvBR($dados[0]['data']);
		$dados[0]['data_fim'] = dataConvBR($dados[0]['data_fim']);
		$dados[0]['data_fim_aux'] = dataConvBR($dados[0]['data_fim_aux']);
	}
	
	
	$listaSecretarias = GPessoas::selectListDpto();
	$form->addSelectField('secretaria','Secretaria: ',true,$listaSecretarias,@$dados[0]['secretaria'],4,4,null,null, null,true);
	
	$listaFuncionarios = GPessoas::selectListPessoa();
	$form->addSelectField('funcionario','Funcionário: ',true,$listaFuncionarios,@$dados[0]['funcionario'],4,4);
	
	$listaTipoAtestado = FunctionsAtestado::selectListTipoAtestado();
	$form->addSelectField('tipo_atestado','Tipo do Atestado: ',true,$listaTipoAtestado,@$dados[0]['tipo_atestado'],4,4,null,null, null,true);
	
	$form->addDateTimeField('data','Data: ',@$dados[0]['data'],true,1,2);
	$form->addTextField('qtde_dias','Quantidade de dias: ',@$dados[0]['qtde_dias'],2,true,null);
	$form->addDateTimeField('data_fim_aux','Data Fim: ',@$dados[0]['data_fim'],true,1,2,true);
	$form->addHiddenField('data_fim', @$dados[0]['data_fim']);
	
	
	$listaProfissionais = FunctionsAtestado::selectListProfissional();
	$form->addSelectField('profissional','Profissional: ',true,$listaProfissionais,@$dados[0]['profissional'],6,6);
	
	$listaCid = FunctionsAtestado::selectListCid();
	$form->addSelectField('cid','CID: ',true,$listaCid,@$dados[0]['cid'],12,12);
	
	getFormFootDefault(PAG_CURRENT);
	$form->closeForm();
	
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#qtde_dias').blur(function() {
			if($('#data').val() != '') {
				if($(this).val() == '') {
					$(this).val(1);
				}
				setDataFim();
			}
		});

		$('#data').blur(function() {
			if($(this).val() != '' && $('#qtde_dias').val() != '') {
				setDataFim();	
			}
		});
	});

	function setDataFim() {
		var date2 = $('#data').val().split("/");
		date2 = new Date(date2[2], date2[1] - 1, date2[0]);
		var dias = $('#qtde_dias').val();
		dias = parseInt(dias, 10) - 1;
		date2.setDate(date2.getDate()+dias); 
		$('#data_fim').val(dateFormat(new Date(date2), 'dd/mm/yyyy'));
		$('#data_fim_aux').val(dateFormat(new Date(date2), 'dd/mm/yyyy'));
	}
</script>

<?php 
}

function salvar(){

	$database = new DB();
	
	$_POST['data'] = dataConvEN($_POST['data']);
	$_POST['data_fim'] = dataConvEN($_POST['data_fim']);
	
	// Insert/Update
	if($database->insert('atestado', $_POST, 'id')){
		msgTelaPost(1, '', PAG_CURRENT);
	} else {
		msgTelaPost(2, '', PAG_CURRENT);
	}
	
	// Log da ação
	Logger($_POST);
}

getFootPage();
