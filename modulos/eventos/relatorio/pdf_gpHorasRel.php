<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);
$baseDir = '../../../';
include_once( $baseDir.'base/autoload.php');
require_once "../function/GPessoas.class.php";

$db = new DB();
//printR($_REQUEST);
$where = ' h.situaca = "Pendente"';
$where = ( getG('psq_refer') ) ? (($where) ? $where . ' and ' : '') : $where;
$where = ( getG('psq_refer') ) ? ' h.referencia = "'.getG('psq_refer').'"' : '';
$where = ( getG('psq_sec') ) ? (($where) ? $where . ' and ' : '') : $where;
$where = ( getG('psq_sec') ) ? $where . ' h.secretaria = '.getG('psq_sec') : $where;
$where = ( getG('psq_tipo') ) ? (($where) ? $where . ' and ' : '') : $where;
$where = ( getG('psq_tipo') ) ? $where . ' h.tipo = '.getG('psq_tipo') : $where;

$dados = GPessoas::selectResHoras(null,$where);

if( getG('psq_rel') == 1 ){
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

if( $dados ){
	$texto .= '<div class="titulo">Lançamento de Eventos</div>
	<table class="table table-condensed table-bordered" width="99%" >
		<thead>
			<tr>
				<th>SECRETARIA</th>
				<th>FUNCIONARIO</th>
				<th>REFERENCIA</th>
				<th>TIPO</th>
				<th>Qtde/Vlor</th>
			</tr>
		</thead><tbody>';
	foreach ($dados as $row){
		$texto .= '<tr>
				<td>'.$row['secretaria'].'</td>
				<td>'.$row['funcionario'].'</td>
				<td align="center"><b>'.$row['referencia'].'</b></td>
				<td>'.$row['tipo'].'</td>
				<td align="right"><b>'.$row['valor'].'</td>	
			</tr>';
		if( getG('psq_final') == 1 && $row['situacao'] == 'pendente'){
			GPessoas::marcaGerFinal($row['id']);
		}
	}
	$texto .= '</tbody>
		</table>
			<br><br><br>';
	if( getG('psq_final') == 1 ){
	$texto .= '<p align="right">Lucas do Rio Verde-MT, '.data_extenso().'</p>';
	$texto .= '<table class="table table-condensed table-bordered" width="99%" >
			<tr>
			<td align="center"><br><br><br>____________________________________<br>Secretário(a)</td>
			<td align="center"><br><br><br>____________________________________<br>Responsável</td>
			</tr>
			</table>';
	}else {
		$texto .= '<h3><b>Apenas Conferência!</b> Geração de documento para simples conferência, para assinaturas marque em <b>"Ger. Final"</b>.</h3>';
	}
}else{
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

}elseif( getG('psq_rel') == 2 ){
	if( $dados){
	header('Content-type: text/plain');
	header('Content-Disposition: attachment; filename="'.$dados[0]['secretaria'].'_'.$dados[0]['referencia'].'.txt"');
	foreach ($dados as $row){
		$registro = str_pad($row['func_id'], 10, "0", STR_PAD_LEFT); 
		$refer = explode('/',$row['referencia']);
		$refer[1] = substr($refer[1],-2);
		$codEven = str_pad($row['prov_id'], 3, "0", STR_PAD_LEFT); 
		$tipo = $row['prov_tipo'];
		if( $tipo == 'V'){
			$valor = explode(',',$row['valor']);
		}else{
			$valor = explode(':',$row['valor']);
		}
		$valor[0] = str_pad($valor[0], 6, "0", STR_PAD_LEFT); 
		$data = date('d/m/Y');
		$txt .= '001'.$registro.$refer[0].$refer[1].$codEven.$valor[0].$valor[1].$data.$tipo.'000000
';
	}
	print($txt);
	//echo nl2br($txt);
	}else{
		ECHO 'Sem registro para gerar arquivo!';
	}
}


