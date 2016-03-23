<script type="text/javascript" src="./js/market/input.js"></script>

<?php
ini_set("display_errors", 0);
require("/risorse.php");
require("/modules/game/map/params.php");
require("./system/ClassMappa.php");
require("./system/ClassMercato.php");
	
$obj_mappa	 = new Mappa();
$obj_market  = new Mercato();

$tb_market_rules 	= $obj_market->rules();
$text_labels 		= $obj_market->attributeLabels();

//Per la disabilitazione del pulsante
$disabilita_ampliamento = false;

//Informazioni per la stampa delle specifiche di costruzione
$object 		= $market;
$nome_struttura = $strutture->columns_name[4];
$act_level 	    = $info_building[$nome_struttura];
$n_merchants 	= $obj_market->avable_merchants($act_level);
$work_merchants = $obj_market->work_merchants($villo_req);

$build_message = $obj_market->message["market"]["default"];

if($act_level){
	$build_message = $obj_market->message["market"]["build"];
}

echo "<br/><p>".$build_message."</p>";

$avable_merchants = $n_merchants - $work_merchants;

echo $obj_market->message["market"]["avable_merchants"] . "" . $avable_merchants;

//Verifica se questo edificio è gia in costruzione
$id_struttura 	= array_search($nome_struttura, $strutture->columns_name);
$in_queue 		= $costruzioni_code->in_queue($villo_req, $id_struttura);

require("/mercato/index.php");

if($act_level != 10){

	if(!$in_queue){
		require("/requirements.php");
	}else{
		//Struttura già in ampliamento
		echo $strutture->message["wait_end_upgrade"] . "" . ($act_level + 1);
		echo "<p id='countdown'>".$in_queue["scadenza"]."</p>";
	}

}
?>
<script type="text/javascript">

$("input[type=range]").on("change mousemove", function() {
	document.getElementById(this.name).value = $(this).val();
	$("#"+this.name).css("background-color", "white");
});

resources_input("<?=$name_input_gold?>", <?=$oro?>);
resources_input("<?=$name_input_wood?>", <?=$legno?>);
resources_input("<?=$name_input_iron?>", <?=$ferro?>);
resources_input("<?=$name_input_food?>", <?=$cibo?>);
</script>