<?php
include_once('../../../base/autoload.php');
header('Content-Type: application/json; charset=utf-8');

ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

$sql = "SELECT 
				 a.id
				,DATE_FORMAT( `data`, '%d/%m/%Y %H:%i:%s' ) AS `data`
				,a.ip
				,a.tela
				,SUBSTRING(a.acao,1,80) as acao
				,e.nome
				
		FROM log_sistema a inner join usuario e on e.id = a.usuario	
		";

echo json_encode( TDataTables::simple($_REQUEST, $sql), true );

