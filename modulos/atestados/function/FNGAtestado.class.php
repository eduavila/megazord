<?php

require_once "../modulos/eventos/function/GPessoas.class.php";

class FNGAtestado {
	public static function relResumo() {
		$dSes = getInfoSessaoSSID($_COOKIE['PHPSESSID']);
		
		aDiv(null, 12, 'alert-info', 'alert', '<h4>Escolha os filtros necessários:</h4>');
		aDiv(null, 12, 'well2', null);

		$form = new Form('formRes', null, null, 'post" target="mdlPrintFil', null);
		
		$listaSecretarias = GPessoas::selectListDpto();
		$form->addSelectField('psq_sec', 'Secretaria: ', false, $listaSecretarias, $dSes['DPTO'], 4, 4, null, null, null, true);
		
		$listaFuncionarios = GPessoas::selectListPessoa();
		$form->addSelectField('psq_func', 'Funcionário: ', false, $listaFuncionarios, @$dados[0]['funcionario'], 4, 4, null, null, null, false);
		
		$btn = new Button();
		aDiv(null,12,'form-group',null,null);
			$btn->btnForm(null, 'Gerar Relatório', 'type="submit"', 'print', 'success').'&nbsp;';
		cDiv();
		
		$form->closeform();
		cDiv();
		
		echo "<script>
				$('#formRes').submit(function(e){
					e.preventDefault();
					$('#modalBoxmdlPrintFil').remove();
					var values = $(this).serialize();
					geraRelatorio(values);
				});
				
				function geraRelatorio(values){
					sysModalBox('Relatório','modulos/atestados/relatorio/pdfRelAtestado.php?trel=html&'+values,990,550,false,'info','mdlPrintFil');
				}
				
				function onExitModal(){
					$('#modalBoxmdlPrintFil').remove();
				}
			</script>";
	}
}