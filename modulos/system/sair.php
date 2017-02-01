<?php
define('PAG_TITLE','Sair do Sistema');
define('PAG_CURRENT','?modulo=system&pag=sair');

$acao = (@$_GET['act'] == null ? "default": $_GET['act']);
$id = @$_GET['id'];

getHeadPage(PAG_TITLE,'info');

expulsaVisitante('ExitSystem');


getFootPage();