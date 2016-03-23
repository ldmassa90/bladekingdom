<p>Ecco le offerte attualmente aperte sul mercato</p>
<?php

$name_input_gold = "resources_gold";
$name_input_wood = "resources_wood";
$name_input_iron = "resources_iron";
$name_input_food = "resources_food";

$submit 			= $_POST["submit"];
$quantity_gold 		= isset($_POST[$name_input_gold])  ? $_POST[$name_input_gold]	: 0;
$quantity_wood 		= isset($_POST[$name_input_wood])  ? $_POST[$name_input_wood]   : 0;
$quantity_iron 		= isset($_POST[$name_input_iron])  ? $_POST[$name_input_iron]	: 0;
$quantity_food 		= isset($_POST[$name_input_food])  ? $_POST[$name_input_food]	: 0;

$message = $obj_market->message["market"];

if(isset($submit)){
	$attributes = array();

        $attributes["oro"]      = (int) $quantity_gold;
	$attributes["ferro"] 	= (int) $quantity_wood;
	$attributes["legno"]	= (int) $quantity_iron;
	$attributes["cibo"] 	= (int) $quantity_food;
	if($avable_merchants){
		//Verifico che il mittente abbia le risorse necessarie
				
		if($quantity_gold > $oro || $quantity_wood > $legno || $quantity_iron > $ferro || $quantity_food > $cibo){
			echo $message["no_resources"];
		}elseif(($quantity_gold + $quantity_wood + $quantity_iron + $quantity_food) == 0){
			echo $message["zero_send"];				
		}else{
					
			$id_dest = -1;		//E' un offerta su mercato

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
				
		}
			
	}else{
		//Nessun mercante disponibile !
		echo $message["no_merchants"];
	}
}

$offerte_proprietarie = $obj_market->summary_offers($_SESSION['id_user']);

if(mysqli_num_rows($offerte_proprietarie)){
?>
<p>Signore, se vuole pu&ograve; modificare le sue offerte al momento aperte.</p>

<table>
    <tr>
        <td>Oro</td>
        <td>Ferro</td>
        <td>Legno</td>
        <td>Cibo</td>
        <td>Azioni</td>
    </tr>
<?php

$gui->general_attributes(array("min" => "0"));
    $input_submit   = $gui->input("submit", "", array("value" => "Modifica", "name" => "submit"), true);
    while($info_offers = mysqli_fetch_assoc($offerte_proprietarie)){
	
        $quantity_gold = $info_offers[$obj_market->columns_name[3]];
        $quantity_wood = $info_offers[$obj_market->columns_name[4]];
        $quantity_iron = $info_offers[$obj_market->columns_name[5]];
        $quantity_food = $info_offers[$obj_market->columns_name[6]];
  
        $input_text_gold  = $gui->input("number", $risorse, array("id" => $name_input_gold, "name" => $name_input_gold, "max" => $oro, "value" => $quantity_gold));
        $input_text_wood  = $gui->input("number", $risorse, array("id" => $name_input_wood, "name" => $name_input_wood, "max" => $legno, "value" => $quantity_wood));
        $input_text_iron  = $gui->input("number", $risorse, array("id" => $name_input_iron, "name" => $name_input_iron, "max" => $ferro, "value" => $quantity_iron));
        $input_text_food  = $gui->input("number", $risorse, array("id" => $name_input_food, "name" => $name_input_food, "max" => $cibo, "value" => $quantity_food));

        ?>
        <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
        <tr>
            <td><?=$input_text_gold?></td>
            <td><?=$input_text_wood ?></td>
            <td><?=$input_text_iron ?></td>
            <td><?=$input_text_food ?></td>
            <td><?=$input_submit ?> <button>Elimina</button></td>
        </tr>
        </form>
     <?php
    }

?>
</table>
<?php 
}

$offerte_altrui = $obj_market->summary_offers($_SESSION['id_user'], "!=");

if(mysqli_num_rows($offerte_altrui)){
?>
<p>Signore, ecco l'elenco delle offerte che potrebbero interessarle.</p>
<?php

?>

<table>
    <tr>
        <td>Oro</td>
        <td>Ferro</td>
        <td>Legno</td>
        <td>Cibo</td>
        <td>Tempo</td>
        <td>Azioni</td>
    </tr>
<?php

    while($info_offers = mysqli_fetch_assoc($offerte_altrui)){
	
        $id_sender = $info_offers[$obj_market->columns_name[1]];
        $quantity_gold = $info_offers[$obj_market->columns_name[3]];
        $quantity_wood = $info_offers[$obj_market->columns_name[4]];
        $quantity_iron = $info_offers[$obj_market->columns_name[5]];
        $quantity_food = $info_offers[$obj_market->columns_name[6]];
        $time_end = $obj_market->time_end($id_sender, $_SESSION['id_user']);
        ?>
        
        <tr>
            <td><?=$quantity_gold ?></td>
            <td><?=$quantity_wood ?></td>
            <td><?=$quantity_iron ?></td>
            <td><?=$quantity_food ?></td>
            <td><?=$time_end  ?></td>
            <td><button type="button">Accetta</button></td>
        </tr>
        

     <?php
    }
?>
</table>
<?php } else { ?>
<p>Non ci sono offerte aperte per lei al momento. Riprovi pi&ugrave; tardi.
<?php } ?> 