<?php
include_once('../../../base/autoload.php');
header('Content-Type: application/json; charset=utf-8');

ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

$sql = "SELECT l.id, l.session, l.data, p.nome as perfil, u.nome as usuario, l.browser
			, l.remote_ip, case when l.status <> 1 then l.data_encer else 'NÃO Encerrado' end as encerrado, s.nome as status
			FROM log_sessao l
			inner join usuario u on u.id = l.user
			inner join perfil p on p.id = l.perfil
			inner join status s on s.id = l.status			
		";

echo json_encode( TDataTables::simple($_REQUEST, $sql), true );

