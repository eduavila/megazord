<?php
include_once('../../../base/autoload.php');
header('Content-Type: application/json; charset=utf-8');

ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

$sql = "SELECT p.*
		,(SELECT GROUP_CONCAT( sm.nome separator ', ' )
			FROM perfil_menu m
			left join menu sm on sm.id = m.id_menu
			where id_perfil = p.id) as acessos 
		FROM perfil p 
		ORDER BY id DESC";

echo json_encode( TDataTables::simple($_REQUEST, $sql), true );

