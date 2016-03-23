<?php
require("/risorse.php");

//Per la disabilitazione del pulsante
$disabilita_ampliamento = false;

//Informazioni per la stampa delle specifiche di costruzione
$object 		= $warehouse;
$nome_struttura = $strutture->columns_name[8];
$act_level 	    = $strutture->ottieni_livello($nome_struttura, $villo_req);

//Verifica se questo edificio è gia in costruzione
$id_struttura 	= array_search($nome_struttura, $strutture->columns_name);
$in_queue	 	= $costruzioni_code->in_queue($villo_req, $id_struttura);


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