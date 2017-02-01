<?php
include_once('./base/includes/seguranca.php');

$admLoginIcon = getParametro('siteIcon');
$admLoginLogo = getParametro('admLogo');
$admLoginTitle = getParametro('admLoginTitle');
$nameSite = getParametro('siteTitle');
$descSite = getParametro('admDescription');
/*
$admLoginIcon = $database->get_results("SELECT * FROM parametro where campo = 'siteIcon' and status = 1");
$admLoginIcon = $admLoginIcon[0]['valor']; 

$admLoginLogo = $database->get_results("SELECT * FROM parametro where campo = 'admLogo' and status = 1");
$admLoginLogo = $admLoginLogo[0]['valor']; 

$admLoginTitle = $database->get_results("SELECT * FROM parametro where campo = 'admLoginTitle' and status = 1");
$admLoginTitle = $admLoginTitle[0]['valor']; 

$nameSite = $database->get_results("SELECT * FROM parametro where campo = 'admFrontTitle' and status = 1");
$nameSite = $nameSite[0]['valor']; 

$descSite = $database->get_results("SELECT * FROM parametro where campo = 'admDescription' and status = 1");
$descSite = $descSite[0]['valor'];
*/
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
	<meta charset="UTF-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=9">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $admLoginIcon; ?>">
	<title><?php echo $nameSite; ?></title>
	<meta name="description" content="<?php echo $descSite; ?>">
    <!-- Bootstrap -->
    <link href="base/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<body style="background: #d2d6de">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

	<div class="container">
		<div class="col-md-4" ></div>
		<div class="col-md-4" >
			<div class="row well" style="margin-top:20%; background: #fff; border-radius: 0px; border: 0px solid #fff;">
			
			<?php
			if( $_POST ){

				include_once('./base/includes/seguranca.php');

				$login = addslashes($_POST['usuario']);
				$senha = addslashes($_POST['senha']);
				$senha = md5($senha);

				$consulta = $database->get_results("SELECT * FROM usuario
										WHERE login = '$login' and senha = '$senha'");
				if( @$consulta[0]['id'] <> 0 ){
					$_SESSION['userId']= $consulta[0]['id'];
					$_SESSION['dptoId']= $consulta[0]['dpto'];
					$_SESSION['userLogin']= $consulta[0]['login'];
					$_SESSION['userNome']= $consulta[0]['nome'];
					$_SESSION['perfilId']= $consulta[0]['perfil'];
					$_SESSION['logado']= date('d/m/Y H:i:s');
					
					$browser = idBrowser($_SERVER['HTTP_USER_AGENT']);
					$ip = getIp();
					$log = array(
						'session' => session_id()
						,'browser' => $browser
						,'data' => date('Y-m-d H:i:s')
						,'user' => $consulta[0]['id']
						,'dpto' => $consulta[0]['dpto']
						,'remote_ip' => $ip
						,'status' => 1
						,'perfil' => $consulta[0]['perfil']
					);
					$query = $database->insert( 'log_sessao', $log );
					$_SESSION['sessionId'] = $database->lastid();
					if( $query ){
						header('location:principal.php');
					}else{
						expulsaVisitante('ErrorLog');
					}
				}else{
					unset ($_SESSION['userId']);
					//header('location:index.php');
					echo '<div class="col-md-12 col-xs-6">
						<div class="alert alert-danger" role="alert">
							<b>Error:</b> Senha é inválida!<br /> Tente Novamente! </div>
						</div>';
					echo '<META HTTP-EQUIV="Refresh" CONTENT="5;URL=index.php">';
					exit;
				}
				
			}


			if(@$_GET['fail']){
				echo '
					<div class="alert alert-danger" role="alert">
						<b>Error:</b> Você não tem permissão para acessar esta página!!<br /> </div>
					';
			}


			if(@$_GET['alt']){
				echo '
					<div class="alert alert-info" role="alert">
						<b>Info:</b> Realize login com sua nova senha!!<br /> </div>
					';
			}

			?>

				<div class="col-md-12 text-center" style="background: #fff" >
				<form name="login" class="login-form" method="post" action="index.php">
					<div class="form-login text-left">
						<div class="form-login text-center">							
							<img src="images/logo_index.png">
						</div>
						<h3><b><?php echo $nameSite; ?></b></h3>
							<label for="usuario" class="center-align">Usuário: </label>
								<input type="text" id="usuario" name="usuario" class="form-control input-sm chat-input" autofocus />
								</br>
							<label for="senha" class="center-align">Senha: </label>
								<input type="password" id="senha" name="senha" class="form-control input-sm chat-input" />
								</br>
						<div class="wrapper">
							<span class="group-btn">     
								<button type="submit" class="btn btn-primary btn-block">Entrar <i class="fa fa-sign-in"></i></button>
							</span>
						</div>
					</div>
				</form>
				<br />
				</div>
				<div class="col-md-12 text-center">
					<small><?php echo $descSite; ?></small>
				</div>
			</div>
			
		</div>
	</div>  
  
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="base/js/bootstrap.min.js"></script>
</body>
</html>