<?php
include_once('../../../base/autoload.php');
header('Content-Type: application/json; charset=utf-8');

ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

$sql = "SELECT 
			 a.id
			,e.nome as tipo
			,concat('Nº ',a.numero,'/',a.ano) as lei
			,SUBSTR(a.assunto, 1, 100) sumula
			,DATE_FORMAT( `data`, '%d/%m/%Y' ) AS `data`
			,i.nome	as status
			,concat('
				<a onclick=getAnexo(\"',a.id,'\") target=\"_blank\">
					<button class=\"btn btn-info btn-xs\" ><span class=\"glyphicon glyphicon-search\"></span> Visualizar</button>
				</a>
			') as acao 
			
			
			
	FROM legislacao a 
	
	inner join tipo e on e.id = a.tipo
	inner join status i on i.codigo = a.status		

	where referencia = '0'
		";
		
		
		
/*$sql = "SELECT 
			 a.id
			,e.nome as tipo
			,SUBSTR(a.titulo, 1, 100) titulo
			,DATE_FORMAT( `data`, '%d/%m/%Y' ) AS `data`
			,i.nome	as status
			,concat('
				<a onclick=getAnexo(\"',a.id,'\") target=\"_blank\">
					<button class=\"btn btn-info btn-xs\" ><span class=\"glyphicon glyphicon-search\"></span> Visualizar</button>
				</a>
			')  acao 
			
	FROM legislacao a 
	
	inner join tipo e on e.id = a.tipo
	inner join status i on i.codigo = a.status		

	where referencia = '0'
		";*/

echo json_encode( TDataTables::simple($_REQUEST, $sql), true );

