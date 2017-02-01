<?php

class TDataTables {

public static function table( $urlGrid, $name, $lstBtn, $ctMenu=null, $strip=null ){
		echo '<div class="toolbar2 col-xs-5" style="padding-left: 0px;">'.$lstBtn.'
		<input name="idActTable" id="idActTable" maxlength="11" readonly="" type="hidden">
	</div>';
	echo '<div id="tableDiv" poptitle="clique para selecionar!" class="wel1"></div>';
	
	echo '<script type="text/javascript" src="./base/js/jquery-2.2.4.js"></script>';
	echo '<link href="./base/js/contextMenu/jquery.contextMenu.min.css" rel="stylesheet">
  	<script src="./base/js/contextMenu/jquery.contextMenu.min.js"></script>
  	<script src="./base/js/contextMenu/jquery.ui.position.min.js"></script>';
	
	$directory = './base/classes/datatables/';
	echo '<link rel="stylesheet" type="text/css" href="'.$directory.'css/dataTables.bootstrap.css">';
	echo '<link rel="stylesheet" type="text/css" href="'.$directory.'css/buttons.dataTables.css">';
	echo '<script type="text/javascript" src="'.$directory.'js/jquery.dataTables.min.js"></script>';
	echo '<script type="text/javascript" src="'.$directory.'js/dataTables.bootstrap.js"></script>';
	
	echo '<script type="text/javascript" src="'.$directory.'js/dataTables.buttons.js"></script>';
	echo '<script type="text/javascript" src="'.$directory.'js/buttons.html5.min.js"></script>';
	echo '<script type="text/javascript" src="'.$directory.'js/dataTables.select.js"></script>';
	echo '<script type="text/javascript" src="'.$directory.'js/pdfmake.min.js"></script>';
	echo '<script type="text/javascript" src="'.$directory.'js/vfs_fonts.js"></script>';
	echo '<script type="text/javascript" src="'.$directory.'js/buttons.print.min.js"></script>';
	echo '<script type="text/javascript" src="'.$directory.'js/jszip.min.js"></script>';
	echo '<script type="text/javascript" src="'.$directory.'js/buttons.flash.min.js"></script>';
	echo '<script type="text/javascript" src="'.$directory.'js/buttons.colVis.min.js"></script>';
	
	if( $strip ){
		$strip = json_encode($strip, false );
	}else{
		$strip = 1;
	}
	
	echo '<script>
		$( document ).ready( function( $ ) {
			createDataTableJS("'.$urlGrid.'", "'.$name.'", \''.$strip.'\');
		});
	window.setTimeout(function(){
		$(".dataTables_info").addClass(" col-sm-8");
		$(".dataTables_filter").addClass(" col-xs-2");
		$(".dt-button").addClass(" btn btn-success btn-sm");
	}, 1500);
	';	
	if( $ctMenu ){
		echo '
		$(function(){
		    $.contextMenu({
		        selector: ".selected", 
				trigger: "left",
				autoHide: false,
				reposition: true, 
		        items: $.contextMenu.fromMenu($(\'#'.$ctMenu.'\')),
		        position: function(opt, x, y){
			        opt.$menu.css({top: (y - 40), left: (x - 15)});
			    }
		        		
		    });
		});';
	}
	echo '
		</script>';
		
}


public static function simple( $request, $sql ){
	$db = new DB();
	$where = self::filter($request);
	
	if( $request['draw'] == '0'  ){
		$dados = $db->get_results('select x.* from ( '.$sql.' ) as x LIMIT 1 ');
	}else{
		$order = self::order($request);
		$limit = self::limit($request);
		$dados = $db->get_results('select x.* from ( '.$sql.' ) as x '.$where.$order.$limit );
	}			
	$recordsTotal = $db->get_results('select count(*) as qtde from ( '.$sql.' ) as x ' );	
	$recordsFiltered = $db->get_results('select x.*, count(*) as qtde from ( '.$sql.' ) as x '.$where );	
	
	$formatCNPJ = null;
	$k = array_keys($dados[0]);
	$k = array_map('strtoupper', $k);
/*
	if( $formatCNPJ ){
		for ($i = 0; $i < count($dados[$formatCNPJ]); $i++){
			if($dados[$formatCNPJ][$i]){
				$dados[$formatCNPJ][$i] = formatarCPF_CNPJ($dados[$formatCNPJ][$i]);
			}
		}
	}
*/
	
	$i=0;
	foreach($dados as $subarr) {
		foreach($subarr as $id => $value) {
			$d = str_replace('"', "'", $value);
			$data[$i][] = str_replace('', "", $d);
		}
		$i++;
	}


	$qfield = $recordsFiltered[0]['qtde'];
	$tfield = $recordsTotal[0]['qtde'];
	//$data = json_encode($data,JSON_FORCE_OBJECT);
	//$draw = isset ( $_REQUEST['draw'] ) ? intval( $_REQUEST['draw'] ) : 0;
	
	return array(
			"draw"            => isset ( $request['draw'] ) ? intval( $request['draw'] ) : 0,
			"recordsTotal"    => intval( $tfield ),
			"recordsFiltered" => intval( $qfield ),
			"columns"			=> $k,
			"data"           	=> $data
	);
	
}


static function limit ( $request ){
	$limit = '';

	if ( isset($request['start']) && $request['length'] != -1 ) {
		$limit = " LIMIT ".intval($request['start']).", ".intval($request['length']);
	}

	return $limit;
}


static function filter ( $request, $other=null ){
	$sqlStr = null;
	$globalSearch = array();
	
	if ( isset($request['search']) && $request['search']['value'] != '' ) {
			$str = $request['search']['value'];

			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];

				if ( $requestColumn['searchable'] == 'true' ) {
					$globalSearch[] = "`".$requestColumn['name']."` LIKE '%$str%' ";
				}
			}
	}
		
	if ( count( $globalSearch ) ) {
		$sqlStr = '('.implode(' OR ', $globalSearch).')';
	}
	
	if ( $other ){
		if ( $sqlStr ){
			$sqlStr .= ' AND '.$other;
		}else{
			$sqlStr = $other;
		}
	}
	
	if ( $sqlStr != null ) {
		$where = ' WHERE '.$sqlStr;
	}
	
	return $where;
}


static function order ( $request ){
	$order = '';	
	if ( isset($request['order']) && count($request['order']) ) {
		$orderBy = array();

		for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
			$columnIdx = intval($request['order'][$i]['column']);
			$requestColumn = $request['columns'][$columnIdx];

			if ( $requestColumn['orderable'] == 'true' ) {
				$dir = $request['order'][$i]['dir'] === 'asc' ? 'ASC' :	'DESC';

				$orderBy[] = $requestColumn['name'].' '.$dir;
				//$orderBy[] = ($columnIdx + 1).' '.$dir;
			}
		}
		$order = ' ORDER BY '.implode(', ', $orderBy);
	}
	return $order;
}


	
}

