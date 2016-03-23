<?php
$obj_resources 	= new VilliRisorse();
$strutture 	= new VilliStrutture();
$submit 	= $_POST["submit"];

$autorizzazione = $villo->is_owner($villo_req, $_SESSION["id_user"]);

if(isset($submit)){
	
	//Tipo della struttura da ampliare
	$id_struttura 			= $_POST["strct"];
	$nome_colonna_risorsa 	= "";
	
	switch($id_struttura){
		//TODO sistemare questi id sottoforma di numeri magici
		case 1:		//Miniera
			$nome_colonna_risorsa = $strutture->columns_name[1];
			$risorse_richieste    = $miniera;	//Array contenente i dati di sviluppo della struttura
		break;
		case 2:		//Fattoria
			$nome_colonna_risorsa = $strutture->columns_name[2];
			$risorse_richieste    = $fattoria;	//Array contenente i dati di sviluppo della struttura
		break;		
		case 3:		//Foresta
			$nome_colonna_risorsa = $strutture->columns_name[3];
			$risorse_richieste    = $falegnameria;	//Array contenente i dati di sviluppo della struttura
		break;
		case 4:		//Mercato
			$nome_colonna_risorsa = $strutture->columns_name[4];
			$risorse_richieste    = $market;	//Array contenente i dati di sviluppo della struttura
		break;
		case 8:		//Magazzino
			$nome_colonna_risorsa = $strutture->columns_name[8];
			$risorse_richieste    = $warehouse;	//Array contenente i dati di sviluppo della struttura
		break;
		case 9:		//Mura
			$nome_colonna_risorsa = $strutture->columns_name[9];
			$risorse_richieste    = $wall;	//Array contenente i dati di sviluppo della struttura
		break;
	}

	if(!empty($nome_colonna_risorsa)){
		//Verifico l'eventuale possibilita di espandere la struttura
		$build_message = $strutture->start_up_building($nome_colonna_risorsa, $risorse_richieste, $villo_req);
		echo $build_message;
	}
}