<?php
include_once('base/includes/seguranca.php');
include_once('base/autoload.php');

//validaPagina('HeaderPage');
$valida = validaPagina('HeaderPage');
error_reporting(0);
ini_set(“display_errors”, 0 );
if(!$valida){
	exit;
}
$nameSite = getParametro('siteTitle'); 
$miniSite = getParametro('miniTitleSite'); 

setlocale(LC_ALL,'pt_BR.UTF8');
mb_internal_encoding('UTF8'); 
mb_regex_encoding('UTF8');

define('PAG_ATUAL',basename($_SERVER['PHP_SELF']));
if( $_GET['modulo'] ){
	define('SIDEBAR','sidebar-collapse');
}

$gnamed = $_GET['named'];

if($gnamed){
	$_SESSION['named'] = $_GET['named'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=7; IE=9; IE=Edge"/>
   	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php echo $nameSite; ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon2.ico">
	
	<link href="./base/css/geral.css" rel="stylesheet">
    <!-- Third part libraries required by Adianti Framework -->
    <script src="./base/lib/jquery/jquery.min.js?afver=201" type="text/javascript"></script>
    <script src="./base/lib/bootstrap/js/bootstrap-plugins.min.js?afver=201" type="text/javascript"></script>
    <script src="./base/lib/bootstrap/js/locales/bootstrap-datepicker.pt.js?afver=201" type="text/javascript"></script>
    <script src="./base/lib/jquery/jquery-ui.min.js?afver=201" type="text/javascript"></script>
    <!-- <script src="./base/lib/jquery/jquery-plugins.min.js?afver=201" type="text/javascript"></script>-->
    
    <!-- Adianti Framework core and components -->
    <script src="./base/lib/adianti/adianti.js?afver=201" type="text/javascript"></script>
    <script src="./base/lib/adianti/components.min.js?afver=201" type="text/javascript"></script>
    
    <!-- Application custom Javascript (Optional) -->
    <script src="./base/lib/app/application.js?appver=201" type="text/javascript"></script>
    
    <!-- Third part CSS required by Adianti Framework -->
    <link href="./base/lib/jquery/jquery-ui.min.css?afver=201" rel="stylesheet" type="text/css" media="screen" />
    <!--<link href="./base/lib/jquery/jquery-plugins.min.css?afver=201" rel="stylesheet" type="text/css" media="screen" />-->
    <link href="./base/lib/bootstrap/css/bootstrap.min.css?afver=201" rel="stylesheet" type="text/css" media="screen" />
    <link href="./base/lib/bootstrap/css/boostrap-plugins.min.css?afver=201" rel="stylesheet" type="text/css" media="screen" />
    <link href="./base/lib/font-awesome/css/font-awesome.min.css?afver=201" rel="stylesheet" type="text/css" media="screen" />
    
    <!-- Adianti Framework Components CSS -->
    <link href="./base/lib/adianti/adianti.css?afver=201" rel="stylesheet" type="text/css" media="screen" />
    <!--<link href="./base/lib/adianti/components.min.css?afver=201" rel="stylesheet" type="text/css" media="screen" />
    
    <!-- Application custom CSS 
    <link href="./base/lib/theme3/application.css?appver=201" rel="stylesheet" type="text/css" media="screen">    -->

    
   
    <link rel="stylesheet" href="./base/lib/theme3/css/AdminLTE.min.css">
    <link rel="stylesheet" href="./base/lib/theme3/css/skins/skin-blue.min.css">
	
<?php
include_once('base/includes/scripts.php');
?>	
</head>

<body class="hold-transition skin-blue sidebar-mini <?php echo SIDEBAR; ?>">
	<div class="wrapper">
		<header class="main-header">
			<a href="<?php echo PAG_ATUAL; ?>" class="logo">
				<span class="logo-mini"><b><?php echo $miniSite; ?></b></span>
				<span class="logo-lg"><b><?php echo $nameSite; ?></b></span>
			</a>
			<nav class="navbar navbar-static-top" role="navigation">
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</a>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							  <i class="fa fa-user"></i>
							  <span class="hidden-xs"><?php echo $_SESSION['userNome']; ?></span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-header" style="height:initial">
									<i class="fa fa-user fa-3x" style="color:white"></i>
									<p>
										<b> <?php echo $_SESSION['userNome']; ?></b><br />
											<?php echo $_SESSION['userLogin']; ?>
									</p>
								</li>
								<li class="user-footer">
									<div class="pull-left">
										<a href="?modulo=system&pag=sessao_cur&named=Perfil" class="btn btn-default btn-flat">Perfil</a>
									</div>
									<div class="pull-right">
										<a href="?modulo=system&pag=sair" class="btn btn-default btn-flat">Sair</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
		</header>
	

		<aside class="main-sidebar" >
			<section class="sidebar">
				<ul class="sidebar-menu" id="side-menu">
					<li class="header">MENU</li>
					<li class="treeview <?php echo ($modulo == 'system' ? "activer" : " "); ?>"><a href="#" class=""> <i class="fa fa-cog "></i> <span>System</span></a>
						<ul class="treeview-menu level-2" style="display: <?php echo ($modulo == 'system' ? "box" : "none"); ?> ;">
						<?php
						if($_SESSION['perfilId'] == 1){
						?>
							<li><a href="?modulo=system&pag=usuario&named=Usuários"><i class="fa fa-users" aria-hidden="true"></i> Usuários</a></li>
							<li><a href="?modulo=system&pag=parametro&named=Configurações"><i class="fa fa-cogs" aria-hidden="true"></i> Configurações</a></li>
							<li><a href="?modulo=system&pag=perfil&named=Perfil"><i class="fa fa-meh-o" aria-hidden="true"></i> Perfil</a></li>
							<li><a href="?modulo=system&pag=logs&named=Logs do Sistema"><i class="fa fa-exchange" aria-hidden="true"></i> Logs do Sistema</a></li>
							<li><a href="?modulo=system&pag=menu&named=Menu Painel"><i class="fa fa-align-justify" aria-hidden="true"></i> Menu Painel</a></li>
						<?php
						}
						?>
							<li><a href="?modulo=system&pag=sessao_cur&named=Sua Sessão"><i class="fa fa-hourglass-start" aria-hidden="true"></i> Sua Sessão</a></li>
							<li><a href="?modulo=system&pag=log_sessao&named=Log Sessão"><i class="fa fa-info-circle" aria-hidden="true"></i> Log Sessão</a></li>
							<li><a href="?modulo=system&pag=sair"><i class="fa fa-sign-out" aria-hidden="true"></i> Sair</a></li>
						</ul>
					</li>

					<?php
					$database = new DB();
					$perfil = $_SESSION['perfilId'];
					$dados = $database->get_results( "SELECT 
													 e.pag as pag
													,e.modulo as modulo
													,e.icon as icon
													,e.nome as nome
													,e.define as define															
												FROM perfil_menu a inner join menu e on e.id = a.id_menu 
												WHERE a.id_perfil = $perfil 
												order by ordem ASC" );
					
					?>	
					
						<?php 	
						for ($col = 0; $col<count($dados); $col++){
							$tabela = $dados[$col]['define'];
							
							if($tabela != null) {	
								$dados2 = $database->get_results( "SELECT count(*) AS `count` FROM $tabela" );
							}
							
							###################
							$pag = $dados[$col]['pag'];
							$_SESSION[$pag] = 1;
							###################
							
						?>
						<li class="<?php if($_GET['pag'] == $dados[$col]['pag']){ echo 'active';}?>" aria-hidden="true">
							<a href="?modulo=<?php echo @$dados[$col]['modulo'];?>&amp;pag=<?php echo @$dados[$col]['pag'];?>&named=<?php echo @$dados[$col]['nome'];?>" >
								<i class="<?php echo @$dados[$col]['icon'];?>" aria-hidden="true"></i> 
								<span><?php echo @$dados[$col]['nome'];?></span>  
								<span class="label label-primary pull-right"><?php echo $dados2[0]['count']?></span>
							</a>
						</li>
						<?php
						}
						?>
				</ul>
			</section>
		</aside>

		<div class="content-wrapper" style="min-height: 916px;">
			<?php
			$acao = (@$_GET['act'] == null ? "default": $_GET['act']);
			
			$id = @$_GET['id'];
			
			global $Base;
			$Base = '../../../';
			
			$modulo = index;
			
			foreach ( $_GET as $key => $val ){
				$$key = $val;
			}
			
			define('PAG_ATUAL',basename($_SERVER['PHP_SELF']));
			if( $_GET['modulo'] ){
			?>
			<section class="content-header">
				<h1>
					<?php echo $_SESSION['named']; ?>
				</h1>
				<ol class="breadcrumb">
					<li><a href="principal.php"><i class="fa fa-home"></i> Home</a></li>
					<?php
					if( $pag ){
					?>
						<li class="active"><?php echo ucfirst($pag);?></li>
					<?php
					}
					?>
				</ol>
			</section>
			<?php
			}
			?>
			<section class="content" style="overflow-y:auto; height:80vh;">
				<div id='adianti_div_content'>
					<div style="width: 100%; height: 50vh;">
						<?php 
							if( $modulo != index ){
								$dir = './modulos/'.$modulo.'/';
								if( file_exists( $dir ) ){
									if( $pag ){
										include_once $dir.$pag.'.php';
									}else{
										if( file_exists( $dir.'index.php' ) ){
											include_once $dir.'/index.php';
										}else{
											include_once $dir.$modulo.'.php';
										}
									}
								}else{
									echo '<div class="well text-center alert alert-danger">';
									echo '<h1 style="width: 100%; font-size: 80px; text-align: center;">
										<b>404</b></h1><h3>Modúlo não encontrado!</h1>';
									echo '</div>';
								}
								
									
							}else{
								
								
								if($acao == 'default'){
								
									######### ALTERAR SENHA #########
									$idusuario = $_SESSION['userId'];
									$dados_user = $database->get_results( "SELECT * FROM usuario WHERE id = $idusuario ");
									
									$altSenha = $dados_user[0]['alt_senha'];
									if($altSenha == 1){
										echo '
										<script src="./base/js/jquery_1_11_1.min.js"></script>
										<div class="box box-danger">
											<div class="box-header">
												<h3 class="box-title">Alteração de Senha!</h3>
											</div>
											<div class="box-body">
											
												<form method="POST" ACTION="principal.php?act=salvarSenha">
													<input class="form-control" name="id" id="id" value="'.$_SESSION['userId'].'" type="hidden">
													<div style="margin-top:10px; margin-left:10px; ">	
															
														<div class="col-xs-3">
															<label>Usuário:</label>
															<input type="text" name="usuario" id="usuario" class="form-control" size="20" required="" disabled="disabled" value="'.$_SESSION['userNome'].'" />
														</div>
												
														<div class="col-xs-3">
															<label>Nova Senha:</label>
															<!--<div class="progress col-xs-12">
																	<div id="progress" class="progress-bar progress-bar-striped progress-bar-success active" style="width: 0%">
																		<span class="sr-only">0% Complete (success)</span>
																	</div>
																</div> --><meter value="0" id="mtSenha" max="50"></meter>
															<input type="password" name="password" id="password" class="form-control" size="8" placeholder="Nova Senha" required="" />
														</div>
														<div class="col-xs-3">
															<label>Repetir Nova Senha:</label>
															<input type="password" name="password_r" id="password_r" class="form-control" size="8" placeholder="Repetir Senha" required="" />
														</div>
														<div class="col-xs-3"><br />
															<button style="" type="submit" class="btn btn-danger btn-sm"> <span class="glyphicon glyphicon-ok"></span> salvar</button>
														</div>	
													</div>	
												</form>
													
											</div>
										</div>	
											<script src="./base/js/jquery.complexify.js"></script>
											<script type="text/javascript">
											
												$(document).ready(function () {
													$(\'input\').keypress(function (e) {
													var code = null;
													code = (e.keyCode ? e.keyCode : e.which);
													return (code == 13) ? false : true;
													});
												});
											
												 $(function () {
													$("#password").complexify({}, function (valid, complexity) {
														document.getElementById("mtSenha").value = complexity;
														//$("#progress").css("width", complexity+"%").attr("aria-valuenow",complexity);
													});
												  });
											</script>
											';	
										
										
										
									}
								
								
								
									
									#################################
									echo '
									<div class=" text-center">
										<h1>Bem Vindo!</h1>
										<h4>Sistema Gerenciador Leis Municipais!</h4>
										<br />
										<img class="img-responsive" src="images/jud.png" style="display: inline;">
									</div>
									';
									
									
								}
								
							} 
							
							
							
							if($acao == 'salvarSenha'){
		
								$database = new DB();
								
								unset($_POST['usuario'] );
								$_POST['senha'] = (md5($_POST['password']));
								$_POST['senha_r'] = (md5($_POST['password_r']));
								$_POST['alt_senha'] = '0';
								unset($_POST['password'] );
								unset($_POST['password_r'] );
								
								$senha1 = $_POST['senha'];
								$senha2 = $_POST['senha_r'];
								unset($_POST['senha_r']);
								
								if($senha1 == $senha2){
									
									$where = array( 'id' => $_POST['id'] );
										$query = $database->update( 'usuario', $_POST, $where, 1 );
										$idLast = $_POST['id'];
										
										#LOG EDIT#
										if($query){
											$_POST2['data'] = date('Y-m-d H:i:s');
											$_POST2['ip'] = $_SERVER['REMOTE_ADDR'];
											$_POST2['acao'] = 'Altaração de Senha do Usuário Cod: '.$_POST['id'];
											$_POST2['usuario'] = $_SESSION['userId'];
											
											$query = $database->insert( 'log_sistema', $_POST2 );
										}
										
									if( @$query ){
										echo '<div class="container">	
												<br />
												<div class="alert alert-success col-xs-12"> <b>Sucesso:</b> Senha alterada com sucesso! Aguarde... </div>
											</div>
										';
										echo '<META HTTP-EQUIV="Refresh" CONTENT="2;URL=index.php?alt=1">';
									}
								}else{
									echo '<div class="container">	
											<br />
											<div class="alert alert-danger col-xs-12"> <b>Erro:</b> As senhas não conferem! Tente novamente. Aguarde...</div>
										</div>
									';
									echo '<META HTTP-EQUIV="Refresh" CONTENT="2;URL=principal.php">';
								}
								
							}
							
							
						?>
					</div>
				</div>
			</section>
			<div id="adianti_online_content"></div>
			<div id="adianti_online_content2"></div>
		</div>

		<footer class="main-footer">
			<div class="pull-right hidden-xs">
				<b>Version</b> 2.1.1
			</div>
			<strong>Gestão de Conteúdo</strong>
			<small>User: <span class="glyphicon glyphicon-user"></span> <a href="?modulo=system&pag=sessao_cur&named=Perfil"><?php echo $_SESSION['userNome']; ?></a> 
			| Logado: <span class="glyphicon glyphicon-time"></span> <?php echo $_SESSION['logado']; ?></small> 
		</footer>
    </div>


	<script src="./base/js/actionPage.js"></script>
	<script src="./base/js/moment.js"></script>
	<script src="./base/js/bootstrap-datetimepicker.min.js"></script>
	<link href="./base/css/bootstrap-datetimepicker.min.css" rel="stylesheet" >
	<link rel="stylesheet" href="./base/classes/lobibox/css/lobibox.css"/>
	<script src="./base/classes/lobibox/js/lobibox.js"></script>
	
	<div class="control-sidebar-bg"></div>
	<div id="divModalBox"></div>
	
	<?php
	define('PAG_ATUAL',basename($_SERVER['PHP_SELF']));
	if( !$_GET['modulo'] ){
	?>
	<!-- jQuery 2.2.3 
	<script src="./base/js/jquery-2.2.3.min.js"></script>-->
	<?php
	}
	?>
	
	<!-- Bootstrap 3.3.6 -->
	<script src="./base/js/bootstrap.js"></script>
	<!-- FastClick -->
	<script src="./base/js/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script src="./base/js/app.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="./base/js/demo.js"></script>

</body>
</html>	