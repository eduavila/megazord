<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
include_once('../base/autoload_sub.php');

$acao = (@$_GET['act'] == null ? "default": $_GET['act']);
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
$dadAnexo = $database->get_results( "SELECT id, cod_noticia as noticia, foto, descricao
	, concat('<a onclick=getFotos(\"arquivos/noticias/',cod_noticia,'/',foto,'\") target=\"_Blanck\"><img src=\"images/visArquivo.png\" title=\"Visualizar Foto\" width=\"20px\"></a>'
	,' <a href=\"noticias.php?act=deleteFoto&id=',id,'\"><img src=\"images/delete.png\" title=\"Remover Anexo\" width=\"20px\"></a>') as  acao 
	FROM noticias_fotos where cod_noticia = $id" );

echo '<div class="col-xs-12 well">';	
$form = new Form('form3','noticias.php?act=salvarFoto','post" enctype="multipart/form-data',null);
$form->addTextField('cod_noticia','Noticia: ',$id,'1',null,true);
$form->addTextField('descricao','Descrição: ',null,4,true,null);
$form->addFileField('foto[]','Arquivo:',@$arquivo,5,null,true,true);

echo '<div class="col-xs-12">';
$btn = new Button();
$btn->btnForm(null,'Salvar Foto','type="submit"','send','success');  
echo '</div>';
$form->closeForm();
echo '</div>';
	
echo '<div class="col-xs-12 well small" style="width:100%; overflow: auto; ">';
	$gFoto = new Grid('tabFoto',$dadAnexo,false,250,false);
echo '</div>';

echo "<script>
function getFotos(img){
	sysModalBox('Visaulização de Fotos','http://previlucas.lucasdorioverde.mt.gov.br/'+img+'?',false,500);
}
</script>";

}

