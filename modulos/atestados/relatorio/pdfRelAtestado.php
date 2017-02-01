<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);
$baseDir = '../../../';
include_once($baseDir.'base/autoload.php');
require_once($baseDir.'modulos/eventos/function/GPessoas.class.php');
require_once('../function/FunctionsAtestado.class.php');

$db = new DB();
//printR($_REQUEST);
//$where = ' h.situaca = "Pendente"';
$where = (getG('psq_sec')) ? (($where) ? $where . ' and ' : '') : $where;
$where = (getG('psq_sec')) ? ' a.secretaria = "'.getG('psq_sec').'"' : '';
$where = (getG('psq_func')) ? (($where) ? $where . ' and ' : '') : $where;
$where = (getG('psq_func')) ? $where . ' a.funcionario = '.getG('psq_func') : $where;


$dados = FunctionsAtestado::selectRelAtestados(null,$where);

//printR($dados);

header('Content-Type: application/pdf');
$arquivoPrint = 'resumoHoras'.date('YmdHis').'.pdf';

$cabecalho = getParametro('imgHeaderLogo');
$cabecalho = '<img src="'.$baseDir.$cabecalho.'" width="100%">';

$texto = '<html>
<head>
  <style>
    @page { margin: 120px 30px 30px;}
    #header { position: fixed; left: -30px; top: -100px; right: -30px; height: -30px; }
    #footer { position: fixed; left: 0px; bottom: -5px; right: 0px; height: 20px;}
    #content { position: fixed; left: 0px; top: 25px; bottom: 0px; right: 0px; height: 20px;}
    #footer .page:after { content: counter(page); }
	hr {margin-bottom: px; border-width: 1px; }
	div .titulo { text-align: center; font-size: 22px; font-weight: bold;	text-decoration: underline; }
	table td {border-bottom: 1px solid #B4B5B0; font-size: 12px; }
	table th {border-bottom: 2px solid #B4B5B0; border-top: 2px solid #B4B5B0; font-size: 11px; background-color: #F5F5F5; }
	table .separa {border-bottom: 2px solid #B4B5B0;font-size: 10px; }
  </style>
<body>
  <div id="header">'.$cabecalho.'</div>
  <div id="footer">'.$rodape.'</div>
  <div id="content">
<div id="conteudo">';

	if($dados){
		$texto .= '<div class="titulo" style="margin-bottom: 20px">Relatório de Atestados</div>
		<table class="table table-condensed table-bordered" width="99%" >
			<thead>
				<tr>
					<th>SECRETARIA</th>
					<th>FUNCIONÁRIO</th>
					<th>TIPO ATESTADO</th>
					<th>DATA</th>
					<th>QTDE DIAS</th>
					<th>DATA FIM</th>
				</tr>
			</thead><tbody>';
		foreach ($dados as $row){
			$texto .= '<tr>
					<td align="center">'.$row['secretaria'].'</td>
					<td align="center">'.$row['funcionario'].'</td>
					<td align="center">'.$row['tipo_atestado'].'</td>
					<td align="center">'.$row['data'].'</td>
					<td align="right">'.$row['qtde_dias'].'</td>
					<td align="center">'.$row['data_fim'].'</td>
				</tr>';
		}
		$texto .= '</tbody>
			</table>
				<br><br><br>';
		/* if( getG('psq_final') == 1 ){
			$texto .= '<p align="right">Lucas do Rio Verde-MT, '.data_extenso().'</p>';
			$texto .= '<table class="table table-condensed table-bordered" width="99%" >
				<tr>
				<td align="center"><br><br><br>____________________________________<br>Secretário(a)</td>
				<td align="center"><br><br><br>____________________________________<br>Responsável</td>
				</tr>
				</table>';
		}
		else {
			$texto .= '<h3><b>Apenas Conferência!</b> Geração de documento para simples conferência, para assinaturas marque em <b>"Ger. Final"</b>.</h3>';
		} */
	}
	else{
		$texto .= 'Sem registro para Mostrar';
	}

$texto .= '</div>
	</div>
</body>
</html>
';
//print($texto);

require_once($baseDir."base/classes/dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();

$dompdf->load_html($texto);
//$dompdf->set_paper('A4','Landscape');
$dompdf->set_paper('A4','portrait');
$dompdf->render();
$dompdf->stream($arquivoPrint,array('Attachment'=>0));