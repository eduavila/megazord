<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
include_once('../base/autoload_sub.php');

$acao = getG('act','default');
$id = @$_GET['id'];

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
$dadAnexo = $database->get_results( "SELECT id, cod_noticia as noticia, nome, arquivo, anexo_ordem as ordem
	, concat('<a onclick=getAnexo(\"../arquivos/noticias/',cod_noticia,'/',arquivo,'\") target=\"_Blanck\"><img src=\"images/visArquivo.png\" title=\"Visualizar Arquivo\" width=\"20px\"></a>'
	,' <a href=\"noticias.php?act=deleteAnexo&id=',id,'\"><img src=\"images/delete.png\" title=\"Remover Anexo\" width=\"20px\"></a>') as  acao 
	FROM noticias_arquivos where cod_noticia = $id" );

echo '<div class="col-xs-12 well">';	
$form = new Form('form3','noticias.php?act=salvarAnexo','post" enctype="multipart/form-data',null);
$form->addTextField('cod_noticia','Noticia: ',$id,'1',null,true);
$form->addTextField('nome','Nome: ',null,4,true,null);
$form->addFileField('arquivo','Arquivo:',@$arquivo,5,null,null,2);
$form->addTextField('anexo_ordem','Ordem: ',null,2,true,null);

echo '<div class="col-xs-12">';
$btn = new Button();
$btn->btnForm(null,'Salvar Anexo','type="submit"','send','success');  
echo '</div>';
$form->closeForm();
echo '</div>';
	
echo '<div class="col-xs-12 well small" style="width:100%; overflow: auto; ">';
	$gA = new Grid('tabAnexo',$dadAnexo,false,250,false);
echo '</div>';

echo "<script>
function getAnexo(arq){
	sysModalBox('Visaulização de Anexo','http://previlucas.lucasdorioverde.mt.gov.br/'+arq+'?',false,500);
}
</script>";

}

