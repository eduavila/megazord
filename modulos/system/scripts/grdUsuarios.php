<?php
include_once('../../../base/autoload.php');
header('Content-Type: application/json; charset=utf-8');

ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

$sql = "SELECT 
				 u.id
				,u.nome
				,u.login
				,p.nome as perfil
				,s.nome as status
		  FROM usuario u
			
		inner join perfil p on p.id = u.perfil
		inner join status s on s.codigo = u.status		
		";

echo json_encode( TDataTables::simple($_REQUEST, $sql), true );

