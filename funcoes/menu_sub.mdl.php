<?php
include_once('../base/autoload_sub.php');

$acao = getG('act','default');
$id = getG('id');
$idm = getG('idm');

switch ($acao) {
	default:
	geral($id);
	break;

	case('salvar'):
	salvar();
	break;	
}


function geral($id){
$database = new DB();

echo '<div class="col-xs-12 well" id="frmSub" >';	
$idm = getG('idm');
if( $idm ){
	$dados = $database->get_results( "select * FROM menu_sub where id = $idm" );
	$id = @$dados[0]['menu'];
}else{
	$id = getG('id');
	@$dados[0]['status'] = 1;
}
$form = new Form('form3','menu.php?act=salvarSub','post',null);
$form->addTextField('id','ID: ',@$idm,'1',null,true);
$form->addTextField('menu','Menu: ',$id,'1',null,true);
$form->addTextField('nome','Nome: ',@$dados[0]['nome'],7,true,null);
$form->addTextField('define','Define: ',@$dados[0]['define'],'3',false,null);
$form->addTextField('link','Link: ',@$dados[0]['link'],8,true,null);
$target = '_blank=Blank,_top=Top';
$form->addSelectField('target','Traget: ',@$dados[0]['target'],$target,2,true,null);
$form->addSelectField('status','Status: ',@$dados[0]['status'],'0=Inativo,1=Ativo',2,true,null);

echo '<div class="col-xs-12">';
$btn = new Button();
$btn->btnForm(null,'Salvar','type="submit"','send','success'); 
$btn->btnForm(null,'Limpar','onclick="getNovo(); return false;"','refresh','info'); 
echo '</div>';
$form->closeForm();
echo '</div>';
	
echo '<div class="col-xs-12 well small" style="width:100%; overflow: auto; " id="tbAnx">';
$dadMenu = $database->get_results( "select *, concat('<button onclick=\"getSubMenu(', id ,')\" class=\"btn btn-warning btn-xs\" ><span class=\"glyphicon glyphicon-pencil\"></span>Editar</button> <a class=\"btn btn-danger btn-xs\" href=\"menu.php?act=delSub&id=',id,'\"><span class=\"glyphicon glyphicon-remove\"></span>Excluir</a>') as  acao 
	FROM menu_sub 
	where menu = $id" );
	
	$gA = new Grid('tabAnexo',$dadMenu,false,250,false);
	
echo '</div>';

echo "<script>
function getSubMenu(id){
	$('#frmSub').load('funcoes/menu_sub.mdl.php?idm='+id+' #frmSub');
}
function getNovo(){
	
	$('#frmSub').load('funcoes/menu_sub.mdl.php?id=".$id." #frmSub');
}
</script>";

}

