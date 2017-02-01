<head>
	<link href="../base/lib/bootstrap/css/bootstrap.min.css?afver=201" rel="stylesheet" type="text/css" media="screen" />
</head>
<?php
//ini_set('error_reporting',E_ALL);
//ini_set('display_errors',1);

include_once('../base/autoload_sub.php');

error_reporting(0);
ini_set('display_errors', 0 );	


$acao = getG('act','default');
$id = @$_GET['id'];

switch ($acao) {
	default:
	geral($id);
	break;
}


function geral($id){
	$database = new DB();
	$dadAnexo = $database->get_results( "
										SELECT 
											 id
											,texto
											,DATE_FORMAT( `data`, '%d/%m/%Y' ) AS `data`
										FROM legislacao where id = $id" );
	?>
	<div class="row">
		<div class="container">					
			<?php
			echo $dadAnexo[0]['texto'];		
			?>
		</div>
	</div>
<?php								
}

