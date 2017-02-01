<?php
require_once "GPessoas.class.php";

class FNGPessoas {
	
	public static function relResumo(){	
		$dSes = getInfoSessaoSSID($_COOKIE['PHPSESSID']);		
		if( in_array($dSes['user'], GPessoas::admGestPessoal(), true ) ){
			$listaRel = '1=Relatório,2=Exportar TXT';
		}else{
			$listaRel = '1=Relatório';
			$dpto = ' and id ='.$dSes['DPTO'];
		}
		
		aDiv(null, 12, 'alert-info', 'alert','<h4>Escolha apenas os filtros necessarios:</h4>');
		aDiv(null, 12, 'well2', null);
		$form = new Form('formRes',null,null,'post" target="mdlPrintFil',null);
			
			$form->addSelectField('psq_rel','Tipo Geração: ',true,$listaRel,1,2,2,null,null,null,true,null,null,false);
			$form->addTextField( 'psq_refer', 'Referencia:', date('m/Y'), 2, null , null, false);				
			$orgaoLista = GPessoas::selectListDpto();
			$form->addSelectField('psq_sec','Secretaria: ',true,$orgaoLista,$dSes['DPTO'],4,4,null,null,null,true,null,null,false);			
			$tipoLista = GPessoas::selectListTipoEvento();
			$form->addSelectField('psq_tipo','Tipo: ',false,$tipoLista,$dados[0]['psq_tipo'],2,2,null,null,null,true,null,null,false);
			$form->addSelectField('psq_final','Ger. Final: ',true,'1=Sim,2=Não',2,2,2,null,null,null,true,null,null,false);
						
			$btn = new Button();
			$btn->btnForm(null, 'Gerar Relatório', 'type="submit"', 'print', 'success').'&nbsp;';			
			$btn->btnForm(null, 'Fechar', null, 'remove', 'danger" data-dismiss="modal" aria-label="Close');

		$form->closeform();
		
		cDiv();
		echo "<script>
		function geraRelatorio(values){
			console.log('dsfasd');
			sysModalBox('Relatório','modulos/eventos/relatorio/pdf_gpHorasRel.php?trel=html&'+values,990,550,false,'info','mdlPrintFil');
		}
		$('#formRes').submit(function(e){
			e.preventDefault();
			$('#modalBoxmdlPrintFil').remove();
			var values = $(this).serialize();
			geraRelatorio(values);
		});
		function onExitModal(){
			$('#modalBoxmdlPrintFil').remove();
		}
		</script>";
	}
	//--------------------------------------------------------------------------------
	public static function importaPess(){
		echo '<div class="col-xs-12 well" style=" height: 420px; overflow: auto; background-color: #fff;">';
		GPessoas::importaFuncFiorili();
		echo '</div>';
	}	
	//--------------------------------------------------------------------------------
	
}