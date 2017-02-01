<?php
if($_SESSION['perfilId']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}

define('PAG_TITLE','Logs do Sistema');
define('PAG_CURRENT','?modulo=system&pag=logs');
define(MOD_CUR, './modulos/system/');

$acao = getG('act','default');
$id = @$_GET['id'];
getHeadPage(PAG_TITLE,'primary');

switch ($acao) {
	default:
	geral();
	break;
}

######################################################
##################### GRID GERAL #####################
######################################################

function geral(){
$database = new DB();

	$listauser = $database->get_results("SELECT nome as id, nome FROM usuario ORDER BY nome ASC");

	$form = new Form(null,null,null);
		$form->addSelectField('psq_status',null,true,$listauser,null,3,3,null,null,null,null);
	$form->closeform();	
	
	$g = new TDataTables();
	$g->table(MOD_CUR.'scripts/grdLogs.php', 'tabela',null,'html5menu',null);
	
	
	echo "<script>
			$('select').on( 'change', function () {
				 $('#tabela').DataTable().search( $(this).val() ).draw();
			  $(this).val('');
			}); 
		</script>";
	
echo '<menu id="html5menu" style="display:none" type="context">
  <command label="Visualizar" onclick="getVisualiza();"></command>
</menu>';

echo "<script>
function getVisualiza(){
	sysModalBoxJs('Resumo do Log','./base/xxxPagDin.php?cl=FNGeral&fct=detSessao&mod=system',true,'relRes');
}
</script>";

}

getFootPage();
