<?php 
require("./system/ClassUniversita.php");

//Informazioni riguardante il villo
$query_info_villo = $obj_town->query("SELECT nome, capitale FROM " . $obj_town->tbl_name . " WHERE id = " . $id_villo);
$info_villo 	  = mysqli_fetch_assoc($query_info_villo);

//Informazioni inerenti alle risorse
$query_info_municipio = $obj_resources->query("SELECT popolazione, tasse, oro, legge FROM " . $obj_resources->tbl_name . " WHERE id = " . $id_villo);
$info_municipio 	  = mysqli_fetch_assoc($query_info_municipio);

//Validazione dei dati ricevuti
$submit = $_POST['submit'];
$errori = "";
$attributi = array();

if(isset($submit)){

	$attributi["nome"] = $_POST["nome_villo"];

	if(!empty($attributi["nome"])){

		$esito = $obj_town->validate($obj_town_rules, $attributi, 2);

		if(is_bool($esito)){		//I dati sono corretti

			if($info_villo["nome"] != $nome_villo){
				
				//Prima dell'aggiornamento verifico che il nome del villaggio scelto non sia già presente
	
				if(!$obj_town->exists_town($nome_villo)){
					
					$obj_town->attributes["id"]   = $obj_town_req;
					$obj_town->attributes["nome"] = $nome_villo;
					
					$validazione1 = $obj_town->update($obj_town);
				}else{
					$errori .= "Questo nome è già esistente !";
				}
			}
		}
		
	}else{
		$errori .= "Il villaggio deve avere un nome !";
	}

	//Vaidazione della legge e tasse
	unset($attributi["nome"]);

	$attributi['tasse'] = (int) $_POST['tasse'];
	$attributi['legge'] = (int) $_POST['law'];

	if($attributi['tasse'] > 100 || $attributi['tasse'] < 0){
		$errori .= "Valore delle tasse fuori dai parametri";
	}else{

		if($attributi['legge'] > 3 || $attributi['legge'] < 1){

			$errori .= "Valore della legge fuori dai parametri";
		}elseif($attributi['tasse'] > 100 || $attributi['tasse'] < 5){
			$errori .= "Valore delle tasse fuori dai parametri";
		}else{
			
			$esito = $obj_resources->validate($obj_resources_rules, $attributi, 2);

			if(is_bool($esito)){		//I dati sono corretti
				
				//Calcolo l'oro e la popolazione per ora
				$popolazione = $info_municipio["popolazione"];
				
				//Oro
				$oro_ad_ora	= (($attributi['tasse'] / 2) / 10) * $popolazione;
				$malus_oro	= $oro_ad_ora / 100;
				
				//Popolazione [Tasso di crescita]
				$tasso_di_crescita  = ((50 - $attributi['tasse']) / 100) * ($popolazione / 2);
				$malus_popolazione  = $tasso_di_crescita / 100;

				//Decremento o incremento l'oro/popolazione a seconda della legge
				
				if($attributi['legge'] == 1){
					$oro_ad_ora 		-= $malus_oro * 15;
					$tasso_di_crescita  += $malus_popolazione  * 10;
				}elseif( $attributi['legge'] == 2){
					$oro_ad_ora			-= $malus_oro * 5;
					$tasso_di_crescita  -= $malus_popolazione  * 5;
				}else{
					$oro_ad_ora 		+= $malus_oro * 15;
					$tasso_di_crescita  -= $malus_popolazione  * 10;
				}
				
				$obj_resources->attributes["id"] 	  = $obj_town_req;
				$obj_resources->attributes["tasse"] = $attributi['tasse'];
				$obj_resources->attributes["legge"] = $attributi['legge'];
				$obj_resources->attributes["oro_per_ora"] = $oro_ad_ora;
				$obj_resources->attributes["pop_per_ora"] = $tasso_di_crescita;
				$validazione2 = $obj_resources->update($obj_resources);

			}else{
				echo $esito;
			}
		}
	}
	
	
	if($validazione1 && $validazione2){
		header("Location: ".$_SERVER['REQUEST_URI']);
	}
}

