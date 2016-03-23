<?php
require("./system/ClassVilliRisorse.php");
require("./system/ClassVilliStrutture.php");
require("./system/ClassCodaCostruzione.php");

$obj_resources 		= new VilliRisorse();
$costruzioni_code 	= new CodaCostruzione();
$strutture 			= new VilliStrutture();

$strtture_rules 	= $strutture->rules();
$risorse_rules 		= $obj_resources->rules();
$villo_rules		= $obj_town->rules();

require("/villo/params.php");

$autorizzazione = $obj_town->is_owner($villo_req, $_SESSION["id_user"]);

if(!$autorizzazione){
	// TODO Inserire in villo.php r. 8 la stringa per una mappa riassuntiva dato che l'utente non sarÃ  autorizzato a visionare
	echo "Mappa riepilogativa";
	exit();
}

$risorse_nomi 	= $obj_resources->attributeLabels();

$strutture_nomi = $strutture->attributeLabels();
$villo_nomi		= $obj_town->attributeLabels();

//Ottengo i livelli di tutte le strutture per poi utilizzarle in ogni sotto pagina
$query_level_b 		= $strutture->query("SELECT * FROM ".$strutture->tbl_name." WHERE ".$strutture->columns_name[0]." = ".$villo_req);
$info_building	 	= mysqli_fetch_assoc($query_level_b);

require("/villo/risorse.php");

switch($sub_page){
	case $obj_town->id_sotto_voci["villo"]: 	//Villo in generale
		require("/villo/generale.php");
	break;
	case $obj_town->id_sotto_voci["municipio"]: 	//Municipio
		require("/villo/municipio.php");
	break;
	case $obj_town->id_sotto_voci["warehouse"]: 	//Municipio
		require("/villo/warehouse.php");
	break;
	//Gestione materie prime
	case $obj_town->id_sotto_voci["legna"]: 	//Legna
		require("/villo/falegnameria.php");
	break;
	case $obj_town->id_sotto_voci["grano"]: 	//Grano
		require("/villo/fattoria.php");
	break;
	case $obj_town->id_sotto_voci["pietra"]: 	//Pietra
		require("/villo/miniera.php");
	break;
	case $obj_town->id_sotto_voci["ampliamento"]: 	//Costruzione/Ampliamento di una struttura
		require("/villo/build.php");
	break;
	case $obj_town->id_sotto_voci["mercato"]:		//Mercato
		require("/villo/market.php");
	break;
	case $obj_town->id_sotto_voci["wall"]:		//Mura
		require("/villo/wall.php");
	break;
	case $obj_town->id_sotto_voci["barrack"]:		//Mura
		require("/villo/barrack.php");
	break;
	case $obj_town->id_sotto_voci["universita"]:		//Università
		require("/villo/universita.php");
	break;
	case $obj_town->id_sotto_voci["caserma"]:		//Caserma
		require("/villo/caserma.php");
		break;
	default:
		echo $obj_town->message["build_no_found"];	//Struttura non trovata
	break;
}

?>