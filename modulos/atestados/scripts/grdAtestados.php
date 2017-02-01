<?php
include_once('../../../base/autoload.php');
header('Content-Type: application/json; charset=utf-8');

ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

$sql = 'SELECT
		
		 a.id
		,d.sigla as secretaria
		,p.nome as "FUNCIONÁRIO"
		,ta.nome as "tipo atestado"
		,DATE_FORMAT(a.data, "%d/%m/%Y" )as data
		,a.qtde_dias as "Qtde dias"
		,DATE_FORMAT(a.data_fim, "%d/%m/%Y") as "Data fim"
		,c.descricao as cid
		,pro.nome as profissional

     	 FROM atestado a
			
		 inner join tab_dpto d on d.id = a.secretaria
		 inner join tab_pessoa p on p.matricula = a.funcionario
		 inner join tipo_atestado ta on ta.id = a.tipo_atestado
		 inner join cid c on c.id = a.cid
		 inner join profissional pro on pro.id = a.profissional
		 ';

echo json_encode(TDataTables::simple($_REQUEST, $sql), true);

