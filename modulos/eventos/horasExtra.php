<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

if($_SESSION['perfilId']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

require_once 'function/GPessoas.class.php';

define(PAG_TITLE,'Lançamento de Eventos');
define(PAG_CURRENT, '?modulo=eventos&pag=horasExtra');
define(MOD_CUR, './modulos/eventos/');

$acao = getG('act','geral');
$id = getG('id');
getHeadPage(PAG_TITLE,'primary');

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
$pagTipo = PAG_CURRENT."Tipo&named=Tipos de Eventos";
$btn = new Button();
$lstBtn = $btn->btnFrmJS('btnNew', 'Novo', "onclick=\"actionPage('".PAG_CURRENT."','form',false);\"", 'fa-plus', 'success').
		$btn->btnFrmJS('btnEdit', 'Editar', "onclick=\"actionPage('".PAG_CURRENT."','form',true);\"", 'fa-pencil', 'warning').
		$btn->btnFrmJS('btnRel', 'Resumos de Horas', "onclick=\"mdlRelatorio();\"", 'fa-print', 'primary').
		$btn->btnFrmJS('btnImporta', 'Importa Fiorili', "onclick=\"importaPesFiorili();\"", 'fa-refresh', 'danger').
		$btn->btnFrmJS('btnTipo', 'Eventos', "onclick=\"actionPage('".$pagTipo."','geral',false);\"", 'fa-plus', 'info');

$g = new TDataTables();
$g->table(MOD_CUR.'function/grdHoraExtra.php', 'tab', $lstBtn, 'html5menu',null);
//$g->table(MOD_CUR.'scripts/grdUsuarios.php', 'tabela', $lstBtn,'html5menu',null);

echo '<menu id="html5menu" style="display:none" type="context">
  <command label="Novo" icon="new" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',false);"></command>
  <command label="Editar" icon="edit" onclick="actionPage(\''.PAG_CURRENT.'\',\'form\',true);"></command>
</menu>';

echo "<script>
function mdlRelatorio(){
	sysModalBoxJs('Relatório Resumo das Horas','./base/xxxPagDin.php?cl=FNGPessoas&fct=relResumo&mod=eventos',990,600,false,'success','relRes');
}
function importaPesFiorili(){
	sysModalBoxJs('Importação de Funcionarios','./base/xxxPagDin.php?cl=FNGPessoas&fct=importaPess&mod=eventos',990,600,false,'success','relRes');
}
</script>";

}


// Função Único formulario na página
function form( $id=null ){
//echo '<script src="js/jquery.mask.js"></script>';
$sair = false;
$db = new DB();
if( $id != null ){
	$dados = $db->get_results("select * from gp_horas where id = ".$id);
	if( $dados[0]['situacao'] != 'pendente'){
		$sair = true;
	}
}else{
	if( date('d') <= '20' ){
		@$dados[0]['referencia'] = date('m/Y');
	}else{
		$date = strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " +1 month");
		@$dados[0]['referencia'] = date("m/Y",$date);
	}
	$dados[0]['registro'] = getG('registro');
}
if( $sair == false ){
aDiv(null,'12','well2', null);
$form = new Form('form1',PAG_CURRENT.'&act=salvar','post','init()');
	$form->addHiddenField('id',$id);
	$form->addHiddenField('sessao',getG('sessionId') );
	$form->addHiddenField('created_at',date('Y-m-d h:i:s') );
	$form->addHiddenField('situacao','pendente');
	
	$form->addTextField( 'referencia', 'Referencia:', @$dados[0]['referencia'], 1,true,true,null );
			
	$orgaoLista = GPessoas::selectListDpto();
	$form->addSelectField('secretaria','Secretaria: ',true,$orgaoLista,$dados[0]['secretaria'],3,3,null,null,null,true,null,null,null);
	
	$pesLista = GPessoas::selectListPessoa();
	$form->addSelectField('registro','Funcionario: ',true,$pesLista,$dados[0]['registro'],4,4,null,null,null,null,null,'atualizaLista()',null,null);

	$tipoLista = GPessoas::selectListTipoEvento();
	$form->addSelectField('tipo','Tipo: ',true,$tipoLista,$dados[0]['tipo'],3,3,null,null,null,true,null,'alTipo()',null,null);
	$form->addTextField( 'valor', 'Valor:', @$dados[0]['valor'], TRUE, null , null, false,1,1);
	
	echo '<div class="col-xs-9">';
	$form->addTextArea('detalhe', 'Detalhes/Observações', @$dados[0]["detalhe"], 12, 10024);
	echo '</div>';	
	
	echo '<div class="col-xs-3 well2" >
		<div class="col-xs-12" id="demDif" > <b>Calculo de Horas</b><br>';
		$gpess = getG('gpess',$dados[0]['registro']);
		if( $gpess ){
			validaQtdeHoras($dados[0]['referencia'],$gpess);
		}
	echo '</div>';
	echo '</div>';
	getFormFootDefault(PAG_CURRENT);
$form->closeform();
cDiv();

}else{
	$btn = new Button();	
	echo '<div class="alert alert-danger text-center">
		<h3>Atenção: </h3> Registro já encaminhado para Dpto Pessoal, <b>não é possivel edição!</b>
	</div>';
	$btn->btnHref(null,'Voltar',PAG_CURRENT,'arrow-left','info');	
}

