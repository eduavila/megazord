<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

include_once('../../../base/autoload.php');
header('Content-Type: application/json; charset=utf-8');

require_once './GPessoas.class.php';

$dSes = getInfoSessaoSSID($_COOKIE['PHPSESSID']);
if( !in_array($dSes['user'], GPessoas::admGestPessoal(), true ) ){
	$where = ' where h.secretaria = '.$dSes['dpto'];
}


$sql = "select
		h.id		
		,o.nome as secretaria
		,concat(p.matricula,' - ',p.nome) as funcionario
		,h.referencia
		,t.nome as tipo
		,h.valor
		,h.situacao
				
from gp_horas h
left join tab_dpto o on o.id = h.secretaria
left join tab_pessoa p on p.matricula = h.registro				
left join gp_horas_tipo t on t.id = h.tipo
$where";

echo json_encode( TDataTables::simple($_REQUEST, $sql), false );
