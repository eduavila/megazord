<?php

class FunctionsAtestado extends DB {
	
	public function FunctionsAtestado(){}
	
	//--------------------------------------------------------------------------------
	public static function selectListTipoAtestado( $orderBy=null, $where=null ){
		$dbCon = new DB();
		return $dbCon->get_results("select
							id
							,nome
					from tipo_atestado".
				( ($where)? ' where '.$where:'').
				( ($orderBy) ? ' order by '.$orderBy:''));
	}
	
	//--------------------------------------------------------------------------------
	public static function selectListCid( $orderBy=null, $where=null ){
		$dbCon = new DB();
		return $dbCon->get_results("select
							id
							,descricao as nome
					from cid".
				( ($where)? ' where '.$where:'').
				( ($orderBy) ? ' order by '.$orderBy:''));
	}
	//--------------------------------------------------------------------------------
	public static function selectListProfissional( $orderBy=null, $where=null ){
		$dbCon = new DB();
		return $dbCon->get_results("select
							id
							,nome
					from profissional".
				( ($where)? ' where '.$where:'').
				( ($orderBy) ? ' order by '.$orderBy:''));
	}
	//--------------------------------------------------------------------------------
	public static function selectRelAtestados($orderBy=null, $where=null){
		$dbCon = new DB();
		return $dbCon->get_results("select
							 a.id
							,d.sigla as secretaria
							,p.nome as funcionario
							,ta.nome as tipo_atestado
							,DATE_FORMAT(a.data, '%d/%m/%Y' )as data
							,a.qtde_dias
							,DATE_FORMAT(a.data_fim, '%d/%m/%Y') as data_fim
					
					     	 FROM atestado a
								
							 inner join tab_dpto d on d.id = a.secretaria
							 inner join tab_pessoa p on p.matricula = a.funcionario
							 inner join tipo_atestado ta on ta.id = a.tipo_atestado".
				( ($where)? ' where '.$where:'').
				( ($orderBy) ? ' order by '.$orderBy:''));
	}
}