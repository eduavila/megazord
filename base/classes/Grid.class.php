<?php
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

class Grid{
		
public function __construct($name,$data,$seleciona,$height,$overflow){
if( $data != null ){	
		if( $height != null){
			$height = ' height: '.$height.'px;';
		}
		if( $overflow != null){
			$overflow = ' overflow: auto;';
		}
		$dados = $data;
		/*$dados = array();
		while($consulta = $data->fetch_assoc() ){
			$dados[] = $consulta;
		}*/
if (count($dados) > 0){
echo '<script src="./base/classes/tablesorter/jquery.tablesorter.js"></script>';
//echo '<script src="./base/classes/tablesorter/jquery.tablesorter.pager.js"></script>';	
echo '<div style="width:100%; '.$height.$overflow.' " id="'.$name.'_area" class="table table-bordered hidden-print">
	<table class="table table-condensed table-hover table-bordered table-responsive table-striped tablesorterPager" width="100%" style="border: 1px solid #d0d0d0; background-color: #fff;" id="'.$name.'" >
	  <thead>
		<tr>';
		if( $seleciona != null ){
			echo '<th width="3px"><input name="checkbox1" type="radio" id="test1" disabled="disabled" /></th>';
		}	
		  echo '<th width="30px">'.strtoupper(implode('</th><th>', array_keys(current($dados)))).'</th>
		</tr>
	  </thead>
	  <tbody>';
	  foreach ($dados as $row): array_map('htmlentities', $row);
	  $id = $row['id'];
		echo '<tr>';
		if( $seleciona != null){
			echo '<td><input name="checkbox1" id="'.$name.'cbx'.$id.'" type="radio" value="'.$id.'" style="zoom:1.3" ></td>';
		}
		echo '<td><label for="'.$name.'cbx'.$id.'">'.implode('</label></td> <td><label for="'.$name.'cbx'.$id.'">', $row).'</label></td>
		</tr>';
		endforeach;
	  echo '</tbody>
	</table>
	<script>
	  	$("#'.$name.'").tablesorter({widthFixed: true, widgets: [\'zebra\']}); 
	</script>
</div>';
}
//	  $htm .= '</tbody></table></div>';		
	echo $htm;
}else{
		echo '<div class="alert alert-danger"><b>Sem Dados para Mostrar!</b></div>';
	}	  
}
	
	
}