echo '<div class="col-xs-12 well2 " id="tabGPess"> <b>Registros Anteriores:</b>';
	$gpess = getG('gpess',$dados[0]['registro']);
	if($gpess){
		$grid = GPessoas::selectListaHorasFunc($gpess);
		$g = new Grid('tab', $grid, null, 190, true);
	}
echo '</div>';

echo "<script>
	$(\"#valor\").mask(\"99:99\");
	msgValor();
		
function alTipo(){		
	var tipo = $('#tipo').val();
	htip = $('#tipo option:selected').text();
	htip = htip.charAt(htip.length-1);
	if( htip == 'V')
    	$(\"#valor\").mask(\"999,99\");
    else
		msgValor();
		
}

function atualizaLista(){
	var pes = $('#registro').val();
	$(\"#tabGPess\").load(window.location.href+'&gpess='+pes+' #tabGPess>*');		
	$(\"#demDif\").load(window.location.href+'&gpess='+pes+' #demDif');
}
		
function msgValor(){
	$('#area_valor').attr('title','Para lançamento em horas o total não poderá ultrapassar <b>45 Horas</b>');
}
</script>";


}


function validaQtdeHoras( $ref, $func){
$db = new DB();	
$func = intval($func);	
$dados = $db->get_results('select * from gp_horas where referencia = "'.$ref.'" and registro = '.$func.' and tipo in (1,2)');
$hora = array();
foreach ( $dados as $r ){
	$hora[] = $r['valor'];
} 

somaHoraMinuto($hora[0],$hora[1],true);

}

function somaHoraMinuto($hora1,$hora2,$text=null){
	$menor = 0;
	$hora1 = explode(':',$hora1);
	$hora2 = explode(':',$hora2);
	$hora = ($hora1[0]+$hora2[0]);
	$minutos = ($hora1[1]+$hora2[1]);
	if( $minutos >= 60 ){
		$minutos = $minutos-60;
		$hora = $hora+1;
	}
	if( $hora >= 45 ){
		$exedH = $hora - 45;
		$exedM = $minutos;
	}else{
		$menor = true;
	}
	if( $text ){
	$html = 'Horas: '.$hora;
	$html .= ' | Minutos: '.$minutos;
	$html .= '<br>Tempo Exedente <b> 45 Horas | '.str_pad($exedH,2,0, STR_PAD_LEFT).':'.str_pad($exedM,2,0, STR_PAD_LEFT).'</b>';
	echo $html;
	}else {
		if( $menor != true ){
			return false;
		}else{
			return true;
		}
	}
}


// Função para Salvar dos Dados
function salvar(){
$db = new DB();	
Logger($_POST);
//printR($_POST);
$block = false;
if( in_array($_POST['tipo'], array(1,2)) ){
	if( $_POST['id'] != null ){
		$wId = ' and id <> '.$_POST['id'];
	}
	$dados = $db->get_results('select * from gp_horas 
			where referencia = "'.$_POST['referencia'].'" and registro = '.$_POST['registro'].' and tipo in (1,2)'.$wId);
	$hora = array();
	foreach ( $dados as $r ){
		$hora[] = $r['valor'];
	}
	if( !$hora[1] ){
		$hora[1] = $_POST['valor'];
	}
	$block = somaHoraMinuto($hora[0],$hora[1]);
}

if( $block != false ){	
	if( $db->insert( 'gp_horas', $_POST , 'id') ){
		msgTelaPost(1, null, PAG_CURRENT);
	}
}else{
	$msg = '<div class="alert alert-danger" role="alert"><h2><b>Falha, Não Salvo!</b></h2><h3> Valor de <b>Hora extra</b> acima do permitido!</h3></div>';
	msgTelaPost(2, $msg, PAG_CURRENT, 10);
	somaHoraMinuto($hora[0],$hora[1],true);
}

}


