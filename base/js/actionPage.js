function validSelect(){
    if( $('input[name="checkbox1"]:checked').length == 1 ){
    	return $('input[name="checkbox1"]:checked').val();
    }else
    	if( $.isNumeric($('#idActTable').val()) == true ){
    		return $('#idActTable').val();
    	}else
    		jsAlertBox('info','Selecione!','Selecione 1 (um) item da Listagem!');
			return false;
}


function actionPage(pagina,acao,id){
	if( id == false){
		href=pagina+'&act='+acao;
		location.href=href;
		return true;
	}
	var idMov = validSelect();
	if( idMov  == false){
		return false;
	}else{
		var href;
		if( id != null ){
			href=pagina+'&act='+acao+'&id='+idMov;
		}else{
			href=pagina+'&act='+acao;
		}
	    	location.href=href;
    }
}


function createDataTableJS( url, tab, strip ){
	strip = jQuery.parseJSON(strip);
	$.ajax({
		async: true,
		"url": url+"?draw=0&limit=2",
		"success": function(json) {
			var tableHeaders = "";
			var tColumns = [];
			$.each(json.columns, function(i, val){
				tableHeaders += "<th>" + val + "</th>";
				tColumns.push({ "name": val });
			});
			$("#tableDiv").empty();
			$("#tableDiv").append('<table id="'+tab+'" class="table table-condensed table-striped table-bordered table-hover table-responsive" cellspacing="0" width="100%"><thead><tr>' + tableHeaders + '</tr></thead></table>');
	
			var table = $('#'+tab).DataTable({
		        "language": { "url": "./base/classes/datatables/Portuguese-Brasil.json" },
				"dom": 'Bfrtip',
				"lengthChange": false,
				"processing": true,
		        "serverSide": true,
				"order": [[ 0, "desc" ]],
				"info": true,
				"select": { style: 'single' },
				"lengthMenu": [[12, 50, 100, 250, 500, 1000], ['12 linhas', '50 linhas', '100 linhas', '250 linhas', '500 linhas', '1000 linhas']],
				"ajax":{
					url : url,
					type: "GET",
					error: function (res) {
						jsMensageBox("warning","normal","Sem Registros","Correspondente a Pesquisa: " + $(".dataTables_filter input").val(),"10000",450);
						$(".dataTables_filter input").val("");
					}
				},
				 "buttons": [
				            { extend: 'copy', text: '<u>C</u>opiar',key: { key: 'c', altKey: true} }
				            , 'csv', 'excel', 'pdf'
				            , { extend: 'print', text: '<u>I</u>mprimir',key: { key: 'i', altKey: true} }
				            , { extend: 'colvis', text: 'Coluna Visivel' }
				            , { extend: 'pageLength', text: 'Registros', className: 'btn-danger' }
				        ],
				"columns" : tColumns,
				"fnRowCallback" : function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
					if( strip ){
						$.each(strip.bg, function(key, value){
							if (aData[strip.col] == key ) {
								$(nRow).addClass(value);
							}
						});
						this.removeClass('table-striped');
					}
				}
			});
			table
		    	.on( 'select', function ( e, dt, type, indexes ) {
		            var rowData = table.rows( indexes ).data().toArray();
					$("#idActTable").val(rowData[0][0]);
		        })
		        .on( 'deselect', function ( e, dt, type, indexes ) {
					$("#idActTable").val("");
		    	});
		}
	});
		
}


function sysModalBoxJs(title,url,data,nome){
if( data == true ){
	if( validSelect() == false ){
		return false;
	}
	var idMov = validSelect();
	var urlFinal = url + '&id=' + idMov;
}else{
	var urlFinal = url;
}
console.log(urlFinal);
if( nome != 'undefined' ){
	nome = 'name="'+nome+'"';
}
var box = url.length;
var onModalHide = function() {
    $('#modalBox'+box).remove();
};
var arq = url.split('?');
var html = '<div class="modal fade" id="modalBox'+box+'" name="'+nome+'" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">';
	html+= '<div class="modal-dialog modal-lg">';
			html+= '<div class="panel panel-info modal-content">';
				html+= '<div class="panel-heading">';
				html+= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
				html+= '<h4><b>'+title+'</b>  <em><small class="text-right">['+arq[0]+']</small></em></h4></div>';
					html+= '<div class="row modal-body">';
					html+= '<div id="modalBoxDetalhe'+box+'"><div class=" text-center"><img src="images/Preloader_3.gif" width="64" height="64"><br> Carregando...</div>';
			html+= '</div></div></div></div></div>';
jQuery('#divModalBox').append( html );
$('#modalBox'+box).modal('show');
$('#modalBoxDetalhe'+box).load( urlFinal + "&embedded=true");
	$('#modalBox'+box).on('hidden.bs.modal', function (e) {
		//onExitModal();
		$('#modalBox'+box).remove();
	});
}


function sysModalBox(title,url,width,height,data,alerta,nome){
if( data == true ){
	if( validSelect() == false ){
		return false;
	}
	var idMov =  validSelect();
	var urlFinal = url + '&id=' + idMov;
}else{
	var urlFinal = url;
}
if( alerta != 'undefined' ){
	alerta = 'alert alert-'+alerta;
}
if( nome != 'undefined' ){
	nome = 'name="'+nome+'"';
}
var html = '<div class="modal fade" id="modalBox" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">';
	html+= '<div class="modal-dialog modal-lg">';
			html+= '<div class="modal-content">';
				html+= '<div class="modal-header ' + alerta + '">';
				html+= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4><b>'+title+'</b></h4></div>';
					html+= '<div class="row modal-body" id="modalBoxDetalhe">';
					html+= '<div class="col-xs-12 text-center" id="loading"><br><br><img src="images/Preloader_3.gif" width="64" height="64"></div>';
					html+= '<iframe src="' + urlFinal + '&embedded=true" '+nome+' width="100%" height="'+height+'" style="border: none;" onload="jaCarregado()"></iframe>';
					//html+= '<div id="modalDetalhe"></div>';
			html+= '</div></div></div></div>';
jQuery('#divModalBox').append( html );
$('#modalBox').modal('show');
//$('#modalBoxDetalhe').load( urlFinal + "&embedded=true");
	$('#modalBox').on('hidden.bs.modal', function (e) {
		//onExitModal();
		$('#modalBox').remove();
	});
//console.log(urlFinal);	
}

function jaCarregado(){
	$("#loading").hide().delay( 800 );
}


function jsMensageBox(tipo,size,title,msg,delay,width){
// tipo = "error", "info", "success", "warning"
if (width != null) {
	widt = 'width: width,';
}else{
	widt = '';
}
	Lobibox.notify(tipo, {
		size: size,
		howClass: 'rollIn',
		hideClass: 'rollOut',
		title: title,
		delay: delay,
		icon: true,
		position: 'bottom left',
		msg: msg,
	});
}
 
function jsAlertBox(tipo,title,msg){
// tipo = "error", "info", "success", "warning"
	Lobibox.alert(tipo, {
		title: title,
		icon: true,
		msg: msg,
	});
} 

// Converte texto para Maiusculo
function convMaiusc(z){
	v = z.value.toUpperCase();
	z.value = v;
}


