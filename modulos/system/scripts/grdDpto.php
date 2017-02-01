<?php
include_once('../../../base/autoload.php');
header('Content-Type: application/json; charset=utf-8');

ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

$sql = "SELECT d.id
		,d.sigla
		,d.nome
		,concat(p.matricula,' - ',p.nome) as responsavel
		,s.nome as status
		FROM tab_dpto d
		left join tab_pessoa p on p.id = d.resp_id
		left join status s on s.codigo = d.status
		ORDER BY id DESC";

echo json_encode( TDataTables::simple($_REQUEST, $sql), true );

