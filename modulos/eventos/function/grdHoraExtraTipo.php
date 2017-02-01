<?php
include_once('../../../base/autoload.php');
header('Content-Type: application/json; charset=utf-8');

ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

$sql = "select
		h.id		
		,h.codigo
		,h.nome
		,h.tipo
		,s.nome as status
from gp_horas_tipo h
left join status s on s.codigo = h.status";

echo json_encode( TDataTables::simple($_REQUEST, $sql), false );
