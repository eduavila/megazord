<?php
define('PAG_TITLE','Dados da Sessão Atual');
define('PAG_CURRENT','?modulo=system&pag=sessao_cur');

$acao = (@$_GET['act'] == null ? "default": $_GET['act']);
$id = @$_GET['id'];
getHeadPage(PAG_TITLE,'primary');

switch ($acao) {
	default:
	geral();
	break;

}

function geral(){
$database = new DB();

$dados = $database->get_results( "SELECT l.id, l.session, l.data, p.nome as perfil, u.nome as usuario, l.browser
					, l.remote_ip, case when l.status <> 1 then l.data_encer else 'NÃO Encerrado' end as encerrado, s.nome as status
					FROM log_sessao l
					inner join usuario u on u.id = l.user
					inner join perfil p on p.id = l.perfil
					inner join status s on s.id = l.status
					where l.id = ".$_SESSION['sessionId'] );

$g = new Grid('tab',$dados,false,500,true);
/*
echo ' <div class="row">
        <div class="col s12 m6">
          <div class="card blue-grey darken-1">
            <div class="card-content white-text">
              <span class="card-title"><b>Sessão: </b>'.$dados[0]['id'].'</span>
              <p><b>Session_ID:</b> '.$dados[0]['session'].'</p><br>
              <p><b>Browser:</b> '.$dados[0]['browser'].' | <b>IP:</b> '.$dados[0]['remote_ip'].' </p><br>			  
              <p><b>Data:</b> '.$dados[0]['data'].'</p><br>
              <p><b>Perfil:</b> '.$dados[0]['perfil'].' | <b>Usuário:</b> '.$dados[0]['usuario'].'</p><br>
              <p><b>Encerrado:</b> '.$dados[0]['encerrado'].' | <b>Status: '.$dados[0]['status'].'</b></p>			  
            </div>
          </div>
        </div>
      </div>';
*/
}


	
getFootPage();

