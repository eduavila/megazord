<?php

class GPessoas extends DB {
	
	public function GPessoas(){
	}
	//--------------------------------------------------------------------------------
	public static function selectListaHorasFunc( $id ){
		$dbCon = new DB();
		return $dbCon->get_results("select
									h.id		
									,o.nome as secretaria
									,concat(p.matricula,' - ',p.nome) as funcionario
									,concat('<b>',h.referencia,'</b>') as referencia
									,t.nome as tipo
									,h.valor
									,h.situacao
									
					from gp_horas h
					left join tab_dpto o on o.id = h.secretaria
					left join tab_pessoa p on p.matricula = h.registro				
					left join gp_horas_tipo t on t.id = h.tipo
					where h.registro = $id and situacao = 'Pendente'
					order by h.id DESC
				");
	}
	//--------------------------------------------------------------------------------
	public static function importaFuncFiorili(){
		$dbCon = new DB();
		//GPessoas::apagarFuncPessoa();
		
		$dados = utf8_decode(file_get_contents("http://servico4.lucasdorioverde.mt.gov.br:8083/webservice_interno/cli_folha_fiorilli.php?tipo=all"));
		$dados = json_decode($dados,true);
		$r = 0;
		$i = 0;
		$a = 0;
		foreach ( $dados as $e ) {
			$r++;
			$ca = $dbCon->get_results('select id, matricula, nome, func from tab_pessoa where cpf_cnpj = "'.$e['CPF'].'"');
			echo '<div class="alert alert-info col-xs-4" style="height: 85px;margin-bottom: 2px !important;">';
			if( $ca ){
				$a++;
				if( $ca[0]['matricula'] != $e['REGISTRO'] ){
					//echo 'DIFERE Registro - ';
					$where = array( 'cpf_cnpj' => $e['CPF'] );
					$altera = array( 'matricula' => $e['REGISTRO']
							,  'documento' => $e['DOCUMENTO']
							, 'data_nasc' => $e['DATA_NASC']
							, 'celular' => $e['CELULAR'] 
							, 'mail' => $e['MAIL']  
							, 'func' => 1 );
					$query = $dbCon->update( 'tab_pessoa', $altera, $where, 1 );
				}else{
					$where = array( 'cpf_cnpj' => $e['CPF'] );
					$altera = array( 'matricula' => $e['REGISTRO']
							,  'documento' => $e['DOCUMENTO']
							, 'data_nasc' => $e['DATA_NASC']
							, 'celular' => $e['CELULAR']
							, 'mail' => $e['MAIL']
							, 'func' => 1 );
					$query = $dbCon->update( 'tab_pessoa', $altera, $where, 1 );
				}
				echo 'Existe ID: '.$ca[0]['id'].' | '.$ca[0]['matricula'].' - '.$e['REGISTRO'].' | '.$ca[0]['nome'].' | '.$ca[0]['func'].'<br>';
			}else{
				$i++;
				$insert = array( 'matricula' => $e['REGISTRO']
						,  'nome' => $e['NOME']
						,  'cpf_cnpj' => $e['CPF']
						,  'documento' => $e['DOCUMENTO']
						, 'data_nasc' => $e['DATA_NASC']
						, 'sexo' => $e['SEXO']
						, 'ecivil' => $e['ESTADOCIVIL']
						, 'telefone' => $e['TELEFONE']
						, 'celular' => $e['CELULAR']
						, 'mail' => $e['MAIL']
						, 'func' => 1
						, 'status' => 'A'
						, 'user_id' => getS('userId')
						, 'ip_user' => getS('ip')
						, 'criado' => date('Y-m-d h:i:s') );
				$query = $dbCon->insert( 'tab_pessoa', $insert );
				echo 'Registrado inserido no banco: '.$e['REGISTRO'].' - '.$e['NOME'].' | '.$e['CPF'].'<br>';
			}
			echo '</div>';
		}
		echo '<div class="alert alert-danger col-xs-12">';
		echo 'Total de registros: <b>'.$r.'</b> | Registros Atualizados: <b>'.$a.'</b> | Registros Inseridos: <b>'.$i.'</b>';
		echo '</div>';
	}//--------------------------------------------------------------------------------
	public static function apagarFuncPessoa(){
		$dbCon = new DB();
		$query = $dbCon->update( 'tab_pessoa', array( 'func' => 0 ), array( 'func' => 1 ), 500); 
		if( $query ){
			return 'Todas as Pessoas marcadas como Não Funcionarios!<br> Rode a rotina de importação!';
		}else {
			return 'Erro na alteração, nada foi feito!';
		}
	}
	//--------------------------------------------------------------------------------
	public static function admGestPessoal(){
		$dbCon = new DB();
		$admContrato = false;
		$acesAdm = getParametro('admGestPessoal');
		$acesAdm = explode(',',$acesAdm);
		//$admContrato = in_array($userId,$acesAdm) ? true : false;
		return $acesAdm;
	}
	//--------------------------------------------------------------------------------
	public static function selectResHoras( $orderBy=null, $where=null ){
		$dbCon = new DB();
		return $dbCon->get_results("select
							h.id		
							,o.nome as secretaria
							,concat(p.matricula,' - ',p.nome) as funcionario
							,h.registro as func_id
							,h.referencia
							,t.nome as tipo
							,t.codigo as prov_id
							,t.tipo as prov_tipo
							,h.valor
							,h.situacao									
					from gp_horas h
					left join tab_dpto o on o.id = h.secretaria
					left join tab_pessoa p on p.matricula = h.registro				
					left join gp_horas_tipo t on t.id = h.tipo".
				( ($where)? ' where '.$where:'').
				( ($orderBy) ? ' order by '.$orderBy:''));
	}
	//--------------------------------------------------------------------------------
	public static function selectListDpto( $orderBy=null, $where=null ){
		$dbCon = new DB();
		return $dbCon->get_results("select
							id
							,nome
					from tab_dpto".
				( ($where)? ' where '.$where:'').
				( ($orderBy) ? ' order by '.$orderBy:''));
	}
	//--------------------------------------------------------------------------------
	public static function selectListTipoEvento( $orderBy=null, $where=null ){
		$dbCon = new DB();
		return $dbCon->get_results("select 
								id
								, concat(nome,' | ',tipo) as nome 
								from gp_horas_tipo".
				( ($where)? ' where '.$where:'').
				( ($orderBy) ? ' order by '.$orderBy:''));
	}
	//--------------------------------------------------------------------------------
	public static function selectListPessoa( $orderBy=null, $where=null ){
		$dbCon = new DB();
		return $dbCon->get_results("select 
								matricula as id
								, concat(matricula,' | ',nome) as nome 
								from tab_pessoa".
				( ($where)? ' where '.$where:'').
				( ($orderBy) ? ' order by '.$orderBy:''));
	}
	//--------------------------------------------------------------------------------
	public static function marcaGerFinal($id){
		$dbCon = new DB();
		$query = $dbCon->update( 'gp_horas', array( 'situacao' => 'Gerado' ), array( 'id' => $id ), 1);
	}
	//--------------------------------------------------------------------------------
	
}


function grdResumoCPF($cpf){
	if($cpf){
		$grid = GPessoas::selectCargosPessoa($cpf);
		echo '<div class="alert col-xs-12 alert-danger"> <h3><b>Cargos dessa Pessoa:</b></h3>';
		addGridDBNew('tab', $grid, null, 200, true);
		echo '</div>';
	}
}
