<?php
//Nomi dei singoli input
$name_input_town = "town_name";
$name_input_gold = "resources_gold";
$name_input_wood = "resources_wood";
$name_input_iron = "resources_iron";
$name_input_food = "resources_food";

$default_text	 = "Nome del villaggio...";


//Recupero i dati per un eventuale invio di risorse

$submit 			= $_POST["submit"];
$town_name 			= isset($_POST[$name_input_town])  ? $_POST[$name_input_town] 	: $default_text;
$quantity_gold 		= isset($_POST[$name_input_gold])  ? $_POST[$name_input_gold]	: 0;
$quantity_wood 		= isset($_POST[$name_input_wood])  ? $_POST[$name_input_wood]   : 0;
$quantity_iron 		= isset($_POST[$name_input_iron])  ? $_POST[$name_input_iron]	: 0;
$quantity_food 		= isset($_POST[$name_input_food])  ? $_POST[$name_input_food]	: 0;

$message = $obj_market->message["market"];

if(isset($submit)){

	$attributes = array();

	$attributes["oro"] 		= (int) $quantity_gold;
	$attributes["ferro"] 	= (int) $quantity_wood;
	$attributes["legno"]	= (int) $quantity_iron;
	$attributes["cibo"] 	= (int) $quantity_food;
	
	if($avable_merchants){
		
		if(!empty($town_name)){
			
			//Verifico che il villo verso cui inviare le risorse esista
			$town_exists = $obj_town->exists_town($town_name);
			
			if($town_exists){
				
				//Verifico che il mittente abbia le risorse necessarie
				
				if($quantity_gold > $oro || $quantity_wood > $legno || $quantity_iron > $ferro || $quantity_food > $cibo){
					echo $message["no_resources"];
				}elseif(($quantity_gold + $quantity_wood + $quantity_iron + $quantity_food) == 0){
					echo $message["zero_send"];				
				}else{
					
					//Verifico che il mittente e il destinatario non coincidano !
					$id_dest = $obj_town->getIdByName($town_name);
					
					$attributes["id_destinatario"] = $id_dest;
					
					if($id_dest != $villo_req){
						
						//Tolgo le risorse inviate all'utente
						$obj_resources->modify_resources($villo_req, $attributes);
	
						//Salvo i dati nel db
						$send = $obj_market->send($villo_req, $id_dest, $attributes);

						if($send){
							echo $message["sended"];
							echo '<script type="text/javascript">
							down_resources('.$attributes['oro'].','.$attributes['legno'].','.$attributes['ferro'].','.$attributes['cibo'].',"qt_");
							</script>';
						}
						//@TODO SISTEMRE EFFETTO MERCATO
					}else{
						echo $message["auto_send"];
					}
					
				}
				
			}else{
				echo $message["no_town"];
			}
			
		}else{
			echo $message["no_town"];
		}
	}else{
		//Nessun mercante disponibile !
		echo $message["no_merchants"];
	}
}


//Disegno gli input
$input_town 	= $gui->input("text", $villo, array("key_rules" => "nome", "id" => $name_input_town, "name" => $name_input_town, "value" => $town_name), true);

//Tutti gli input avranno queste caratteristiche
$gui->general_attributes(array("min" => "0"));

$input_range_gold = $gui->input("range",  $risorse, array("id" => "range_" .$name_input_gold, "name" => $name_input_gold, "max" => $oro, "step" => "1", "value" => $quantity_gold));
$input_range_wood = $gui->input("range",  $risorse, array("id" => "range_" .$name_input_wood, "name" => $name_input_wood, "max" => $legno, "step" => "1", "value" => $quantity_wood));
$input_range_iron = $gui->input("range",  $risorse, array("id" => "range_" .$name_input_iron, "name" => $name_input_iron, "max" => $ferro, "step" => "1", "value" => $quantity_iron));
$input_range_food = $gui->input("range",  $risorse, array("id" => "range_" .$name_input_food, "name" => $name_input_food, "max" => $cibo, "step" => "1", "value" => $quantity_food));

$input_text_gold  = $gui->input("number", $risorse, array("id" => $name_input_gold, "name" => $name_input_gold, "max" => $oro, "value" => $quantity_gold));
$input_text_wood  = $gui->input("number", $risorse, array("id" => $name_input_wood, "name" => $name_input_wood, "max" => $legno, "value" => $quantity_wood));
$input_text_iron  = $gui->input("number", $risorse, array("id" => $name_input_iron, "name" => $name_input_iron, "max" => $ferro, "value" => $quantity_iron));
$input_text_food  = $gui->input("number", $risorse, array("id" => $name_input_food, "name" => $name_input_food, "max" => $cibo, "value" => $quantity_food));

$input_submit   = $gui->input("submit", "", array("value" => "Invia le risorse", "name" => "submit"), true);

//TODO File js input.js da miglirare la funzione per gli input numerici 

/*
 * 
 * 		<tr>
			<td><?=$obj_market->field_text["send_to_town_x"]?></td>
			<td><?=$input_coord_x?></td>
			<td>Non necessaria se è presente il nome.</td>
		</tr>
		<tr>
			<td><?=$obj_market->field_text["send_to_town_y"]?></td>
			<td><?=$input_coord_y?></td>
			<td>Non necessaria se è presente il nome.</td>
		</tr>
 * 
 */
?>
<style>
  .ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
  </style>
<form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
	<table>
		<tr>
			<td><?=$obj_market->field_text["send_to_town"]?></td>
			<td><?=$input_town?></td>
		</tr>
		<tr>
			<td><?=$obj_market->field_text["send_res_gold"]?></td>
			<td><?=$input_range_gold?></td>
			<td><?=$input_text_gold?></td>
		</tr>
		<tr>
			<td><?=$obj_market->field_text["send_res_wood"]?></td>
			<td><?=$input_range_wood?></td>
			<td><?=$input_text_wood?></td>
		</tr>
		<tr>
			<td><?=$obj_market->field_text["send_res_iron"]?></td>
			<td><?=$input_range_iron?></td>
			<td><?=$input_text_iron?></td>
		</tr>
		<tr>
			<td><?=$obj_market->field_text["send_res_food"]?></td>
			<td><?=$input_range_food?></td>
			<td><?=$input_text_food?></td>
		</tr>
		<tr>
			<td colspan="3"><?=$input_submit?></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
$(document).ready(function () {
	clean_text("<?=$name_input_town?>", "<?=$default_text?>");
	input_autocomplete("<?=$name_input_town?>", <?php echo $villo_rules["nome"]["length_min"];?>);
});
</script>
<?php 
//@TODO Sincronizzare gli input con le risorse perchè man mano che si aggiornano le risorse gli input hanno sempre lo stesso max
?>