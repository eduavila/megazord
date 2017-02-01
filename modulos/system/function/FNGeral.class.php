<?php

class FNGeral {

	function detSessao(){
		$id = getG('id');
		$db = new DB();
		$dados = $db->get_results("SELECT 
				 a.id,DATE_FORMAT( `data`, '%d/%m/%Y %H:%i:%s' ) AS `data`
				,a.ip,a.tela, a.acao as detalhe,e.nome as user			
				FROM log_sistema a 
				left join usuario e on e.id = a.usuario
				where a.id = ".$id);
		
		echo '<div class="col-xs-12">';
		//echo '<br /><br />';
		echo '<b>Id:</b> '.$dados[0]['id'].' - <b>IP:</b> '.$dados[0]['ip'].' | <b>Data:</b> '.$dados[0]['data'].'
			<br> <b> User: '.$dados[0]['user'].'</b> | <b> Tela: </b>'.$dados[0]['tela'].'<br />';
		echo '<div class="well2 ">';		
		$dadosLog = str_replace(']','', explode(' | Log: [', $dados[0]['detalhe']));
		$obj = @json_decode($dadosLog[1]);
			foreach ($obj as $key => $value ){
				echo '<b>'.$key.'</b>: '.$value.'<br>';
			}
		echo '</div>';
		echo '<div class="well2"><b> Log Não Tratado: </b>';
		echo $dadosLog[1];
		echo '</div>';			
		cDiv();
	}
	
	
}