echo $errori;
?>
<form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
	<table>
		<tr>
			<td><?=$villo_nomi["nome"];?></td>
			<td><?=$gui->input("input", $obj_town, array("key_rules" => "nome", "value" => $info_villo["nome"], "name" => "nome_villo"));?></td>
			<td></td>
		</tr>
		<tr>
			<td><?=$risorse_nomi["tasse"];?></td>
			<td><?=$gui->input("range", $obj_resources, array("key_rules" => "tasse", "min" => "5", "max" => "100", "step" => "5", "value" => $info_municipio["tasse"], "id" => "tax_bar", "name" => "tasse"));?></td>
			<td id="prev_tax"><?=$info_municipio["tasse"];?>%</td>
		</tr>
		<tr>
			<td><?=$risorse_nomi["legge"];?></td>
			<td id="law">
				<table>
					<tr>
						<td>Benevola</td><td><?=$gui->input("radio", "", array("name" => "law", "value" => "1", "checked" => ($info_municipio["legge"] == 1 ? "checked" : "") ));?></td>
					</tr>
					<tr>
						<td>Neutra</td><td><?=$gui->input("radio", "", array("name" => "law", "value" => "2", "checked" => ($info_municipio["legge"] == 2 ? "checked" : "") ));?></td>
					</tr>
					<tr>
						<td>Severa</td><td><?=$gui->input("radio", "", array("name" => "law", "value" => "3", "checked" => ($info_municipio["legge"] == 3 ? "checked" : "") ));?></td>
					</tr>
				</table>
			</td>
			<td id="prev_tax"></td>
		</tr>
		<tr>
			<td><?=$risorse_nomi["oro"];?></td>
			<td id="act_gold"><?=number_format($info_municipio["oro"], 2, '.', '');?></td>
			<td id="prev_gold"></td>
		</tr>
		<tr>
			<td><?=$risorse_nomi["popolazione"];?></td>
			<td id="act_pop"><?=$info_municipio["popolazione"];?></td>
			<td id="prev_pop"></td>
		</tr>
		<tr></tr>
		<tr>
			<td></td>
			<td><?=$gui->input("submit", "", array("value" => "Approva i cambiamenti", "name" => "submit"), true)?></td>
			<td></td>
		</tr>
	</table>
	<table>
		<tr>
			<th>Proclama l'arruolamento forzato.</th>
		</tr>
		<tr>
			<td>
			<?php 
			
			if($strutture->get_level("universita", $id_villo) == 0){
				echo "Per usare quest'ordine occorre costruire una università !";
			}else{
			
				//Ottengo il livello di avanzamento per la sezione civile
				$uni = new Universita();
				
				if($uni->avanzamento_tecnologico($id_villo, 2) < 3){
					echo "Bisogna scoprire ancora questa tecnica... dai uno sguardo nel ramo 'civile' all'interno dell'università.";
				}else{
					echo "DA FARE - ARRUOLAMENTO FORZATO TRUPPE";
				}
			}
		
			
			?>
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">

var pop = <?=$info_municipio["popolazione"];?>;

$("#tax_bar").on("change mousemove", function() {
	//Dynamic text
	var tax = $(this).val();
	$("#prev_tax").html(tax + "%");

	calc_prev();
});

function calc_prev(){

	var tax  = $("#prev_tax").text().slice(0, -1);
	var gold = $("#act_gold").text().slice(0, -2);
	var law  = parseInt($("input[name=law]:checked").val());
	var down = false;
	
	//Forecast
	gold = ((tax / 2) / 10) * pop;
	tdc  = ((50 - tax) / 100) * (pop / 2);

	var dec_gold = gold / 100;
	var dec_pop  = tdc / 100;

	
	switch(law){

		case 1:
			gold -= dec_gold * 15;
			tdc  +=  dec_pop * 10;
		break;
		case 2:
			gold -= dec_gold * 5;
			tdc  -=  dec_pop * 5;
		break;
		case 3:
			gold += dec_gold * 15;
			tdc  -=  dec_pop * 10;
		break;		

	}

	gold = gold.toFixed(2);
	tdc  = tdc.toFixed(2);
	
	$("#prev_gold").html(gold + "/h");
	$("#prev_pop").html(tdc + "/h");
}

$("#law").on("change", function() {
	calc_prev();
});

calc_prev();

</script>