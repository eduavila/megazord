<?php
define('PAG_TITLE','Log da Sessão');
define('PAG_CURRENT','?modulo=system&pag=log_sessao');

$acao = (@$_GET['act'] == null ? "default": $_GET['act']);
$id = @$_GET['id'];
getHeadPage(PAG_TITLE,'primary');
define(MOD_CUR, './modulos/system/');

switch ($acao) {
	default:
	geral();
	break;
}

function geral(){
$database = new DB();

	$g = new TDataTables();
	$g->table(MOD_CUR.'scripts/grdLogSessao.php', 'tabela',null,'html5menu',null);



}


getFootPage();
