<?php
if($_SESSION['perfilId']!=1){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?fail=1">';
	exit;
}
define('PAG_TITLE','Menu de Atestados');
define('PAG_CURRENT','?modulo=atestado&pag=menuAtestado');

$acao = (@$_GET['act'] == null ? "default": $_GET['act']);
$id = @$_GET['id'];
getHeadPage(PAG_TITLE,'primary');

switch ($acao) {
	default:
	geral();
	break;
}

function geral(){

	echo'<div class="col-md-4 col-xs-12"></div>';
	
	echo'<div class="col-md-2 col-xs-6">
			<a onclick="actionPage(\'?modulo=atestados&pag=atestado\',\'geral\',false);">
				<div class="card bg-green" style="height:130px; width:180px">
					<div class="container text-center" style="height:130px; width:180px">
						<br><i class="fa fa-3x fa-file-o"></i>
						<h4><b>Cadastro de Atestados</b></h4>
					</div>
				</div>
			</a>
		</div>';
	
	echo'<div class="col-md-2 col-xs-6">
			<a onclick="actionPage(\'?modulo=atestados&pag=profissional&named=Profissionais\',\'geral\',false);">
				<div class="card bg-yellow" style="height:130px; width:180px">
					<div class="container text-center" style="height:130px; width:180px">
						<br><i class="fa fa-3x fa-user-o"></i>
						<h4><b>Cadastro de Profissionais</b></h4>
					</div>
				</div>
			</a>
		</div>';
}

getFootPage();
